<?php

namespace App\Http\Controllers;

use App\Models\Arbitro;
use Illuminate\Http\Request;

class ArbitroController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $arbitros = Arbitro::when($search, function ($query, $search) {
            return $query->where('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido', 'like', "%{$search}%")
                        ->orWhere('experiencia', 'like', "%{$search}%");
        })->paginate(7);
        return view('arbitros.index', compact('arbitros', 'search'));
    }

    public function create()
    {
        return view('arbitros.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'experiencia' => 'nullable|string|max:255',
        ]);

        Arbitro::create($request->all());

        return redirect()->route('arbitros.index')->with('success', '¡Árbitro registrado exitosamente!');
    }

    public function show(Arbitro $arbitro)
    {
        return view('arbitros.show', compact('arbitro'));
    }

    public function edit(Arbitro $arbitro)
    {
        return view('arbitros.edit', compact('arbitro'));
    }

    public function update(Request $request, Arbitro $arbitro)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'experiencia' => 'nullable|string|max:255',
        ]);

        $arbitro->update($request->all());

        return redirect()->route('arbitros.index')->with('success', '¡Árbitro actualizado correctamente!');
    }

    public function destroy(Arbitro $arbitro)
    {
        $partidosFinalizados = $arbitro->partidos()->where('estado', 'finalizado')->count();

        if ($partidosFinalizados > 0) {
            return redirect()->route('arbitros.index')
                ->with('error', '⚠️ No se puede eliminar el árbitro porque cuenta con partidos finalizados en su historial.');
        }

        $arbitro->partidos()->where('estado', 'programado')->detach();

        $arbitro->delete();

        return redirect()->route('arbitros.index')
            ->with('success', '¡Árbitro eliminado correctamente y desvinculado de su agenda futura!');
    }
}