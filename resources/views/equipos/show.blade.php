<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Equipo - Liga de Básquetbol</title>
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
                <li class="nav-item"><a class="nav-link active" href="{{ route('equipos.index') }}">📊 Equipos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('jugadores.index') }}">⛹️‍♂️ Jugadores</a></li>
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
            <h2>Detalles del Equipo</h2>
            <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Volver al Listado</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title text-primary mb-3">{{ $equipo->nombre }}</h4>
                <p class="card-text"><strong>Ciudad:</strong> {{ $equipo->ciudad }}</p>
                <p class="card-text"><strong>Entrenador:</strong> {{ $equipo->entrenador ?? 'No asignado' }}</p>
                
                <hr>
                <h5 class="mt-4">Jugadores de este equipo:</h5>
                <ul>
                    @forelse($equipo->jugadores ?? [] as $jugador)
                        <li>{{ $jugador->nombre }} {{ $jugador->apellido }} (Nº {{ $jugador->numero_camiseta }})</li>
                    @empty
                        <li class="text-muted">No hay jugadores registrados en este equipo todavía.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>