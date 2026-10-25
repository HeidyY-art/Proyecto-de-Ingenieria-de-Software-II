<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Árbitros</title>
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

    <!-- Menú Lateral -->
    <div class="sidebar p-3 shadow" id="sidebar">
        <div>
            <h4 class="text-white text-center py-3 border-bottom border-secondary mb-4">🏀 Liga Básquet</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('equipos.index') }}">📊 Equipos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('jugadores.index') }}">⛹️‍♂️ Jugadores</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('partidos.index') }}">📅 Partidos</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('arbitros.index') }}">⚖️ Árbitros</a></li>
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
    <div class="main-content" id="mainContent">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestión de Árbitros</h2>
            <a href="{{ route('arbitros.create') }}" class="btn btn-primary">+ Registrar Árbitro</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('arbitros.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" value="{{ $search ?? '' }}" placeholder="Buscar...">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                @if(isset($search))
                    <a href="{{ route('arbitros.index') }}" class="btn btn-outline-danger">Limpiar</a>
                @endif
            </div>
        </form>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Experiencia</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($arbitros as $arbitro)
                                <tr>
                                    <td>{{ $arbitro->id }}</td>
                                    <td>{{ $arbitro->nombre }} {{ $arbitro->apellido }}</td>
                                    <td>{{ $arbitro->experiencia ?? 'No especificada' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('arbitros.show', $arbitro) }}" class="btn btn-sm btn-info text-white">Ver</a>
                                        <a href="{{ route('arbitros.edit', $arbitro) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('arbitros.destroy', $arbitro) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este árbitro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No hay árbitros registrados todavía.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $arbitros->appends(['search' => $search])->links() }}
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        });
    </script>
</body>
</html>