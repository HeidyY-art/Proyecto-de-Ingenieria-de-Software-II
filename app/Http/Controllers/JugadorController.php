<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use App\Models\Equipo;
use Illuminate\Http\Request;

class JugadorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $jugadores = Jugador::with('equipo')
            ->when($search, function ($query, $search) {
                return $query->where('nombre', 'like', "%{$search}%")
                            ->orWhere('apellido', 'like', "%{$search}%")
                            ->orWhere('posicion', 'like', "%{$search}%")
                            ->orWhereHas('equipo', function ($q) use ($search) { // <-- ¡Aquí faltaba la palabra "use"!
                                $q->where('nombre', 'like', "%{$search}%");
                            });
            })->paginate(7);

        return view('jugadores.index', compact('jugadores', 'search'));
    }

    public function create()
    {
        $equipos = Equipo::all();
        return view('jugadores.create', compact('equipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'equipo_id' => 'required|exists:equipos,id',
            'numero_camiseta' => [
                'required',
                'integer',
                'min:1',
                'max:99',
                \Illuminate\Validation\Rule::unique('jugadores')->where(function ($query) use ($request) {
                    return $query->where('equipo_id', $request->equipo_id);
                }),
            ],
        ], [
            'numero_camiseta.unique' => 'Este número de camiseta ya está ocupado por otro jugador en este equipo.',
            'numero_camiseta.min' => 'El número de camiseta debe ser mayor o igual a 1.',
        ]);

        Jugador::create($request->all());

        return redirect()->route('jugadores.index')->with('success', '¡Jugador registrado exitosamente!');
    }

    public function show(Jugador $jugador)
    {
        $jugador->load('equipo');
        return view('jugadores.show', compact('jugador'));
    }

    public function edit(Jugador $jugador)
    {
        $equipos = Equipo::all();
        return view('jugadores.edit', compact('jugador', 'equipos'));
    }

    public function update(Request $request, Jugador $jugador)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'posicion' => 'required|string|max:255',
            'equipo_id' => 'required|exists:equipos,id',
            'numero_camiseta' => [
                'required',
                'integer',
                'min:1',
                'max:99',
                \Illuminate\Validation\Rule::unique('jugadores')->where(function ($query) use ($request) {
                    return $query->where('equipo_id', $request->equipo_id);
                })->ignore($jugador->id),
            ],
        ], [
            'numero_camiseta.unique' => 'Este número de camiseta ya está ocupado por otro jugador en este equipo.',
            'numero_camiseta.min' => 'El número de camiseta debe ser mayor o igual a 1.',
        ]);

        $jugador->update($request->all());

        return redirect()->route('jugadores.index')->with('success', '¡Jugador actualizado correctamente!');
    }

    public function destroy(Jugador $jugador)
    {
        $jugador->delete();
        return redirect()->route('jugadores.index')->with('success', '¡Jugador eliminado!');
    }
}

