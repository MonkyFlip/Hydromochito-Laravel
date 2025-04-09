<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hydromochito - Sistema de Gestión</title>
    <link rel="stylesheet" href="{{ asset('css/visitantes.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css"> <!-- Animaciones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Iconos FontAwesome -->
</head>

<body>
    <!-- Menú de Navegación -->
    <nav class="menu">
        <div class="logo">
            <a href="#header" style="color: #FFFFFF; text-decoration: none; font-weight: bold;">Hydromochito</a>
        </div>
        <button class="menu-toggle" onclick="toggleDropdown()"><i class="fas fa-bars"></i></button>
        <ul class="menu-list">
            <li><a href="#header"><i class="fas fa-arrow-up"></i> Inicio</a></li>
            <li><a href="#presentacion"><i class="fas fa-info-circle"></i> Presentación</a></li>
            <li><a href="#representacion"><i class="fas fa-list-alt"></i> Registros</a></li>
            <li><a href="#reportes"><i class="fas fa-file-alt"></i> Reportes</a></li>
            <li><a href="#graficos"><i class="fas fa-chart-pie"></i> Gráficos</a></li>
            @if(Session::has('user_email'))
            <li><a href="{{ route('perfil_usuario.index') }}"><i class="fas fa-user"></i> Perfil Usuario</a></li>
            @endif
            <li><a href="/login"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a></li>
        </ul>

        <!-- Menú desplegable -->
        <div class="menu-dropdown" id="menuDropdown">
            <a href="#header"><i class="fas fa-arrow-up"></i> Inicio</a>
            <a href="#presentacion"><i class="fas fa-info-circle"></i> Presentación</a>
            <a href="#representacion"><i class="fas fa-list-alt"></i> Registros</a>
            <a href="#reportes"><i class="fas fa-file-alt"></i> Reportes</a>
            <a href="#graficos"><i class="fas fa-chart-pie"></i> Gráficos</a>
            @if(Session::has('user_email'))
            <a href="{{ route('perfil_usuario.index') }}"><i class="fas fa-user"></i> Perfil Usuario</a>
            @endif
            <a href="/login"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a>
        </div>
    </nav>

    <header id="header" class="header">
        <h1>Hydromochito</h1>
        <p>Un sistema diseñado para simplificar la gestión de registros, la generación de reportes y la visualización de
            datos clave, optimizando procesos de forma precisa y eficiente.</p>
    </header>

    <main>
        <!-- Sección 1: Presentación -->
        <section id="presentacion" class="section section-presentacion">
            <div class="contenedor presentacion-contenedor">
                <!-- Texto a la Izquierda -->
                <div class="texto-presentacion info" data-aos="fade-right">
                    <h2>¿Qué es Hydromochito?</h2>
                    <p>
                        Hydromochito es un sistema avanzado diseñado para monitorear y gestionar recursos hídricos y
                        energéticos de manera eficiente e intuitiva, permitiendo medir indicadores clave como el flujo,
                        nivel y temperatura del agua, así como el tipo de energía utilizada, ya sea solar o eléctrica.
                        Con una interfaz interactiva, genera reportes automatizados, visualiza datos en tiempo real y
                        ofrece herramientas para optimizar procesos, reducir costos y promover la sostenibilidad.
                        Además, incluye un robusto apartado de gestión de usuarios, asegurando un control personalizado
                        y seguro, convirtiéndolo en la solución ideal para empresas comprometidas con la innovación y el
                        uso responsable de sus recursos.
                    </p>
                </div>
                <!-- Imagen a la Derecha -->
                <div class="imagen-presentacion representacion" data-aos="fade-left">
                    <img src="/image/Presentacion.png" alt="Presentación del sistema">
                </div>
            </div>
        </section>

        <!-- Sección 2: Representación -->
        <section id="representacion" class="section section-representacion">
            <div class="contenedor representacion-contenedor">
                <!-- Carrusel de Imágenes a la Izquierda -->
                <div class="imagen-carrusel representacion-imagen" data-aos="fade-left">
                    <div id="carruselRepresentacion" class="carousel slide" data-bs-ride="carousel"
                        data-bs-interval="3000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="image/Registros-Iot.png" class="d-block w-100" alt="Registro IoT">
                            </div>
                            <div class="carousel-item">
                                <img src="image/Registros-Usuarios.png" class="d-block w-100"
                                    alt="Registro de Usuarios">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Texto a la Derecha -->
                <div class="texto representacion-texto" data-aos="fade-right">
                    <h2>Vista de Registros</h2>
                    <p>
                        Accede a una lista clara y organizada de registros de tus usuarios o de Hydromochito, con
                        información clave como nombre, email, roles, flujo, nivel de agua y tipo de energía usado.
                        Diseñado para brindarte una experiencia eficiente, incluye opciones avanzadas para filtrar y
                        encontrar datos fácilmente, garantizando un control seguro y óptimo de tus recursos.
                    </p>
                </div>
            </div>
        </section>

        <!-- Sección 3: Generación de Reportes -->
        <section id="reportes" class="section section-reportes">
            <div class="contenedor contenedor-reportes">
                <!-- Información sobre Reportes -->
                <div class="info info-reportes" data-aos="fade-right">
                    <h2>Generación de Reportes</h2>
                    <p>Exporta tus registros en formatos PDF y Excel con un solo clic, transformando tus datos en
                        reportes claros, profesionales y listos para compartir. Hydromochito organiza automáticamente la
                        información, facilitando el análisis y la toma de decisiones con reportes estructurados que se
                        adaptan a las necesidades de tu negocio. Simplifica tus procesos y ahorra tiempo con esta
                        herramienta eficiente y accesible.</p>
                </div>

                <!-- Carrusel sobre Reportes -->
                <div class="imagen-carrusel-reportes representacion" data-aos="fade-left">
                    <div id="carruselReportes" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="image/Reporte-Iot.png" class="d-block w-100" alt="Reporte IoT">
                            </div>
                            <div class="carousel-item">
                                <img src="image/Reporte-Usuarios.png" class="d-block w-100" alt="Reporte Usuarios">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección 4: Visualización de Gráficos -->
        <section id="graficos" class="section section-graficos">
            <div class="contenedor-graficos">
                <!-- Carrusel de Imágenes a la Izquierda -->
                <div id="graficoCarrusel" class="carousel slide graficos-carrusel" data-bs-ride="carousel"
                    data-bs-interval="3000">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="image/Graficos-Usuarios.png" class="d-block w-50" alt="Gráfico Usuarios">
                        </div>
                        <div class="carousel-item">
                            <img src="image/Graficos-Hydromochito.png" class="d-block w-100" alt="Gráfico IoT">
                        </div>
                    </div>
                </div>
                <!-- Descripción de los Gráficos a la Derecha -->
                <div class="info-graficos" data-aos="fade-right">
                    <h2>Descripción de Gráficos</h2>
                    <p>Explora visualizaciones claras y detalladas que presentan las métricas clave gestionadas por
                        Hydromochito, diseñadas para facilitar el análisis y la toma de decisiones:</p>
                    <ul>
                        <li><strong>Distribución de Roles:</strong> Un gráfico que ilustra la proporción entre usuarios
                            Administradores y Normales, permitiendo una visión precisa de la estructura de usuarios.
                        </li>
                        <li><strong>Flujo de Agua:</strong> Representación visual del caudal en litros por minuto, ideal
                            para monitorear y optimizar el uso del agua en tiempo real.</li>
                    </ul>
                    <p>Estas imágenes reflejan la precisión y funcionalidad que Hydromochito aporta a la gestión de
                        recursos, haciendo que los datos sean más accesibles y comprensibles.</p>
                </div>
            </div>
        </section>
    </main>

    <footer style="text-align: center; padding: 10px; background-color: #F5F5F5; color: #5D4037;">
        <p>&copy; 2025 Hydromochito. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    AOS.init(); // Inicializar AOS

    // Mostrar y ocultar el menú desplegable con animación
    function toggleDropdown() {
        const menuDropdown = document.getElementById('menuDropdown');
        menuDropdown.classList.toggle('active');
    }

    // Cerrar el menú al hacer clic fuera de él
    document.addEventListener('click', function(event) {
        const menuDropdown = document.getElementById('menuDropdown');
        const menuToggle = document.querySelector('.menu-toggle');
        if (!menuDropdown.contains(event.target) && event.target !== menuToggle) {
            menuDropdown.classList.remove('active');
        }
    });

    // Smooth scrolling para las secciones
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
    </script>
</body>

</html>
