<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipo_local_id', 
        'equipo_visitante_id', 
        'fecha_partido', 
        'puntos_local', 
        'puntos_visitante', 
        'estado'
    ];

    // Un partido pertenece a un equipo local
    public function equipoLocal()
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

    // Un partido pertenece a un equipo visitante
    public function equipoVisitante()
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }
}