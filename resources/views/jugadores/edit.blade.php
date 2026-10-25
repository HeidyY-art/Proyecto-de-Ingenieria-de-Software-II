<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Jugador - Liga de Básquetbol</title>
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

    <div class="sidebar p-3 shadow">
        <div>
            <h4 class="text-white text-center py-3 border-bottom border-secondary mb-4">🏀 Liga Básquet</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('equipos.index') }}">📊 Equipos</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('jugadores.index') }}">⛹️‍♂️ Jugadores</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('partidos.index') }}">📅 Partidos</a></li>
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

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Editar Jugador: {{ $jugador->nombre }} {{ $jugador->apellido }}</h2>
            <a href="{{ route('jugadores.index') }}" class="btn btn-secondary">Volver al Listado</a>
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

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('jugadores.update', $jugador->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $jugador->nombre) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" value="{{ old('apellido', $jugador->apellido) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="numero_camiseta" class="form-label">Número de Camiseta</label>
                        <input type="number" class="form-control" id="numero_camiseta" name="numero_camiseta" value="{{ old('numero_camiseta', $jugador->numero_camiseta ?? '') }}" min="1" max="99" required placeholder="Ej. 23">
                    </div>

                    <div class="mb-3">
                        <label for="posicion" class="form-label fw-bold">Posición</label>
                        <select class="form-select @error('posicion') is-invalid @enderror" id="posicion" name="posicion" required>
                            <option value="" disabled {{ old('posicion', $jugador->posicion ?? '') == '' ? 'selected' : '' }}>Seleccione una posición...</option>
                            <option value="Base" {{ old('posicion', $jugador->posicion ?? '') == 'Base' ? 'selected' : '' }}>Base</option>
                            <option value="Escolta" {{ old('posicion', $jugador->posicion ?? '') == 'Escolta' ? 'selected' : '' }}>Escolta</option>
                            <option value="Alero" {{ old('posicion', $jugador->posicion ?? '') == 'Alero' ? 'selected' : '' }}>Alero</option>
                            <option value="Ala-Pívot" {{ old('posicion', $jugador->posicion ?? '') == 'Ala-Pívot' ? 'selected' : '' }}>Ala-Pívot</option>
                            <option value="Pívot" {{ old('posicion', $jugador->posicion ?? '') == 'Pívot' ? 'selected' : '' }}>Pívot</option>
                        </select>
                        @error('posicion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="equipo_id" class="form-label">Equipo</label>
                        <select class="form-select" id="equipo_id" name="equipo_id" required>
                            @foreach($equipos as $equipo)
                                <option value="{{ $equipo->id }}" {{ old('equipo_id', $jugador->equipo_id) == $equipo->id ? 'selected' : '' }}>
                                    {{ $equipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar Jugador</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>