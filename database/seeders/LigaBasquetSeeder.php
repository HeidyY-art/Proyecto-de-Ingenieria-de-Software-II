<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipo;
use App\Models\Jugador;

class LigaBasquetSeeder extends Seeder
{
    public function run(): void
    {
        // Crear Equipos
        $lakers = Equipo::create([
            'nombre' => 'Lakers',
            'ciudad' => 'Los Ángeles'
        ]);

        $bulls = Equipo::create([
            'nombre' => 'Bulls',
            'ciudad' => 'Chicago'
        ]);

        // Crear Jugadores para Lakers
        Jugador::create([
            'equipo_id' => $lakers->id,
            'nombre' => 'LeBron',
            'apellido' => 'James',
            'numero_camiseta' => 23,
            'posicion' => 'Alero'
        ]);

        Jugador::create([
            'equipo_id' => $lakers->id,
            'nombre' => 'Anthony',
            'apellido' => 'Davis',
            'numero_camiseta' => 3,
            'posicion' => 'Pívot'
        ]);

        // Crear Jugadores para Bulls
        Jugador::create([
            'equipo_id' => $bulls->id,
            'nombre' => 'Zach',
            'apellido' => 'LaVine',
            'numero_camiseta' => 8,
            'posicion' => 'Escolta'
        ]);
    }
}