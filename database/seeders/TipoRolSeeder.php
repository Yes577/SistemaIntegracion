<?php

namespace Database\Seeders;

use App\Models\TipoRol;
use Illuminate\Database\Seeder;

class TipoRolSeeder extends Seeder
{
    public function run(): void
    {
        TipoRol::updateOrCreate(['id' => TipoRol::ADMIN_ID], ['nombre' => 'admin']);
        TipoRol::updateOrCreate(['id' => TipoRol::USER_ID], ['nombre' => 'user']);
    }
}
