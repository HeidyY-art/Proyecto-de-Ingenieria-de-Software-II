Documentación Técnica: Sistema de Gestión de Liga de Básquetbol
Link de git: https://github.com/HeidyY-art/Proyecto-de-Ingenieria-de-Software-II.git 
Para iniciar sesión use: 
Nombre de cuenta:admin@liga.com
Contraseña:password123
1. Descripción general de la arquitectura del sistema
Para este proyecto del Sistema de Gestion de Liga de Basquetboll se uso la estructura MVC mediante el framework de  Laravel permitiendo separar la lógica y las consultas a la base de datos de lo que el usuario vera en pantalla.
Para gestionar la BD  y la persistencia se uso migraciones y Eloquent ORM para que los datos se guarden de manera segura y ordenada. Para la presentacion se opto por vistas independientes en Blade que son las plantillas oficiales que vienen integradas en Laravel , estilizadas con Bootstrap 5 para tener un control visual absoluto de cada módulo. Y para la navegación se estructuro una barra lateral fija donde cada vista se define de forma manual y directa su clase “active” para resaltar la sección actual en la que se encuentra el usuario.
2. Listado de clases, controladores y modelos implementados
2.1	Modelos(app/Models/):
•	User:Modelo base utilizado para la autenticación y el control de accesos seguros al panel administrativo.
•	Equipo:Administra los nombres de Equipos, ciudades y sus relaciones directas con los jugadores (1:N) y los partidos (como locales o visitantes).
•	Jugador:Guarda la información detallada del deportista, su posición estandarizada en la cancha y el equipo al que pertenece.
•	Partido:Controla el calendario de encuentros, jornadas, marcadores y los estados del juego (programado, en curso y finalizado).
•	Arbitro:Lleva el directorio de los jueces de la liga y gestiona su relación de uno a muchos con los partidos.
2.2	Controladores(app/Http/Controllers/):
•	AuthController:Administra el acceso seguro al sistema mediante el control de sesiones en el panel de control.
•	EquipoController:Administra el CRUD completo de los equipos, incorporando las reglas necesarias para proteger el historial de partidos finalizados y limpiar encuentros programados antes de ejecutar un borrado.
•	JugadorController:Maneja el alta, edición, filtrado y control operativo de los deportistas vinculados a cada equipo.
•	PartidoController:Controla la programación de encuentros, las validaciones de fechas, las restricciones de rivales y la sincronización del juez mediante la tabla pivote.
•	ArbitroController:Gestiona el directorio arbitral y las restricciones operativas del personal de arbitraje.
2.3	Estructura Clave de BD:
•	Relaciones(1:N) entre equipos y jugadores.
•	Relaciones(1:M) entre árbitros y partidos gestionadas a través de la tabla pivote partido_arbitro.
3. Descripción de las principales funcionalidades desarrolladas
1.	Seguridad y Control de Acceso:Autenticación protegida mediante middleware y sesiones con cifrado de credenciales.
2.	CRUDs Operativos Completos:Flujos robustos para crear, consultar, editar y borrar registros en los diferentes módulos de la liga.
3.	Posiciones Estandarizadas de Jugadores:Se reemplazaron los inputs de texto libre por un menú desplegable (<select>) con las posiciones oficiales de básquetbol para mantener consistencia de datos.
4.	Programación de Partido:Se implementó el módulo de partidos permitiendo definir el equipo local, el equipo visitante con validación cruzada para evitar que un club juegue contra sí mismo.
5.	Asignación Dinámica de Árbitros:Selector desplegable en las vistas de partidos que vincula al arbitro seleccionado utilizando el método sync() sobre la tabla pivote partido_arbitro.
6.	Control Automatizado de Partidos:El sistema evalúa las fechas y horas de los encuentros mediante la librería Carbon, transicionando automáticamente los estados de los partidos entre Programado, En curso y Finalizado según el transcurso de las horas.
7.	Búsqueda Dinámica con Paginación:Filtros por coincidencia (LIKE) combinados con paginate(7) para mantener las tablas ligeras y eficientes.
8.	Eliminación Segura (Integridad Referencial):Validación estricta previa al borrado. Si un equipo o árbitro tiene partidos en estado finalizado, el sistema bloquea la eliminación para proteger el historial deportivo. Los partidos futuros se depuran de forma limpia.
4. Explicación de fragmentos relevantes de código
A.	Lógica de actualización automática de estados basada en intervalos de tiempo con Carbon
public function index()
    {
        $partidosTotales = Partido::all();
        $ahora = Carbon::now();

        foreach ($partidosTotales as $partido) {
            $inicioPartido = Carbon::parse($partido->fecha_partido);
            $finEstimadoPartido = (clone $inicioPartido)->addHours(2); 

            if ($ahora->between($inicioPartido, $finEstimadoPartido)) {
                if ($partido->estado !== 'en_curso') {
                    $partido->update(['estado' => 'en_curso']);
                }
            } 

            elseif ($ahora->greaterThan($finEstimadoPartido)) {
                if ($partido->estado !== 'finalizado') {
                    $partido->update(['estado' => 'finalizado']);
                }
            }

            elseif ($ahora->lessThan($inicioPartido)) {
                if ($partido->estado !== 'programado') {
                    $partido->update(['estado' => 'programado']);
                }
            }
        }

        $partidos = Partido::with(['equipoLocal', 'equipoVisitante'])->orderBy('jornada')->get();
        return view('partidos.index', compact('partidos'));
    }
