<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Programar Partido - Liga de Básquetbol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background-color: #f8f9fa; }
        .sidebar { width: 260px; min-height: 100vh; background-color: #212529; color: #fff; position: fixed; top: 0; left: 0; display: flex; flex-direction: column; justify-content: space-between; }
        .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; border-radius: 6px; margin-bottom: 5px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #0d6efd; }
        .main-content { margin-left: 260px; padding: 30px; }
    </style>
</head>
<body>

    <!-- Panel Lateral (Sidebar) -->
    <div class="sidebar p-3 shadow">
        <div>
            <h4 class="text-white text-center py-3 border-bottom border-secondary mb-4">🏀 Liga Básquet</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('equipos.index') }}">📊 Equipos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('jugadores.index') }}">⛹️‍♂️ Jugadores</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('partidos.index') }}">📅 Partidos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('arbitros.index') }}">⚖️ Árbitros</a></li>
            </ul>
        </div>
        <div class="border-top border-secondary pt-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 btn-sm">Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Programar Nuevo Partido</h2>
            <a href="{{ route('partidos.index') }}" class="btn btn-secondary">Volver al listado</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm border-0 col-md-8 mx-auto">
            <div class="card-body p-4">
                <form action="{{ route('partidos.store') }}" method="POST" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerText = 'Guardando...';">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="equipo_local_id" class="form-label fw-bold">Equipo Local</label>
                            <select name="equipo_local_id" id="equipo_local_id" class="form-select" required>
                                <option value="">Seleccione equipo local...</option>
                                @foreach($equipos as $equipo)
                                    <option value="{{ $equipo->id }}" {{ old('equipo_local_id') == $equipo->id ? 'selected' : '' }}>
                                        {{ $equipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="equipo_visitante_id" class="form-label fw-bold">Equipo Visitante</label>
                            <select name="equipo_visitante_id" id="equipo_visitante_id" class="form-select" required>
                                <option value="">Seleccione equipo visitante...</option>
                                @foreach($equipos as $equipo)
                                    <option value="{{ $equipo->id }}" {{ old('equipo_visitante_id') == $equipo->id ? 'selected' : '' }}>
                                        {{ $equipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_partido" class="form-label fw-bold">Fecha y Hora</label>
                            <input type="datetime-local" name="fecha_partido" id="fecha_partido" class="form-control" value="{{ old('fecha_partido') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="jornada" class="form-label fw-bold">Número de Jornada</label>
                            <input type="number" name="jornada" id="jornada" class="form-control" min="1" value="{{ old('jornada', 1) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3">
                            <label for="lugar" class="form-label">Lugar / Sede del Encuentro</label>
                            <input type="text" name="lugar" id="lugar" class="form-control" 
                                value="{{ old('lugar', $partido->lugar ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="arbitro_id" class="form-label">Árbitro Asignado</label>
                            <select name="arbitro_id" id="arbitro_id" class="form-select" required>
                                <option value="" disabled selected>-- Seleccione un árbitro --</option>
                                
                                @foreach($arbitros as $arbitro)
                                    <option value="{{ $arbitro->id }}" 
                                        {{ (old('arbitro_id') == $arbitro->id) ? 'selected' : '' }}>
                                        {{ $arbitro->nombre }} {{ $arbitro->apellido ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('arbitro_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="puntos_local" class="form-label">Puntos Local (Opcional)</label>
                            <input type="number" name="puntos_local" id="puntos_local" class="form-control" min="0" value="{{ old('puntos_local', 0) }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="puntos_visitante" class="form-label">Puntos Visitante (Opcional)</label>
                            <input type="number" name="puntos_visitante" id="puntos_visitante" class="form-control" min="0" value="{{ old('puntos_visitante', 0) }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label fw-bold">Estado del Partido</label>
                            <select name="estado" id="estado" class="form-select" required>
                                <option value="programado" {{ old('estado') == 'programado' ? 'selected' : '' }}>Programado</option>
                                <option value="en_curso" {{ old('estado') == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                                <option value="finalizado" {{ old('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">Guardar Partido</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>