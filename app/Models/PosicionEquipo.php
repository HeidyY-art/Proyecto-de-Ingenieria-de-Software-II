<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosicionEquipo extends Model
{
    use HasFactory;

    protected $table = 'posicion_equipos';

    protected $fillable = [
        'equipo_id',
        'partidos_jugados',
        'ganados',
        'perdidos',
        'puntos_favor',
        'puntos_contra',
        'puntos'
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
}