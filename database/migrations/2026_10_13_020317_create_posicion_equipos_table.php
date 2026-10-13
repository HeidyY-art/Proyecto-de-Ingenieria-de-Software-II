<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posicion_equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->integer('partidos_jugados')->default(0);
            $table->integer('ganados')->default(0);
            $table->integer('perdidos')->default(0);
            $table->integer('puntos_favor')->default(0);
            $table->integer('puntos_contra')->default(0);
            $table->integer('puntos')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posicion_equipos');
    }
};
