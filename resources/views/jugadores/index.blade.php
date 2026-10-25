<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Jugadores - Liga de Básquetbol</title>
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
            <h2>Gestión de Jugadores</h2>
            <a href="{{ route('jugadores.create') }}" class="btn btn-primary">Registrar Nuevo Jugador</a>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route(request()->route()->getName()) }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" value="{{ $search ?? '' }}" placeholder="Buscar...">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                @if(isset($search))
                    <a href="{{ route(request()->route()->getName()) }}" class="btn btn-outline-danger">Limpiar</a>
                @endif
            </div>
        </form>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Nº Camiseta</th>
                            <th>Equipo</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($jugadores)
                            @forelse($jugadores as $jugador)
                            <tr>
                                <td>{{ $jugador->id }}</td>
                                <td>{{ $jugador->nombre }}</td>
                                <td>{{ $jugador->apellido }}</td>
                                <td>{{ $jugador->numero_camiseta }}</td>
                                <td>{{ $jugador->equipo->nombre ?? 'Sin equipo' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('jugadores.show', $jugador->id) }}" class="btn btn-info btn-sm text-white">Ver</a>
                                    <a href="{{ route('jugadores.edit', $jugador->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('jugadores.destroy', $jugador->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este jugador?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No hay jugadores registrados todavía.</td>
                            </tr>
                            @endforelse
                        @endisset
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $jugadores->appends(['search' => $search])->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>