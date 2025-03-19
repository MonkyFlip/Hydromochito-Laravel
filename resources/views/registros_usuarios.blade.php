<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/registros_usuarios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar_usuarios.css') }}">
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <!-- Logotipo y nombre -->
        <a class="navbar-brand" href="{{ asset('image/Logo-sin-fondo.png') }}">
            <img src="{{ asset('image/Logo-sin-fondo.png') }}" alt="Usuarios" width="60" height="60"
                class="d-inline-block align-top">
            Gestión Usuarios
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
        <form class="d-flex" action="{{ route('registros_usuarios.index') }}" method="GET">
            <input class="form-control me-2" type="search" name="query" placeholder="Buscar Usuario" aria-label="Buscar"
                value="{{ request('query') }}">
            <button class="btn btn-outline-success me-2" type="submit">
                <i class="bi bi-search"></i>
            </button>
            <a href="{{ route('registros_usuarios.index') }}" class="btn btn-outline-danger" id="clear-filter">
                <i class="bi bi-x-circle"></i>
            </a>
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
                        <i class="bi bi-file-earmark-text"></i> Reportes Usuarios
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownReportes">
                        <!-- Opción para importar registros -->
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="bi bi-upload"></i> Importar Usuarios
                            </a>
                        </li>
                        <!-- Opciones para exportar -->
                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-download"></i> Exportar
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('reportes.usuarios.excel') }}">
                                        <i class="bi bi-file-earmark-excel"></i> Exportar Excel (Reporte Completo)
                                    </a>
                                </li>
                                <li>
                                    <form id="exportExcelForm" action="{{ route('reporte.usuarios.excel') }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="datos" id="excelDatos"
                                            value="{{ json_encode($usuarios->items()) }}">
                                        <input type="hidden" name="columnas" id="excelColumnas"
                                            value="{{ json_encode(['ID Usuario', 'Nombre', 'Email', 'Rol']) }}">
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-file-earmark-excel"></i> Exportar Excel (Reporte Actual)
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('reporte.usuarios.previsualizar-pdf') }}"
                                        target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i> Previsualizar PDF (Reporte Completo)
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('reporte.usuarios.previsualizar-actual-pdf') }}"
                                        method="POST" target="_blank">
                                        @csrf
                                        <input type="hidden" name="datos" id="previsualizarPdfDatos"
                                            value="{{ json_encode($usuarios->items()) }}">
                                        <input type="hidden" name="columnas" id="previsualizarPdfColumnas"
                                            value="{{ json_encode($columnas) }}">
                                        <button type="submit" class="dropdown-item">
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
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#rolesChartModal">
                                <i class="bi bi-person"></i> Gráficos Usuarios
                            </a>
                        </li>
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

    <div class="container mt-2">
        <!-- Botón para abrir el modal -->
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
            data-bs-target="#agregarRegistroModal">
            <i class="bi bi-plus-circle"></i> Registrar Usuario
        </button>

        <!-- Tabla de usuarios -->
        <div class="table-responsive table-container">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se iteran los datos de usuarios -->
                    @if($usuarios->count() > 0)
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id_usuario }}</td>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            @if($usuario->id_rol == 1)
                            Administrador
                            @else
                            Usuario Normal
                            @endif
                        </td>
                        <td>
                            <!-- Botones de acción -->
                            <a href="#" class="btn btn-primary btn-sm btn-action" data-bs-toggle="modal"
                                data-bs-target="#verRegistroModal">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="#" class="btn btn-warning btn-sm btn-action" data-bs-toggle="modal"
                                data-bs-target="#editModal-{{ $usuario->id_usuario }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-danger btn-sm btn-action" data-bs-toggle="modal"
                                data-bs-target="#eliminarRegistroModal-{{ $usuario->id_usuario }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal para Editar Usuario -->
                    <div class="modal fade" id="editModal-{{ $usuario->id_usuario }}" tabindex="-1"
                        aria-labelledby="editModalLabel-{{ $usuario->id_usuario }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <!-- Encabezado del modal -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel-{{ $usuario->id_usuario }}">Editar
                                        Usuario</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <!-- Cuerpo del modal -->
                                <div class="modal-body">
                                    <form id="formEditarUsuario-{{ $usuario->id_usuario }}"
                                        action="{{ route('registros_usuarios.update', $usuario->id_usuario) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Campo de Nombre -->
                                        <div class="mb-3">
                                            <label for="nombre-{{ $usuario->id_usuario }}"
                                                class="form-label">Nombre</label>
                                            <input type="text" name="nombre" id="nombre-{{ $usuario->id_usuario }}"
                                                class="form-control" placeholder="Ingrese el nombre completo" required
                                                pattern="^[a-zA-Z ]+$" maxlength="255"
                                                title="El nombre solo debe contener letras y espacios."
                                                value="{{ $usuario->nombre }}">
                                        </div>
                                        <!-- Campo de Email -->
                                        <div class="mb-3">
                                            <label for="email-{{ $usuario->id_usuario }}" class="form-label">Correo
                                                Electrónico</label>
                                            <input type="email" name="email" id="email-{{ $usuario->id_usuario }}"
                                                class="form-control" placeholder="Ingrese el correo electrónico"
                                                required pattern="^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,8}$"
                                                title="Ingrese un correo electrónico válido."
                                                value="{{ $usuario->email }}">
                                        </div>
                                        <!-- Campo de Contraseña -->
                                        <div class="mb-3">
                                            <label for="password-{{ $usuario->id_usuario }}"
                                                class="form-label">Contraseña (Opcional)</label>
                                            <input type="password" name="password"
                                                id="password-{{ $usuario->id_usuario }}" class="form-control"
                                                placeholder="Ingrese una nueva contraseña (si desea cambiarla)"
                                                minlength="8"
                                                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*#?&]).{8,}"
                                                title="La contraseña debe tener al menos una letra minúscula, una letra mayúscula, un número y un carácter especial (@$!%*#?&).">
                                            <small class="text-muted">Deje este campo vacío si no desea cambiar la
                                                contraseña.</small>
                                        </div>
                                        <!-- Campo de Rol -->
                                        <div class="mb-3">
                                            <label for="id_rol-{{ $usuario->id_usuario }}"
                                                class="form-label">Rol</label>
                                            <select name="id_rol" id="id_rol-{{ $usuario->id_usuario }}"
                                                class="form-control" required>
                                                <option value="1" {{ $usuario->id_rol == 1 ? 'selected' : '' }}>
                                                    Administrador</option>
                                                <option value="2" {{ $usuario->id_rol == 2 ? 'selected' : '' }}>Usuario
                                                    Normal</option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <!-- Pie del modal -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary"
                                        form="formEditarUsuario-{{ $usuario->id_usuario }}">Guardar Cambios</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para Ver Usuario -->
                    <div class="modal fade" id="verRegistroModal" tabindex="-1" aria-labelledby="verRegistroModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <!-- Encabezado del modal -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="verRegistroModalLabel">Detalles del Usuario</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <!-- Cuerpo del modal -->
                                <div class="modal-body">
                                    <div class="container">
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>ID Usuario:</strong></div>
                                            <div class="col-8"><span>{{ $usuario->id_usuario }}</span></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>Nombre:</strong></div>
                                            <div class="col-8"><span>{{ $usuario->nombre }}</span></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>Email:</strong></div>
                                            <div class="col-8"><span>{{ $usuario->email }}</span></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>Rol:</strong></div>
                                            <div class="col-8">
                                                <span>
                                                    @if($usuario->id_rol == 1)
                                                    Administrador
                                                    @else
                                                    Usuario Normal
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pie del modal -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Eliminar Usuario -->
                    <div class="modal fade" id="eliminarRegistroModal-{{ $usuario->id_usuario }}" tabindex="-1"
                        aria-labelledby="eliminarRegistroModalLabel-{{ $usuario->id_usuario }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Encabezado -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="eliminarRegistroModalLabel-{{ $usuario->id_usuario }}">
                                        Eliminar Usuario
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <!-- Cuerpo -->
                                <div class="modal-body">
                                    <p><strong>ID Usuario:</strong> <span
                                            id="deleteIdUsuario">{{ $usuario->id_usuario }}</span></p>
                                    <p><strong>Nombre:</strong> <span
                                            id="deleteNombreUsuario">{{ $usuario->nombre }}</span></p>
                                    <p><strong>Email:</strong> <span
                                            id="deleteEmailUsuario">{{ $usuario->email }}</span></p>
                                    <p><strong>Rol:</strong> <span id="deleteRolUsuario">
                                            @if($usuario->id_rol == 1)
                                            Administrador
                                            @else
                                            Usuario Normal
                                            @endif
                                        </span></p>
                                    <p class="text-danger">¿Estás seguro de que deseas eliminar este usuario? Esta
                                        acción no se puede
                                        deshacer.</p>
                                </div>
                                <!-- Pie -->
                                <div class="modal-footer">
                                    <form id="deleteForm-{{ $usuario->id_usuario }}" method="post"
                                        action="{{ route('registros_usuarios.destroy', $usuario->id_usuario) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                    @else
                    <tr>
                        <td colspan="5" class="text-center">No hay registros de usuarios.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <!-- Paginación -->
        <div class="d-flex justify-content-center pagination-container">
            {{ $usuarios->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5', ['pageName' => 'page_usuarios']) }}
        </div>
    </div>

    <!-- Modal para Registrar Usuario -->
    <div class="modal fade" id="agregarRegistroModal" tabindex="-1" aria-labelledby="agregarRegistroModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Encabezado del modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarRegistroModalLabel">Registrar Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Cuerpo del modal -->
                <div class="modal-body">
                    <form id="formRegistroUsuario" action="{{ route('registros_usuarios.store') }}" method="POST">
                        @csrf
                        <!-- Campo de Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control"
                                placeholder="Ingrese el nombre completo" required pattern="^[a-zA-Z ]+$" maxlength="255"
                                title="El nombre solo debe contener letras y espacios.">
                            <small class="form-text text-muted">El nombre debe contener únicamente letras y espacios.
                                Máximo 255 caracteres.</small>
                        </div>
                        <!-- Campo de Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="Ingrese el correo electrónico" required
                                pattern="^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,8}$"
                                title="Ingrese un correo electrónico válido.">
                            <small class="form-text text-muted">Debe ingresar un correo electrónico válido (por ejemplo:
                                usuario@dominio.com).</small>
                        </div>
                        <!-- Campo de Contraseña -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Ingrese una contraseña segura" required minlength="8"
                                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*#?&]).{8,}"
                                title="La contraseña debe tener al menos una letra minúscula, una letra mayúscula, un número y un carácter especial (@$!%*#?&).">
                            <small class="form-text text-muted">Debe contener al menos una letra minúscula, una
                                mayúscula, un número y un carácter especial (@$!%*#?&). Longitud mínima: 8
                                caracteres.</small>
                        </div>
                        <!-- Campo de Rol -->
                        <div class="mb-3">
                            <label for="id_rol" class="form-label">Rol</label>
                            <select name="id_rol" id="id_rol" class="form-control" required>
                                <option value="1">Administrador</option>
                                <option value="2">Usuario Normal</option>
                            </select>
                            <small class="form-text text-muted">Seleccione el rol del usuario.</small>
                        </div>
                    </form>
                </div>
                <!-- Pie del modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" form="formRegistroUsuario">Registrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para el Gráfico -->
    <div class="modal fade roles-chart-modal" id="rolesChartModal" tabindex="-1" aria-labelledby="rolesChartModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rolesChartModalLabel">Gráfico de Roles de Usuarios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <canvas id="rolesChart" width="400" height="200"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
                    <form id="importForm" method="POST" action="{{ route('import') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Seleccionar Archivo</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.csv" required>
                            <div class="form-text">Solo se aceptan archivos .xlsx o .csv (Máx: 2 MB).</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Importar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Aviso -->
    <div class="modal fade" id="importResultModal" tabindex="-1" aria-labelledby="importResultModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importResultModalLabel">Resultados de la Importación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if (session('new_records') || session('omitted'))
                    <ul class="list-group mt-3">
                        <li class="list-group-item"><strong>Importados:</strong> {{ session('new_records', 0) }}</li>
                        <li class="list-group-item"><strong>Omitidos:</strong> {{ session('omitted', 0) }}</li>
                    </ul>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap 5.3.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/registros_usuarios.js') }}"></script>
    <script>
    var importDone = "{{ session('import_done', false) }}";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/graficos_scripts.js') }}"></script>
</body>

</html>