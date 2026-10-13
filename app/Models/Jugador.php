<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jugador extends Model
{
    use HasFactory;

    protected $table = 'jugadores'; // Añade esta línea

    protected $fillable = ['equipo_id', 'nombre', 'apellido', 'numero_camiseta', 'posicion'];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
}