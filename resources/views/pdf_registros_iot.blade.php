<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Registros IoT</title>
    <style>
    /* General Styles */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        color: #3E456A;
        /* Azul salvaje 900 */
        line-height: 1.6;
        background-color: #F4F6FA;
        /* Azul salvaje 50 */
    }

    /* Header */
    header {
        background-color: #495284;
        /* Azul salvaje 800 */
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
        border: 1px solid #D2DBEB;
        /* Azul salvaje 200 */
        text-align: left;
        padding: 10px;
    }

    .table th {
        background-color: #5461A1;
        /* Azul salvaje 700 */
        color: #FFFFFF;
        /* Blanco puro */
        text-transform: uppercase;
        font-size: 14px;
    }

    .table tbody tr:nth-child(odd) {
        background-color: #E5EAF4;
        /* Azul salvaje 100 */
    }

    .table tbody tr:nth-child(even) {
        background-color: #FFFFFF;
        /* Blanco puro */
    }

    .table tbody tr:hover {
        background-color: #D2DBEB;
        /* Azul salvaje 200 */
    }

    /* Footer */
    footer {
        background-color: #495284;
        /* Azul salvaje 800 */
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
        <span style="font-size: 16px; font-style: italic;">Reporte de Registros IoT</span>
    </header>


    <!-- Main Content -->
    <main>
        <h2 style="text-align: center; margin: 10px 0 10px 0; font-size: 22px; font-weight: 500;">Reporte de Registros
            IoT</h2>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        @foreach($columnas as $columna)
                        <th>{{ $columna }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($registros as $registro)
                    <tr>
                        <td>{{ $registro['id_registro'] ?? 'N/A' }}</td>
                        <td>{{ $registro['flujo_agua'] ?? 'N/A' }}</td>
                        <td>{{ $registro['nivel_agua'] ?? 'N/A' }}</td>
                        <td>{{ $registro['temp'] ?? 'N/A' }}</td>
                        <td>{{ $registro['energia'] ?? 'N/A' }}</td>
                        <td>{{ $registro['nombre_usuario'] ?? 'N/A' }}</td>
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