Se recorre los partidos registrados y se calcula un margen de duración estimada de 2 horas. Si el momento actual se encuentra en ese rango, cambia el estado a encurso, si ya transcurrió el tiempo, lo marca como finalizado, manteniendo la base de datos sincronizada sin intervención manual.

B.	Sincronización de árbitros en Partidos en PartidoController
    public function store(Request $request)
    {
        $request->validate([
            'equipo_local_id' => 'required|exists:equipos,id|different:equipo_visitante_id',
            'equipo_visitante_id' => 'required|exists:equipos,id',
            'fecha_partido' => 'required|date',
            'jornada' => 'required|integer|min:1',
            'puntos_local' => 'nullable|integer|min:0',
            'puntos_visitante' => 'nullable|integer|min:0',
            'estado' => 'required|in:programado,en_curso,finalizado',
            'arbitro_id' => 'required|exists:arbitros,id',
        ], [
            'equipo_local_id.different' => 'El equipo local y el visitante no pueden ser el mismo.',
        ]);

        Partido::create($request->all());
        $partido->arbitros()->sync([$request->arbitro_id]);

        return redirect()->route('partidos.index')->with('success', '¡Partido programado exitosamente!');
    }
Se logra validar  los datos de entrada, crear el partido en su tabla base y usar el método sync() para insertar la relación de manera limpia en la tabla pivote partido_arbitro.   
C.	Blindaje y reglas de eliminación en EquipoController
    public function destroy(Equipo $equipo)
    {
        $partidosFinalizados = $equipo->partidosLocales()->where('estado', 'finalizado')->count() 
                            + $equipo->partidosVisitantes()->where('estado', 'finalizado')->count();

        if ($partidosFinalizados > 0) {
            return redirect()->route('equipos.index')
                ->with('error', '⚠️ No se puede eliminar el equipo porque cuenta con partidos finalizados en su historial.');
        }

        $equipo->partidosLocales()->where('estado', 'programado')->delete();
        $equipo->partidosVisitantes()->where('estado', 'programado')->delete();

        $equipo->delete();

        return redirect()->route('equipos.index')
            ->with('success', '¡Equipo y sus jugadores eliminados correctamente (partidos futuros depurados)!');
    }
Se implemento una barrera,si el equipo ya jugó encuentros que quedaron como finalizado, frenamos el borrado para no romper los datos de partidos. Si solo tiene partidos futuros se procede a eliminar el equipo, permitiendo que la base de datos se encargue de borrar a sus jugadores asociados mediante cascada.
5. Problemas encontrados durante el desarrollo y soluciones aplicadas
1.	Asignación múltiple de árbitros:El formulario enviaba un dato simple para una relación N:M que esperaba un arreglo. 
Solución:Se mantuvo la tabla pivote y se encapsuló el ID en un array [$request->arbitro_id] para que el método sync() lo procesara sin problemas.
2.	Validación cruzada de equipos: Se permitía seleccionar al mismo equipo como local y visitante. 
Solución:Integramos la regla different:equipo_visitante_id en las validaciones del controlador.
3.	Integridad histórica de datos:Al eliminar equipos o árbitros se ponían en riesgo los registros de partidos pasados. 
Solución:Agregamos barreras condicionales en los métodos destroy para bloquear el borrado si existen encuentros finalizados.
4.	Pérdida de filtros en la paginación:Al cambiar de página se borraba el texto ingresado en el buscador. 
Solución:Usamos appends(['search' => $search]) en los enlaces de Blade para mantener los parámetros intactos.
5.	Control de rutas activas:Las plantillas globales fallaban al iluminar las pestañas del menú. 
Solución:Declaramos y controlamos la clase active de forma manual en cada vista de la barra lateral para un control visual absoluto.
6.	Duplicidad en migraciones:Intentar agregar columnas ya existentes arrojaba errores SQL de columnas repetidas. 
Solución:Se depuró el historial de migraciones pendientes y se estructuraron archivos limpios para los nuevos campos.
7.	Incompatibilidad visual de paginación:Laravel renderizaba por defecto con Tailwind, desbordando los diseños de Bootstrap 5. 
Solución:Configuramos Paginator::useBootstrapFive() en el proveedor y ajustamos contenedores flexibles verticales (flex-column align-items-center).
8.	Envíos múltiples:Los clics ansiosos o repetidos en el botón de guardado generaban registros duplicados en simultáneo. 
Solución:Implementamos un bloqueo dinámico del botón vía JavaScript en el evento onsubmit del formulario.
6. Decisiones técnicas tomadas y justificación
1.	Laravel 11:Seleccionado por su seguridad, robustez y eficiencia en rutas y migraciones.
2.	Bootstrap 5:Elegido por su ligereza y componentes responsivos estandarizados.
3.	Integración de Carbon:Utilizado para cálculos precisos de fechas y horas en los partidos.
4.	Validaciones estrictas:Reglas personalizadas en servidor para garantizar la integridad referencial y mensajes claros en español.
5.	Vistas autónomas:Mantenimiento de vistas independientes para un control visual directo y transparente en esta etapa.
6.	Uso de la Tabla Pivote para Árbitros:Aunque inicialmente se evaluó una relación directa, mantener la tabla partido_arbitro deja el sistema preparado para futuras expansiones (N:M).
