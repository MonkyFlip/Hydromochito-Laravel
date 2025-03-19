<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Hydromochito</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <video autoplay muted loop id="bg-video">
        <source src="image/Background-2k.webm" type="video/mp4">
        Tu navegador no soporta videos en HTML5.
    </video>

    <div id="error-data" style="display:none;">{{ json_encode($errors->all()) }}</div>
    <div class="login-container">
        <!-- Logo en un círculo -->
        <div class="logo-circle">
            <img src="image/Logo-sin-fondo.png" alt="Hydromochito Logo">
        </div>
        <!-- Título y Botones -->
        <h1>Bienvenido a Hydromochito</h1>
        <div class="button-container">
            <button class="btn btn-primary" data-toggle="modal" data-target="#loginModal">Iniciar Sesión</button>
            <button class="btn btn-secondary" data-toggle="modal" data-target="#registerModal">Registrar</button>
            <a href="#" class="recover-password" data-toggle="modal" data-target="#recoverPasswordModal">Recuperar
                Contraseña</a>
            <!-- Botón de Regresar -->
            <a href="{{ route('visitantes.index') }}" class="btn btn-light mt-3">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>
    </div>

    <!-- Modal Iniciar Sesión -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="loginModal">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="loginEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="loginPassword" name="password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" form="loginForm">Iniciar Sesión</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="registerModal">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Registrar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="registerForm" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="registerName" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="registerName" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="registerEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="registerPassword" name="password" required>
                            <div class="progress mt-2">
                                <div id="passwordStrengthBar" class="progress-bar" role="progressbar" style="width: 0%;"
                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small id="passwordHelp" class="form-text text-muted">La contraseña debe tener al menos 1
                                letra mayúscula, 1 letra minúscula, 1 número, 1 carácter especial y una longitud mínima
                                de 8 caracteres.</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" form="registerForm">Registrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Recuperar Contraseña -->
    <div class="modal fade" id="recoverPasswordModal" tabindex="-1" aria-labelledby="recoverPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="recoverPasswordModal">
                <div class="modal-header">
                    <h5 class="modal-title" id="recoverPasswordModalLabel">Recuperar Contraseña</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="recoverPasswordForm" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="recoverEmail" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="recoverEmail" name="email" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" form="recoverPasswordForm">Recuperar
                        Contraseña</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Error -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="errorModal">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="error-messages">
                        <!-- Mensajes de error dinámicos -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>