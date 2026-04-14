<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parqueaderos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_usuario')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('id_evento')
                  ->constrained('eventos')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->integer('capacidad_total');
            $table->integer('cupos_disponibles');

            $table->string('descripcion')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parqueaderos');
    }
};