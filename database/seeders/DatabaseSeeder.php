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
        ]);

        User::updateOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Administrador',
            'password' => Hash::make('admin123'),
            'id_tipo_rol' => TipoRol::ADMIN_ID,
        ]);
    }
}
