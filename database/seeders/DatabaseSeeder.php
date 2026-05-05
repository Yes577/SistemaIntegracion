<?php

namespace Database\Seeders;

use App\Models\TipoRol;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TipoRolSeeder::class,
            EstadoEventoSeeder::class,
        ]);

        $bootstrapAdminEmail = trim((string) env('ADMIN_BOOTSTRAP_EMAIL', ''));
        $bootstrapAdminPassword = (string) env('ADMIN_BOOTSTRAP_PASSWORD', '');
        $bootstrapAdminName = trim((string) env('ADMIN_BOOTSTRAP_NAME', 'Administrador'));

        if ($bootstrapAdminEmail === '' || $bootstrapAdminPassword === '') {
            $this->command?->warn(
                'Bootstrap admin skipped: define ADMIN_BOOTSTRAP_EMAIL and ADMIN_BOOTSTRAP_PASSWORD before seeding.'
            );
            return;
        }

        User::updateOrCreate([
            'email' => $bootstrapAdminEmail,
        ], [
            'name' => $bootstrapAdminName,
            'password' => Hash::make($bootstrapAdminPassword),
            'id_tipo_rol' => TipoRol::ADMIN_ID,
        ]);
    }
}
