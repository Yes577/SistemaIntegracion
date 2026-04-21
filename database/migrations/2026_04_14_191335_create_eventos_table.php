<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();

            $table->date('fecha');
            $table->time('hora');

            $table->string('lugar');
            $table->boolean('tiene_parqueadero')->default(false);

            $table->integer('capacidad_maxima');
            $table->integer('capacidad_actual');

            $table->foreignId('id_estado')
                  ->constrained('estados_evento')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('id_usuario')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};