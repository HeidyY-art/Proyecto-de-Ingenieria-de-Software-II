<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Árbitro</title>
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

    <div class="sidebar p-3 shadow" id="sidebar">
        <div>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary mb-3">
                <h4 class="text-white mb-0 fs-5 text-truncate">🏀 Liga Básquet</h4>
                <button class="btn btn-sm btn-outline-light" id="toggleSidebar" title="Contraer menú">☰</button>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('equipos.index') }}">📊 Equipos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('jugadores.index') }}">⛹️‍♂️ Jugadores</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('partidos.index') }}">📅 Partidos</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('arbitros.index') }}">⚖️ Árbitros</a></li>
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

    <div class="main-content" id="mainContent">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">⚖️ Editar Árbitro</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('arbitros.update', $arbitro) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-bold">Nombre</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $arbitro->nombre) }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="apellido" class="form-label fw-bold">Apellido</label>
                                <input type="text" class="form-control @error('apellido') is-invalid @enderror" id="apellido" name="apellido" value="{{ old('apellido', $arbitro->apellido) }}" required>
                                @error('apellido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="experiencia" class="form-label fw-bold">Experiencia</label>
                                <input type="text" class="form-control @error('experiencia') is-invalid @enderror" id="experiencia" name="experiencia" value="{{ old('experiencia', $arbitro->experiencia) }}">
                                @error('experiencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('arbitros.index') }}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Actualizar Árbitro</button>
                            </div>
                        </form>
                    </div>
                </div>
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