<?php

namespace Tests\Feature;

use App\Jobs\SendCheckInConfirmationEmailJob;
use App\Jobs\SendEventReminderEmailJob;
use App\Jobs\SendInscripcionConfirmationEmailJob;
use App\Mail\InscripcionConfirmadaMail;
use App\Models\EstadoEvento;
use App\Models\Evento;
use App\Models\Inscripcion;
use App\Models\TipoRol;
use App\Models\User;
use App\Services\QrCodeService;
use App\Services\QrTokenService;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EventOperationsModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_inscripcion_dispatches_confirmation_job_and_generates_qr_metadata(): void
    {
        Bus::fake([SendInscripcionConfirmationEmailJob::class]);

        $admin = $this->createAdmin();
        $user = $this->createUser();
        $evento = $this->createEvento($admin, CarbonImmutable::now()->addDays(2));

        $response = $this->actingAs($user)->post(route('panel.inscripciones.store', $evento));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $inscripcion = Inscripcion::first();

        $this->assertNotNull($inscripcion);
        $this->assertNotNull($inscripcion->qr_uuid);
        $this->assertNotNull($inscripcion->qr_emitido_at);
        $this->assertNotNull($inscripcion->qr_expira_at);
        $this->assertSame(Inscripcion::STATUS_PENDING, $inscripcion->estado_check_in);

        Bus::assertDispatched(SendInscripcionConfirmationEmailJob::class, function (SendInscripcionConfirmationEmailJob $job) use ($inscripcion): bool {
            return $job->inscripcionId === $inscripcion->id;
        });
    }

    public function test_confirmation_email_job_sends_mail_with_qr_attachment(): void
    {
        Mail::fake();

        $admin = $this->createAdmin();
        $user = $this->createUser();
        $evento = $this->createEvento($admin, CarbonImmutable::now()->addDays(3));
        $inscripcion = $this->createInscripcion($user, $evento);

        $job = new SendInscripcionConfirmationEmailJob($inscripcion->id);
        $job->handle(app(QrTokenService::class), app(QrCodeService::class));

        Mail::assertSent(InscripcionConfirmadaMail::class, function (InscripcionConfirmadaMail $mail) use ($user): bool {
            return $mail->hasTo($user->email)
                && str_starts_with($mail->qrPng, "\x89PNG")
                && count($mail->attachments()) === 1;
        });
    }

    public function test_qr_generation_and_check_in_flow_work(): void
    {
        Bus::fake([SendCheckInConfirmationEmailJob::class]);

        $admin = $this->createAdmin();
        $user = $this->createUser();
        $evento = $this->createEvento($admin, CarbonImmutable::now()->addDays(1));
        $inscripcion = $this->createInscripcion($user, $evento);

        $token = app(QrTokenService::class)->generateToken($inscripcion);

        $this->assertNotEmpty($token);
        $this->assertNotNull(app(QrTokenService::class)->parseToken($token));
        $this->assertStringContainsString('<svg', app(QrCodeService::class)->generateSvg($token));

        $response = $this->actingAs($admin)->postJson(route('admin.eventos.checkin.validate', $evento), [
            'token' => $token,
        ]);

        $response->assertOk()->assertJson([
            'ok' => true,
        ]);

        $this->assertDatabaseHas('inscripciones', [
            'id' => $inscripcion->id,
            'estado_check_in' => Inscripcion::STATUS_CONFIRMED,
        ]);

        Bus::assertDispatched(SendCheckInConfirmationEmailJob::class, function (SendCheckInConfirmationEmailJob $job) use ($inscripcion): bool {
            return $job->inscripcionId === $inscripcion->id;
        });
    }

    public function test_check_in_rejects_reused_codes(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createUser();
        $evento = $this->createEvento($admin, CarbonImmutable::now()->addDays(1));
        $inscripcion = $this->createInscripcion($user, $evento);

        $token = app(QrTokenService::class)->generateToken($inscripcion);

        $this->actingAs($admin)->postJson(route('admin.eventos.checkin.validate', $evento), [
            'token' => $token,
        ])->assertOk();

        $reuseResponse = $this->actingAs($admin)->postJson(route('admin.eventos.checkin.validate', $evento), [
            'token' => $token,
        ]);

        $reuseResponse->assertStatus(422)->assertJson([
            'ok' => false,
            'reason' => 'already_used',
        ]);
    }

    public function test_check_in_rejects_wrong_event_and_expired_codes(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createUser();
        $eventoA = $this->createEvento($admin, CarbonImmutable::now()->addDays(1));
        $eventoB = $this->createEvento($admin, CarbonImmutable::now()->addDays(2));
        $inscripcionA = $this->createInscripcion($user, $eventoA);

        $tokenA = app(QrTokenService::class)->generateToken($inscripcionA);

        $wrongEventResponse = $this->actingAs($admin)->postJson(route('admin.eventos.checkin.validate', $eventoB), [
            'token' => $tokenA,
        ]);

        $wrongEventResponse->assertStatus(422)->assertJson([
            'ok' => false,
            'reason' => 'wrong_event',
        ]);

        $inscripcionExpirada = $this->createInscripcion($user, $eventoB);
        $inscripcionExpirada->forceFill([
            'qr_expira_at' => now()->subMinute(),
        ])->save();

        $expiredToken = app(QrTokenService::class)->generateToken($inscripcionExpirada->fresh());

        $expiredResponse = $this->actingAs($admin)->postJson(route('admin.eventos.checkin.validate', $eventoB), [
            'token' => $expiredToken,
        ]);

        $expiredResponse->assertStatus(422)->assertJson([
            'ok' => false,
            'reason' => 'expired',
        ]);
    }

    public function test_reminder_command_dispatches_jobs_for_events_due_in_24_hours(): void
    {
        Bus::fake([SendEventReminderEmailJob::class]);

        $admin = $this->createAdmin();
        $user = $this->createUser();
        $startsAt = CarbonImmutable::now()->addHours(23);
        $evento = $this->createEvento($admin, $startsAt);
        $this->createInscripcion($user, $evento);

        $this->artisan('eventos:enviar-recordatorios')
            ->expectsOutput('Recordatorios encolados: 1')
            ->assertExitCode(0);

        Bus::assertDispatched(SendEventReminderEmailJob::class);
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'id_tipo_rol' => TipoRol::ADMIN_ID,
        ]);
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'id_tipo_rol' => TipoRol::USER_ID,
        ]);
    }

    private function createEvento(User $admin, CarbonImmutable $startsAt): Evento
    {
        return Evento::create([
            'nombre' => 'Evento '.fake()->unique()->word(),
            'descripcion' => 'Evento de prueba',
            'fecha' => $startsAt->toDateString(),
            'hora' => $startsAt->format('H:i'),
            'lugar' => 'Auditorio principal',
            'tiene_parqueadero' => false,
            'capacidad_maxima' => 50,
            'capacidad_actual' => 50,
            'id_estado' => EstadoEvento::where('nombre', EstadoEvento::PUBLICADO)->value('id'),
            'id_usuario' => $admin->id,
        ]);
    }

    private function createInscripcion(User $user, Evento $evento): Inscripcion
    {
        $inscripcion = Inscripcion::create([
            'id_user' => $user->id,
            'id_evento' => $evento->id,
            'estado_check_in' => Inscripcion::STATUS_PENDING,
            'qr_emitido_at' => now(),
            'qr_expira_at' => $evento->startsAt()->addHours(24),
        ]);

        return app(QrTokenService::class)->syncInscripcion($inscripcion->fresh());
    }
}
