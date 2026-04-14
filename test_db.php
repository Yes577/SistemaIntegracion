<?php

$app = require __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Models\Evento;
use App\Models\User;

echo "=== VERIFICAR BASE DE DATOS ===\n\n";

echo "Total usuarios: " . User::count() . "\n";
echo "Total eventos: " . Evento::count() . "\n";

if (User::count() > 0) {
    echo "\n📋 Usuarios en la base de datos:\n";
    User::all()->each(function ($user) {
        echo "  - {$user->name} ({$user->email})\n";
    });
}

if (Evento::count() > 0) {
    echo "\n🎉 Eventos en la base de datos:\n";
    Evento::with('usuario', 'estado')->all()->each(function ($evento) {
        echo "  - {$evento->nombre} (Usuario: {$evento->usuario->name}) - Estado: {$evento->estado->nombre}\n";
    });
} else {
    echo "\n❌ No hay eventos guardados aún.\n";
}

echo "\n✅ La base de datos está funcionando correctamente.\n";
