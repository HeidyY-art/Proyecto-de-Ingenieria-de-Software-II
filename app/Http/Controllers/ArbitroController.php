<?php

namespace App\Http\Controllers;

use App\Models\Arbitro;
use Illuminate\Http\Request;

class ArbitroController extends Controller
{
    public function index()
    {
        $arbitros = Arbitro::all();
        return view('arbitros.index', compact('arbitros'));
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
        $arbitro->delete();
        return redirect()->route('arbitros.index')->with('success', '¡Árbitro eliminado correctamente!');
    }
}