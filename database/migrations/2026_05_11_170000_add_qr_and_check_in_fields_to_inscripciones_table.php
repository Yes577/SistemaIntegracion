<?php

use Carbon\CarbonImmutable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->uuid('qr_uuid')->nullable()->after('id');
            $table->timestamp('qr_emitido_at')->nullable()->after('qr_uuid');
            $table->timestamp('qr_expira_at')->nullable()->after('qr_emitido_at');
            $table->string('estado_check_in', 20)->default('pendiente')->after('id_parqueadero');
            $table->timestamp('check_in_at')->nullable()->after('estado_check_in');
            $table->timestamp('recordatorio_enviado_at')->nullable()->after('check_in_at');

            $table->unique('qr_uuid');
            $table->index(['id_evento', 'estado_check_in']);
            $table->index('recordatorio_enviado_at');
        });

        $inscripciones = DB::table('inscripciones')
            ->join('eventos', 'eventos.id', '=', 'inscripciones.id_evento')
            ->select([
                'inscripciones.id',
                'inscripciones.created_at',
                'eventos.fecha',
                'eventos.hora',
            ])
            ->orderBy('inscripciones.id')
            ->get();

        foreach ($inscripciones as $inscripcion) {
            $startsAt = CarbonImmutable::parse(sprintf('%s %s', $inscripcion->fecha, $inscripcion->hora));

            DB::table('inscripciones')
                ->where('id', $inscripcion->id)
                ->update([
                    'qr_uuid' => (string) Str::uuid(),
                    'qr_emitido_at' => $inscripcion->created_at ?? now(),
                    'qr_expira_at' => $startsAt->addHours(24),
                    'estado_check_in' => 'pendiente',
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->dropIndex('inscripciones_id_evento_estado_check_in_index');
            $table->dropIndex('inscripciones_recordatorio_enviado_at_index');
            $table->dropUnique('inscripciones_qr_uuid_unique');
            $table->dropColumn([
                'qr_uuid',
                'qr_emitido_at',
                'qr_expira_at',
                'estado_check_in',
                'check_in_at',
                'recordatorio_enviado_at',
            ]);
        });
    }
};
