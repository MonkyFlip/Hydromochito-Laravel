<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros IoT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/registros_iot.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <!-- Logotipo y nombre de la PMV -->
        <a class="navbar-brand" href="{{ asset('image/Logo-sin-fondo.png') }}">
            <img src="{{ asset('image/Logo-sin-fondo.png') }}" alt="Hydromochito" width="60" height="60"
                class="d-inline-block align-top">
            Hydromochito
        </a>

        <!-- Saludo personalizado en el navbar -->
        <div class="navbar-text">
            @if(Session::has('user_name'))
            <span>Hola, {{ Session::get('user_name') }}!</span>
            @else
            <span>Hola, Invitado!</span>
            @endif
        </div>

        <!-- Barra de filtrado -->
        <form class="d-flex" action="{{ route('registros_iot.index') }}" method="GET">
            <input class="form-control me-2" type="search" name="query" placeholder="Buscar" aria-label="Buscar"
                value="{{ request('query') }}">
            <button class="btn btn-outline-success me-2" type="submit">
                <i class="bi bi-search"></i>
            </button>
            <button class="btn btn-outline-danger" type="reset" id="clear-filter">
                <i class="bi bi-x-circle"></i>
            </button>
        </form>

        <!-- Botones de navegación -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Menú principal -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuPrincipal" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-house"></i> Menú principal
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuPrincipal">
                        <li>
                            <a class="dropdown-item" href="{{ route('registros_usuarios.index') }}">
                                <i class="bi bi-people"></i> Registros Usuarios
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('registros_iot.index') }}">
                                <i class="bi bi-diagram-3"></i> Registros IoT
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('visitantes.index') }}">
                                <i class="bi bi-eye"></i> Visitantes
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Menú de reportes -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownReportes" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-file-earmark-text"></i> Reportes
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownReportes">
                        <!-- Opción para importar registros -->
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="bi bi-upload"></i> Importar registros
                            </a>
                        </li>

                        <!-- Opciones para exportar -->
                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-download"></i> Exportar
                            </a>
                            <ul class="dropdown-menu">
                                <!-- Exportar Excel (Reporte Completo) -->
                                <li>
                                    <a class="dropdown-item dropdown-item-excel"
                                        href="{{ route('reportes.registros_iot.excel') }}">
                                        <i class="bi bi-file-earmark-excel"></i> Exportar Excel (Reporte Completo)
                                    </a>
                                </li>
                                <!-- Exportar Excel (Reporte Actual) -->
                                <li>
                                    <form id="exportExcelForm" action="{{ route('reporte.excel') }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <input type="hidden" name="datos" id="excelDatos">
                                        <input type="hidden" name="columnas" id="excelColumnas">
                                        <button type="submit" class="dropdown-item" onclick="prepareExcelData()">
                                            <i class="bi bi-file-earmark-excel"></i> Exportar Excel (Reporte Actual)
                                        </button>
                                    </form>
                                </li>
                                <!-- Previsualizar PDF (Reporte Completo) -->
                                <li>
                                    <a class="dropdown-item dropdown-item-pdf"
                                        href="{{ route('reportes.registros_iot.pdf') }}" target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i> Previsualizar PDF (Reporte Completo)
                                    </a>
                                </li>
                                <!-- Previsualizar PDF (Reporte Actual) -->
                                <li>
                                    <form id="previsualizarPdfForm" action="{{ route('reporte.previsualizar-pdf') }}"
                                        target="_blank" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="datos" id="previsualizarPdfDatos">
                                        <input type="hidden" name="columnas" id="previsualizarPdfColumnas">
                                        <button type="submit" class="dropdown-item"
                                            onclick="preparePrevisualizarPdfData()">
                                            <i class="bi bi-file-earmark-pdf"></i> Previsualizar PDF (Reporte Actual)
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- Menú de gráficos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownGraficos" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bar-chart"></i> Gráficos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownGraficos">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#graficoModal">
                                <i class="bi bi-water"></i> Gráficos Hydromochito
                            </a></li>
                    </ul>
                </li>

                <!-- Botón de cerrar sesión -->
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

    <div class="container mt-1">
        <!-- Botón para agregar un registro -->
        <div class="container mt-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregarRegistroModal">
                <i class="bi bi-plus-circle"></i> Agregar Registro
            </button>
        </div>
        <div id="registrosTable" class="container mt-1 table-responsive">
            <!-- Registros Table -->
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID Registro</th>
                        <th>Flujo de Agua</th>
                        <th>Nivel de Agua</th>
                        <th>Temperatura</th>
                        <th>Energía</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paginator as $registro)
                    <tr>
                        <td>{{ $registro['id_registro'] }}</td>
                        <td>{{ $registro['flujo_agua'] }}</td>
                        <td>{{ $registro['nivel_agua'] }}</td>
                        <td>{{ $registro['temp'] }}</td>
                        <td>{{ $registro['energia'] }}</td>
                        <td>{{ $registro['nombre_usuario'] }}</td> <!-- Mostrar el nombre del usuario -->
                        <td>
                            <!-- Botón Ver Registro -->
                            <button class="btn btn-primary btn-sm btn-action" data-bs-toggle="modal"
                                data-bs-target="#verRegistroModal">
                                <i class="bi bi-eye"></i>
                            </button>
                            <!-- Botón Editar Registro -->
                            <button class="btn btn-warning btn-sm btn-action" data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $registro['id_registro'] }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <!-- Botón Eliminar Registro -->
                            <button class="btn btn-danger btn-sm btn-action" data-bs-toggle="modal"
                                data-bs-target="#eliminarRegistroModal">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <!-- Modal Editar -->
                    <div class="modal fade" id="editModal{{ $registro['id_registro'] }}" tabindex="-1"
                        aria-labelledby="editModalLabel{{ $registro['id_registro'] }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $registro['id_registro'] }}">Editar
                                        Registro</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('registros_iot.update', $registro['id_registro']) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="flujo_agua" class="form-label">Flujo de Agua</label>
                                            <input type="number" step="0.01" class="form-control" name="flujo_agua"
                                                value="{{ $registro['flujo_agua'] }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nivel_agua" class="form-label">Nivel de Agua</label>
                                            <input type="number" step="0.01" class="form-control" name="nivel_agua"
                                                value="{{ $registro['nivel_agua'] }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="temp" class="form-label">Temperatura</label>
                                            <input type="number" step="0.01" class="form-control" name="temp"
                                                value="{{ $registro['temp'] }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="energia" class="form-label">Energía</label>
                                            <select class="form-control" name="energia" required>
                                                <option value="solar"
                                                    {{ $registro['energia'] == 'solar' ? 'selected' : '' }}>Solar
                                                </option>
                                                <option value="electricidad"
                                                    {{ $registro['energia'] == 'electricidad' ? 'selected' : '' }}>
                                                    Electricidad</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="id_usuario" class="form-label">Usuario</label>
                                            <input type="text" class="form-control" name="nombre_usuario"
                                                value="{{ $registro['nombre_usuario'] }}" required readonly>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
            <!-- Código de paginación de registros IoT -->
            <div class="d-flex justify-content-center pagination-container">
                {{ $paginator->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5', ['pageName' => 'page_registros']) }}
            </div>
        </div>

        <!-- Modales -->

        <!-- Modal Agregar Registro -->
        <div class="modal fade" id="agregarRegistroModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Registro</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createForm" method="post" action="{{ route('registros_iot.store') }}">
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
                            <div class="mb-3">
                                <label for="id_usuario" class="form-label">Usuario</label>
                                <select class="form-control" id="id_usuario" name="id_usuario" required>
                                    <option value="">Seleccione un usuario</option>
                                    @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario['id_usuario'] }}">{{ $usuario['nombre'] }}</option>
                                    @endforeach
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

        <!-- Modal Ver Registro -->
        <div class="modal fade" id="verRegistroModal" tabindex="-1" aria-labelledby="verRegistroLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verRegistroLabel">Ver Registro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>ID Registro:</strong> <span id="verIdRegistro"></span></p>
                        <p><strong>Flujo de Agua:</strong> <span id="verFlujoAgua"></span></p>
                        <p><strong>Nivel de Agua:</strong> <span id="verNivelAgua"></span></p>
                        <p><strong>Temperatura:</strong> <span id="verTemp"></span></p>
                        <p><strong>Energía:</strong> <span id="verEnergia"></span></p>
                        <p><strong>Usuario:</strong> <span id="verIdUsuario"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Eliminar Registro -->
        <div class="modal fade" id="eliminarRegistroModal" tabindex="-1" aria-labelledby="eliminarRegistroLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eliminarRegistroLabel">Eliminar Registro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>ID Registro:</strong> <span id="deleteIdRegistro"></span></p>
                        <p><strong>Flujo de Agua:</strong> <span id="deleteFlujoAgua"></span></p>
                        <p><strong>Nivel de Agua:</strong> <span id="deleteNivelAgua"></span></p>
                        <p><strong>Temperatura:</strong> <span id="deleteTemp"></span></p>
                        <p><strong>Energía:</strong> <span id="deleteEnergia"></span></p>
                        <p><strong>Usuario:</strong> <span id="deleteIdUsuario"></span></p>
                        <p>¿Estás seguro de que deseas eliminar este registro?</p>
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" method="post" action="">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Aviso -->
        <div class="modal fade modal-aviso" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="statusModalLabel">Aviso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Mensaje de estado dinámico -->
                        <p class="text-secondary">Aquí se mostrará el mensaje de estado.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para mostrar Gráficos -->
        <div class="modal fade graphics-modal" id="graficoModal" tabindex="-1" aria-labelledby="graficoModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="graficoModalLabel">Gráficos de Hydromochito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Canvases para los gráficos en una cuadrícula de 4 -->
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <canvas id="graficoFlujoAgua" width="400" height="200"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <canvas id="graficoNivelAgua" width="400" height="200"></canvas>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <canvas id="graficoTemp" width="400" height="200"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <canvas id="graficoEnergia" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Importación -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Importar Registros</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="importForm" method="POST" action="{{ route('import') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="file" class="form-label">Seleccionar Archivo</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.csv,.pdf"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Importar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Resultados -->
        <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Encabezado del Modal -->
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="resultModalLabel">
                            <i class="bi bi-check-circle me-2"></i> Resultado de la Importación
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <!-- Cuerpo del Modal -->
                    <div class="modal-body">
                        @if (session('success'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                        </div>
                        @endif
                        @if (session('error'))
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ session('error') }}
                        </div>
                        @endif
                        @if (session('omitted') > 0)
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            Se omitieron {{ session('omitted') }} registros ya existentes.
                        </div>
                        @endif
                        @if (session('new_records') > 0)
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Se agregaron {{ session('new_records') }} nuevos registros.
                        </div>
                        @endif
                    </div>
                    <!-- Pie de página del Modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ENjdO4Dr2bkBIFxQpeo1pM3VPW4yA2I1tj1lRx8Vjhskg77SHn3vwjL1bMyRy7l9" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="{{ asset('js/iot_scripts.js') }}"></script>
        <script src="{{ asset('js/graficos_scripts.js') }}"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var statusMessage = "{!! session('status') !!}";
            var statusType = "{{ session('status_type') }}"; // Añadimos el tipo de estado

            if (statusMessage) {
                var modal = document.getElementById('statusModal');
                var modalBody = modal.querySelector('.modal-body');

                // Limpiar cualquier mensaje previo
                modalBody.innerHTML = '';

                var messageParagraph = document.createElement('p');
                messageParagraph.innerHTML = statusMessage; // Usar innerHTML para interpretar HTML
                modalBody.appendChild(messageParagraph);

                // Añadir la clase de CSS según el estado
                modal.classList.remove('modal-success', 'modal-warning', 'modal-orange',
                    'modal-error'); // Eliminar clases previas
                if (statusType === 'success') {
                    modal.classList.add('modal-aviso-success');
                } else if (statusType === 'warning') {
                    modal.classList.add('modal-aviso-warning');
                } else if (statusType === 'orange') {
                    modal.classList.add('modal-aviso-orange');
                } else if (statusType === 'error') {
                    modal.classList.add('modal-aviso-error');
                }

                var statusModal = new bootstrap.Modal(modal);
                statusModal.show();
            }
        });
        </script>
        <!-- Incluir el siguiente script al final de la vista -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successMessage = '{{ session("success") }}';
            var errorMessage = '{{ session("error") }}';
            var omittedCount = '{{ session("omitted") }}';
            var newRecordsCount = '{{ session("new_records") }}';

            if (successMessage || errorMessage || omittedCount || newRecordsCount) {
                var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
                resultModal.show();
            }
        });
        </script>
</body>

</html>