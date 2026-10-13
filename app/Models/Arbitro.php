<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arbitro extends Model
{
    use HasFactory;

    protected $table = 'arbitros';

    protected $fillable = ['nombre', 'apellido', 'experiencia'];

    // Un árbitro puede dirigir muchos partidos
    public function partidos()
    {
        return $this->belongsToMany(Partido::class, 'partido_arbitro', 'arbitro_id', 'partido_id');
    }
}