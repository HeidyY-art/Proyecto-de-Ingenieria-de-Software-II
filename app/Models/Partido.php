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
        'jornada',
        'lugar',
        'puntos_local', 
        'puntos_visitante', 
        'estado'
    ];

    public function arbitros()
    {
        return $this->belongsToMany(Arbitro::class, 'partido_arbitro', 'partido_id', 'arbitro_id');
    }

    public function equipoLocal()
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

    public function equipoVisitante()
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }

    public function getEstadoActualAttribute()
    {
        
        if ($this->estado === 'finalizado') {
            return 'finalizado';
        }

        $ahora = Carbon::now();
        $inicioPartido = Carbon::parse($this->fecha_partido);
        $finEstimadoPartido = (clone $inicioPartido)->addHours(2);
        
        if ($ahora->between($inicioPartido, $finEstimadoPartido)) {
            if ($this->estado !== 'en_curso') {
                $this->update(['estado' => 'en_curso']);
            }
            return 'en_curso';
        }

        if ($ahora->greaterThan($finEstimadoPartido)) {
            if ($this->estado !== 'finalizado') {
                $this->update(['estado' => 'finalizado']);
            }
            return 'finalizado';
        }
        return 'programado';
    }
}