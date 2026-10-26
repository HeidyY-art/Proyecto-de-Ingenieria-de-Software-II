<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use App\Models\Equipo;
use App\Models\Arbitro;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PartidoController extends Controller
{
    public function index()
    {
        $partidosTotales = Partido::all();
        $ahora = Carbon::now();

        foreach ($partidosTotales as $partido) {
            $inicioPartido = Carbon::parse($partido->fecha_partido);
            $finEstimadoPartido = (clone $inicioPartido)->addHours(2); 

            if ($ahora->between($inicioPartido, $finEstimadoPartido)) {
                if ($partido->estado !== 'en_curso') {
                    $partido->update(['estado' => 'en_curso']);
                }
            } 

            elseif ($ahora->greaterThan($finEstimadoPartido)) {
                if ($partido->estado !== 'finalizado') {
                    $partido->update(['estado' => 'finalizado']);
                }
            }

            elseif ($ahora->lessThan($inicioPartido)) {
                if ($partido->estado !== 'programado') {
                    $partido->update(['estado' => 'programado']);
                }
            }
        }

        $partidos = Partido::with(['equipoLocal', 'equipoVisitante'])->orderBy('jornada')->get();
        return view('partidos.index', compact('partidos'));
    }

    public function create()
    {
        $arbitros = Arbitro::all(); 
        $equipos = Equipo::all();
        
        return view('partidos.create', compact('arbitros', 'equipos'));
    } 

    public function store(Request $request)
    {
        $request->validate([
            'equipo_local_id' => 'required|exists:equipos,id|different:equipo_visitante_id',
            'equipo_visitante_id' => 'required|exists:equipos,id',
            'fecha_partido' => 'required|date',
            'jornada' => 'required|integer|min:1',
            'puntos_local' => 'nullable|integer|min:0',
            'puntos_visitante' => 'nullable|integer|min:0',
            'estado' => 'required|in:programado,en_curso,finalizado',
            'arbitro_id' => 'required|exists:arbitros,id',
        ], [
            'equipo_local_id.different' => 'El equipo local y el visitante no pueden ser el mismo.',
        ]);

        Partido::create($request->all());
        $partido->arbitros()->sync([$request->arbitro_id]);

        return redirect()->route('partidos.index')->with('success', '¡Partido programado exitosamente!');
    }

    public function show(Partido $partido)
    {
        return view('partidos.show', compact('partido'));
    }

    public function edit(Partido $partido)
    {
        $arbitros = Arbitro::all(); 
        $equipos = Equipo::all(); 
        return view('partidos.edit', compact('partido', 'arbitros', 'equipos'));
    }

    public function update(Request $request, Partido $partido)
    {
        $request->validate([
            'equipo_local_id' => 'required|exists:equipos,id|different:equipo_visitante_id',
            'equipo_visitante_id' => 'required|exists:equipos,id',
            'fecha_partido' => 'required|date',
            'jornada' => 'required|integer|min:1',
            'puntos_local' => 'nullable|integer|min:0',
            'puntos_visitante' => 'nullable|integer|min:0',
            'estado' => 'required|in:programado,en_curso,finalizado',
            'arbitro_id' => 'required|exists:arbitros,id',
        ], [
            'equipo_local_id.different' => 'El equipo local y el visitante no pueden ser el mismo.',
        ]);

        $partido->update($request->all());
        $partido->arbitros()->sync([$request->arbitro_id]);
        return redirect()->route('partidos.index')->with('success', '¡Partido actualizado correctamente!');
    }

    public function destroy(Partido $partido)
    {
        $partido->delete();
        return redirect()->route('partidos.index')->with('success', '¡Partido eliminado correctamente!');
    }
}