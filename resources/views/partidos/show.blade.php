<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Partido - Liga de Básquetbol</title>
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
                <li class="nav-item"><a class="nav-link" href="#">⚖️ Árbitros</a></li>
                <li class="nav-item"><a class="nav-link" href="#">🏆 Posiciones</a></li>
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
            <h2>Detalles del Encuentro</h2>
            <a href="{{ route('partidos.index') }}" class="btn btn-secondary">Volver al Listado</a>
        </div>

        <div class="card shadow-sm border-0 col-md-8 mx-auto">
            <div class="card-header bg-dark text-white text-center py-3">
                <h5 class="mb-0">Jornada {{ $partido->jornada }}</h5>
            </div>
            <div class="card-body p-4 text-center">
                
                <div class="row align-items-center my-4">
                    <!-- Equipo Local -->
                    <div class="col-5">
                        <h3 class="fw-bold text-primary">{{ $partido->equipoLocal->nombre ?? 'N/D' }}</h3>
                        <p class="text-muted mb-0">Local</p>
                    </div>

                    <!-- Marcador -->
                    <div class="col-2">
                        <h2 class="fw-bold mb-0">
                            {{ $partido->puntos_local ?? 0 }} - {{ $partido->puntos_visitante ?? 0 }}
                        </h2>
                    </div>

                    <!-- Equipo Visitante -->
                    <div class="col-5">
                        <h3 class="fw-bold text-danger">{{ $partido->equipoVisitante->nombre ?? 'N/D' }}</h3>
                        <p class="text-muted mb-0">Visitante</p>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row text-start px-4">
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">📅 Fecha y Hora:</p>
                        <h5 class="fw-semibold">{{ $partido->fecha_partido }}</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">📌 Estado Actual:</p>
                        <h5>
                            @if($partido->estado == 'finalizado')
                                <span class="badge bg-success">Finalizado</span>
                            @elseif($partido->estado == 'en_curso')
                                <span class="badge bg-warning text-dark">En Curso</span>
                            @else
                                <span class="badge bg-info text-dark">Programado</span>
                            @endif
                        </h5>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('partidos.edit', $partido->id) }}" class="btn btn-warning">Editar Partido</a>
                    <form action="{{ route('partidos.destroy', $partido->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este partido?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
                <!-- Sección Visual de Canchas y Alineaciones -->
<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0 text-center">🏀 Alineaciones en Cancha</h5>
    </div>
    <div class="card-body bg-light">
        <div class="row">
            
            <!-- CANCHA EQUIPO LOCAL -->
            <div class="col-md-6 text-center">
                <h5 class="text-primary fw-bold mb-3">Alineación de {{ $partido->equipoLocal->nombre }}</h5>
                
                <div class="basketball-court position-relative mx-auto shadow rounded border border-3 border-dark" style="width: 100%; max-width: 400px; height: 500px; background-color: #d28c46;">
                    <!-- Líneas de la cancha (decorativo CSS) -->
                    <div class="position-absolute top-50 start-0 end-0 border-top border-white border-2"></div>
                    <div class="position-absolute top-50 start-50 translate-middle rounded-circle border border-white border-2" style="width: 100px; height: 100px;"></div>
                    
                    @foreach($partido->equipoLocal->jugadores as $jugador)
                        @php
                            // Asignamos coordenadas aproximadas en la cancha según la posición
                            $posStyle = match(strtolower($jugador->posicion)) {
                                'base' => 'top: 15%; left: 50%; transform: translateX(-50%);',
                                'escolta' => 'top: 35%; left: 20%;',
                                'alero' => 'top: 35%; right: 20%;',
                                'ala-pívot', 'ala-pivot' => 'top: 65%; left: 25%;',
                                'pívot', 'pivot' => 'top: 65%; right: 25%;',
                                default => 'top: 50%; left: 50%;'
                            };
                        @endphp
                        
                        <div class="position-absolute p-1 bg-white rounded shadow-sm border border-primary text-center" style="{{ $posStyle }} font-size: 11px; width: 90px;">
                            <span class="badge bg-primary rounded-circle">#{{ $jugador->numero }}</span>
                            <div class="fw-bold text-truncate">{{ explode(' ', $jugador->nombre)[0] }}</div>
                            <small class="text-muted text-uppercase" style="font-size: 9px;">{{ $jugador->posicion }}</small>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- CANCHA EQUIPO VISITANTE -->
            <div class="col-md-6 text-center">
                <h5 class="text-danger fw-bold mb-3">Alineación de {{ $partido->equipoVisitante->nombre }}</h5>
                
                <div class="basketball-court position-relative mx-auto shadow rounded border border-3 border-dark" style="width: 100%; max-width: 400px; height: 500px; background-color: #d28c46;">
                    <!-- Líneas de la cancha -->
                    <div class="position-absolute top-50 start-0 end-0 border-top border-white border-2"></div>
                    <div class="position-absolute top-50 start-50 translate-middle rounded-circle border border-white border-2" style="width: 100px; height: 100px;"></div>
                    
                    @foreach($partido->equipoVisitante->jugadores as $jugador)
                        @php
                            $posStyle = match(strtolower($jugador->posicion)) {
                                'base' => 'top: 15%; left: 50%; transform: translateX(-50%);',
                                'escolta' => 'top: 35%; left: 20%;',
                                'alero' => 'top: 35%; right: 20%;',
                                'ala-pívot', 'ala-pivot' => 'top: 65%; left: 25%;',
                                'pívot', 'pivot' => 'top: 65%; right: 25%;',
                                default => 'top: 50%; left: 50%;'
                            };
                        @endphp
                        
                        <div class="position-absolute p-1 bg-white rounded shadow-sm border border-danger text-center" style="{{ $posStyle }} font-size: 11px; width: 90px;">
                            <span class="badge bg-danger rounded-circle">#{{ $jugador->numero }}</span>
                            <div class="fw-bold text-truncate">{{ explode(' ', $jugador->nombre)[0] }}</div>
                            <small class="text-muted text-uppercase" style="font-size: 9px;">{{ $jugador->posicion }}</small>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>