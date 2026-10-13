<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'ciudad'];

    // Un equipo tiene muchos jugadores
    public function jugadores()
    {
        return $this->hasMany(Jugador::class);
    }

    // Un equipo tiene muchos partidos como local
    public function partidosLocales()
    {
        return $this->hasMany(Partido::class, 'equipo_local_id');
    }

    // Un equipo tiene muchos partidos como visitante
    public function partidosVisitantes()
    {
        return $this->hasMany(Partido::class, 'equipo_visitante_id');
    }
}