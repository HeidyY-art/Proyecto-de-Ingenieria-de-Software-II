<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadisticaPartido extends Model
{
    use HasFactory;

    protected $table = 'estadistica_partidos';

    protected $fillable = [
        'partido_id', 
        'jugador_id', 
        'puntos', 
        'rebotes', 
        'asistencias', 
        'faltas'
    ];

    public function partido()
    {
        return $this->belongsTo(Partido::class);
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
}