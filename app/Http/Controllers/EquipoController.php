<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $equipos = Equipo::when($search, function ($query, $search) {
            return $query->where('nombre', 'like', "%{$search}%")
                        ->orWhere('ciudad', 'like', "%{$search}%")
                        ->orWhere('entrenador', 'like', "%{$search}%");
        })->paginate(7);

        return view('equipos.index', compact('equipos', 'search'));
    }

    public function create()
    {
        return view('equipos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:equipos',
            'ciudad' => 'required|string|max:255',
            'entrenador' => 'nullable|string|max:255',
        ]);

        Equipo::create($request->all());

        return redirect()->route('equipos.index')->with('success', 'Equipo registrado exitosamente.');
    }

    public function show(Equipo $equipo)
    {
        return view('equipos.show', compact('equipo'));
    }

    public function edit(Equipo $equipo)
    {
        return view('equipos.edit', compact('equipo'));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:equipos,nombre,' . $equipo->id,
            'ciudad' => 'required|string|max:255',
            'entrenador' => 'nullable|string|max:255',
        ]);

        $equipo->update($request->all());

        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado exitosamente.');
    }

    public function destroy(Equipo $equipo)
    {
        $equipo->delete();
        return redirect()->route('equipos.index')->with('success', 'Equipo eliminado exitosamente.');
    }
}