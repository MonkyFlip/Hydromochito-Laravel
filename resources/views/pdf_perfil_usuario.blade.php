<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Perfil de Usuario</title>
    <style>
    /* General Styles */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        color: #0E2523;
        /* Niagara 950 */
        line-height: 1.6;
        background-color: #F3FAF8;
        /* Niagara 50 */
    }

    /* Header */
    header {
        background-color: #409C8C;
        /* Niagara 500 */
        color: #FFFFFF;
        /* Blanco puro */
        padding: 20px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    header img {
        height: 50px;
        margin-right: 15px;
    }

    header h1 {
        font-size: 26px;
        margin: 0;
        display: flex;
        align-items: center;
    }

    header span {
        font-size: 16px;
        font-weight: 300;
        font-style: italic;
    }

    /* User Info */
    .user-info {
        margin: 15px 30px;
        font-size: 16px;
        color: #2D7468;
        /* Niagara 600 */
    }

    .user-info strong {
        font-weight: bold;
        color: #0E2523;
        /* Niagara 950 */
    }

    /* Table Styles */
    .table-container {
        padding: 20px 30px;
        margin: 0 auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        background: #FFFFFF;
        /* Blanco puro */
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .table th,
    .table td {
        border: 1px solid #D7F0E9;
        /* Niagara 100 */
        text-align: left;
        padding: 10px;
    }

    .table th {
        background-color: #2D7468;
        /* Niagara 600 */
        color: #FFFFFF;
        /* Blanco puro */
        text-transform: uppercase;
        font-size: 14px;
    }

    .table tbody tr:nth-child(odd) {
        background-color: #EAF6F4;
        /* Niagara 50 */
    }

    .table tbody tr:nth-child(even) {
        background-color: #FFFFFF;
        /* Blanco puro */
    }

    .table tbody tr:hover {
        background-color: #AFE0D3;
        /* Niagara 200 */
    }

    /* Footer */
    footer {
        background-color: #409C8C;
        /* Niagara 500 */
        color: #FFFFFF;
        /* Blanco puro */
        text-align: center;
        padding: 15px 30px;
        font-size: 14px;
        margin-top: 20px;
    }

    footer .date {
        font-weight: 500;
        margin-bottom: 5px;
    }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <h1 style="display: flex; align-items: center; gap: 10px; margin: 0;">
            <img src="{{ public_path('image/Logo-sin-fondo.png') }}" alt="Hydromochito Logo" style="height: 40px;">
            <span style="font-size: 26px; font-weight: 600;">Hydromochito</span>
        </h1>
        <span style="font-size: 16px; font-style: italic;">Reporte de Perfil de Usuario</span>
    </header>

    <!-- User Info -->
    <div class="user-info">
        Reporte elaborado por: {{ $nombreUsuario ?? 'Usuario no identificado' }}
    </div>

    <!-- Main Content -->
    <main>
        <h2 style="text-align: center; margin: 10px 0 10px 0; font-size: 22px; font-weight: 500;">Reporte de Registros
            de Hydromochito</h2>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        @foreach($columnas as $columna)
                        @if(strtolower($columna) !== 'id registro')
                        <!-- Ignorar ID Registro -->
                        <th>{{ $columna }}</th>
                        @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($registros as $registro)
                    <tr>
                        @if(isset($registro['flujo_agua']))
                        <td>{{ $registro['flujo_agua'] ?? 'N/A' }}</td>
                        @endif
                        @if(isset($registro['nivel_agua']))
                        <td>{{ $registro['nivel_agua'] ?? 'N/A' }}</td>
                        @endif
                        @if(isset($registro['temp']))
                        <td>{{ $registro['temp'] ?? 'N/A' }}</td>
                        @endif
                        @if(isset($registro['energia']))
                        <td>{{ $registro['energia'] ?? 'N/A' }}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="date">Fecha de generación: {{ date('d/m/Y') }}</div>
        Reporte Oficial de Hydromochito &copy; <span>&reg;</span>
    </footer>
</body>

</html>