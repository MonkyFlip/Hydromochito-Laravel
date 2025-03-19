<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="{{ asset('css/perfil_usuario.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar_perfil_usuario.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar Mejorada -->
    <nav class="navbar navbar-expand-lg">
        <!-- Logotipo y nombre -->
        <a class="navbar-brand" href="#">
            <img src="{{ asset('image/Logo-sin-fondo.png') }}" alt="Hydromochito Logo" width="60" height="60"
                class="d-inline-block align-top">
            Hydromochito
        </a>

        <!-- Botones de navegación -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('visitantes.index') }}">
                        <i class="bi bi-people"></i> Visitantes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('perfil_usuario.index') }}">
                        <i class="bi bi-person"></i> Perfil Usuario
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <!-- Sección Izquierda -->
        <aside class="sidebar">
            <div class="saludo-rol">
                <p>
                    @if(Session::has('user_name') && Session::has('user_role'))
                    <span>¡Hola, {{ Session::get('user_name') }}! Tu presencia como
                        <strong>{{ Session::get('user_role') }}</strong> es esencial para nosotros. ¡Sigamos avanzando
                        juntos!</span>
                    @else
                    <span>¡Hola! Invitado, tu viaje empieza aquí. ¿Qué podemos hacer por ti hoy?</span>
                    @endif
                </p>
            </div>

            <div class="botones">
                <!-- Botón de Ver Gráficos -->
                <button class="btn-graficos" data-user-name="{{ Session::get('user_name', 'Usuario Anónimo') }}"
                    data-bs-toggle="modal" data-bs-target="#graficosModal">
                    <i class="bi bi-bar-chart"></i> Ver Gráficos
                </button>

                <!-- Botón de Exportar Excel -->
                <a href="{{ route('reporte.perfil_usuario.excel') }}" class="btn-reporte-excel" target="_blank">
                    <i class="bi bi-file-earmark-excel"></i> Generar Reporte Excel
                </a>

                <!-- Botón de Previsualizar PDF -->
                <form id="previsualizarPdfFormCompleto" action="{{ route('reporte.perfil_usuario.pdf') }}" method="POST"
                    target="_blank" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-reporte-pdf">
                        <i class="bi bi-file-earmark-pdf"></i> Previsualizar Reporte PDF
                    </button>
                </form>
            </div>
        </aside>

        <main class="contenido">
            <div class="tabla-contenedor">
                <h2 class="tabla-titulo text-center">Registros de Hydromochito</h2>

                <div class="d-flex justify-content-start mb-3">
                    <!-- Botón para abrir el Modal -->
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregarRegistroModal">
                        <i class="bi bi-plus-circle"></i> Agregar Registro
                    </button>
                </div>

                <div class="table-responsive">
                    <div class="tabla-contenedor">
                        <table id="tabla-perfil-usuario" class="tabla-niagara">
                            <thead>
                                <tr>
                                    <th class="hidden-column">ID</th> <!-- Ocultar visualmente -->
                                    <th>Flujo de Agua</th>
                                    <th>Nivel de Agua</th>
                                    <th>Temperatura</th>
                                    <th>Energía</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registros as $registro)
                                <tr>
                                    <td class="hidden-column">{{ $registro->id_registro }}</td>
                                    <!-- Ocultar visualmente -->
                                    <td>{{ $registro->flujo_agua }}</td>
                                    <td>{{ $registro->nivel_agua }}</td>
                                    <td>{{ $registro->temp }}°C</td>
                                    <td>{{ $registro->energia }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm btn-action" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminarRegistro"
                                            data-id="{{ $registro->id_registro }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No se encontraron registros.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center pagination-container">
                    {{ $registros->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5', ['pageName' => 'page_registros']) }}
                </div>
        </main>

        <!-- Modal Agregar Registro -->
        <div class="modal fade" id="agregarRegistroModal" tabindex="-1" aria-labelledby="agregarRegistroLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="agregarRegistroLabel">Agregar Registro</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createForm" method="post" action="{{ route('perfil_usuario.registrar') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="flujo_agua" class="form-label">Flujo de Agua</label>
                                <input type="number" class="form-control" id="flujo_agua" name="flujo_agua" step="0.01"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="nivel_agua" class="form-label">Nivel de Agua</label>
                                <input type="number" class="form-control" id="nivel_agua" name="nivel_agua" step="0.01"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="temp" class="form-label">Temperatura</label>
                                <input type="number" class="form-control" id="temp" name="temp" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="energia" class="form-label">Energía</label>
                                <select class="form-control" id="energia" name="energia" required>
                                    <option value="solar">Solar</option>
                                    <option value="electricidad">Electricidad</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Eliminar Registro -->
        <div class="modal fade" id="modalEliminarRegistro" tabindex="-1" aria-labelledby="eliminarRegistroLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="eliminarRegistroLabel">Confirmar Eliminación</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.</p>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary me-2"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Gráficos -->
        <div class="modal fade" id="graficosModal" tabindex="-1" aria-labelledby="graficosModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="graficosModalLabel">Registros en Gráficos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Aviso al Usuario -->
                        <div class="alert alert-info">
                            Selecciona un rango de registros indicando el número de inicio y fin. Haz clic en
                            "Actualizar Gráfico" para visualizar los datos correspondientes.
                        </div>

                        <!-- Controles de Rango -->
                        <div class="mb-3">
                            <label for="rangeStart" class="form-label">Inicio del rango:</label>
                            <input type="number" id="rangeStart" class="form-control" placeholder="1">
                        </div>
                        <div class="mb-3">
                            <label for="rangeEnd" class="form-label">Fin del rango:</label>
                            <input type="number" id="rangeEnd" class="form-control" placeholder="10">
                        </div>
                        <button id="updateChartButton" class="btn btn-primary">Actualizar Gráfico</button>

                        <!-- Gráfico -->
                        <canvas id="registrosChart" width="400" height="200" class="mt-3"></canvas>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cerrar btn btn-secondary"
                            data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

<!-- Scripts Bootstrap 5.3.3 -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script src="js/perfil_usuario.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Selecciona todos los botones de eliminar
    const eliminarButtons = document.querySelectorAll('.btn-accion.eliminar');

    eliminarButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Obtén el id del registro del atributo data-id
            const idRegistro = this.getAttribute('data-id');

            // Establece el valor del campo oculto en el modal
            document.getElementById('idRegistroEliminar').value = idRegistro;

            // Actualiza dinámicamente el 'action' del formulario
            const formEliminar = document.getElementById('formEliminarRegistro');
            const actionUrl = "{{ route('registros_iot.destroy', ['id' => ':id']) }}".replace(
                ':id', idRegistro);
            formEliminar.action = actionUrl;
        });
    });
});
</script>

</html>