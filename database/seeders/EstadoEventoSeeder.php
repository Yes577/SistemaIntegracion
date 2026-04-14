<?php

namespace Database\Seeders;

use App\Models\EstadoEvento;
use Illuminate\Database\Seeder;

class EstadoEventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            ['nombre' => 'borrador', 'descripcion' => 'Evento en estado borrador, aún no publicado'],
            ['nombre' => 'publicado', 'descripcion' => 'Evento publicado y visible para todos'],
            ['nombre' => 'cerrado', 'descripcion' => 'Evento cerrado, no se aceptan más registros'],
            ['nombre' => 'cancelado', 'descripcion' => 'Evento cancelado'],
        ];

        foreach ($estados as $estado) {
            EstadoEvento::firstOrCreate(['nombre' => $estado['nombre']], $estado);
        }
    }
}
