// Grafios: Registros IoT
document.addEventListener('DOMContentLoaded', function () {
    // Función para crear gráficos
    function crearGrafico(id, label, dataset, backgroundColor, borderColor) {
        var ctx = document.getElementById(id).getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dataset.labels,
                datasets: [{
                    label: label,
                    data: dataset.data,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1 // Ajusta el intervalo según lo que miden los datos
                        }
                    }
                }
            }
        });
    }

    // Obtener datos de los registros y agrupar en intervalos
    fetch('http://localhost:3000/api/registros_iot/')
        .then(response => response.json())
        .then(data => {
            // Función para agrupar datos en intervalos
            function agruparDatos(datos, intervalo) {
                return datos.reduce((acc, valor) => {
                    if (valor >= 5 && valor < 10) acc[0]++;
                    else if (valor >= 10 && valor < 20) acc[1]++;
                    else if (valor >= 20 && valor < 40) acc[2]++;
                    else if (valor >= 40) acc[3]++;
                    return acc;
                }, [0, 0, 0, 0]);
            }

            var flujo_agua = agruparDatos(data.map(registro => registro.flujo_agua));
            var nivel_agua = agruparDatos(data.map(registro => registro.nivel_agua));
            var temp = agruparDatos(data.map(registro => registro.temp));

            // Agrupar datos de energía por tipo
            var energia = data.reduce((acc, registro) => {
                if (registro.energia === 'solar') acc[0]++;
                else if (registro.energia === 'electricidad') acc[1]++;
                return acc;
            }, [0, 0]);
            var labelsEnergia = ['Solar', 'Electricidad'];

            var labels = ['5-10', '10-20', '20-40', '40+'];

            crearGrafico('graficoFlujoAgua', 'Flujo de Agua', { labels: labels, data: flujo_agua }, 'rgba(75, 192, 192, 0.2)', 'rgba(75, 192, 192, 1)');
            crearGrafico('graficoNivelAgua', 'Nivel de Agua', { labels: labels, data: nivel_agua }, 'rgba(153, 102, 255, 0.2)', 'rgba(153, 102, 255, 1)');
            crearGrafico('graficoTemp', 'Temperatura', { labels: labels, data: temp }, 'rgba(255, 159, 64, 0.2)', 'rgba(255, 159, 64, 1)');
            crearGrafico('graficoEnergia', 'Energía', { labels: labelsEnergia, data: energia }, 'rgba(255, 99, 132, 0.2)', 'rgba(255, 99, 132, 1)');
        })
        .catch(error => console.error('Error al obtener los datos de los registros:', error));
});


// Gráficos: Roles de Usuarios
document.addEventListener('DOMContentLoaded', function () {
    // Función para crear gráficos
    function crearGrafico(id, label, dataset, backgroundColor, borderColor) {
        const ctx = document.getElementById(id).getContext('2d');
        new Chart(ctx, {
            type: 'pie', // Cambia a 'bar' si prefieres un gráfico de barras
            data: {
                labels: dataset.labels,
                datasets: [{
                    label: label,
                    data: dataset.data,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.label + ': ' + context.raw + ' usuarios';
                            }
                        }
                    }
                }
            }
        });
    }

    // Obtener datos de los roles de usuarios desde la API
    fetch('http://localhost:3000/api/registros_usuarios/') // Enlace a tu API
        .then(response => response.json())
        .then(data => {
            // Procesar los datos de la API
            const adminCount = data.filter(usuario => usuario.id_rol === 1).length;
            const userCount = data.filter(usuario => usuario.id_rol === 2).length;

            // Configurar las etiquetas y los datos
            const labels = ['Administrador', 'Usuario Normal'];
            const dataset = {
                labels: labels,
                data: [adminCount, userCount]
            };

            // Crear el gráfico con colores personalizados
            crearGrafico(
                'rolesChart', // ID del canvas
                'Distribución de Roles de Usuarios',
                dataset,
                ['rgba(213, 163, 115, 0.6)', 'rgba(143, 94, 61, 0.6)'], // Colores para los roles (Dorado cálido y Café cálido)
                ['rgba(213, 163, 115, 1)', 'rgba(143, 94, 61, 1)'] // Bordes para los roles (Más vibrante)
            );
        })
        .catch(error => console.error('Error al cargar los datos desde la API:', error));
});
