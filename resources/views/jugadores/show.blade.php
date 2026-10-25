<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Jugador - Liga de Básquetbol</title>
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
                <li class="nav-item"><a class="nav-link" href="#">🏆 <span>Posiciones</span></a></li>
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
            <h2>Detalles del Jugador</h2>
            <a href="{{ route('jugadores.index') }}" class="btn btn-secondary">Volver al Listado</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h3 class="card-title text-primary mb-3">{{ $jugador->nombre }} {{ $jugador->apellido }}</h3>
                <p class="card-text"><strong>Número de Camiseta:</strong> #{{ $jugador->numero_camiseta }}</p>
                <p class="card-text"><strong>Posición:</strong> {{ $jugador->posicion }}</p>
                <p class="card-text"><strong>Equipo al que pertenece:</strong> {{ $jugador->equipo->nombre ?? 'Sin equipo asignado' }}</p>
                <p class="card-text"><strong>Ciudad del Equipo:</strong> {{ $jugador->equipo->ciudad ?? 'N/A' }}</p>
                <p class="card-text"><strong>Entrenador del Equipo:</strong> {{ $jugador->equipo->entrenador ?? 'N/A' }}</p>
                
                <hr>
                <div class="mt-4">
                    <a href="{{ route('jugadores.edit', $jugador->id) }}" class="btn btn-warning">Editar Jugador</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>