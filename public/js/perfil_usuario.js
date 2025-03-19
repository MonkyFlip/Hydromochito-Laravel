//Script para Eliminar un Registro
document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".btn-action"); // Botones de eliminar
    const deleteForm = document.getElementById("deleteForm"); // Formulario en el modal
    const modalEliminarRegistro = document.getElementById("modalEliminarRegistro"); // Modal

    deleteButtons.forEach(button => {
        button.addEventListener("click", function () {
            const idRegistro = button.getAttribute("data-id"); // Obtener el ID del atributo data-id
            const actionUrl = `/perfil_usuario/registros/${idRegistro}`; // Generar URL dinámica

            deleteForm.setAttribute("action", actionUrl); // Actualizar la acción del formulario
        });
    });
});

//Script para Reportes PDF
function preparePerfilUsuarioData() {
    // Inicializa los arrays para datos y columnas
    const datos = [];
    const columnas = [];

    // Captura los encabezados de la tabla
    const encabezados = document.querySelectorAll("#tabla-perfil-usuario thead th");
    encabezados.forEach((encabezado) => {
        const texto = encabezado.textContent.trim().toLowerCase();
        if (texto !== "acciones") { // Ignora la columna "Acciones"
            columnas.push(encabezado.textContent.trim());
        }
    });

    // Captura las filas de la tabla
    const filas = document.querySelectorAll("#tabla-perfil-usuario tbody tr");
    filas.forEach((fila) => {
        const filaDatos = [];
        fila.querySelectorAll("td").forEach((celda, index) => {
            if (columnas[index] !== "Acciones") { // Ignora la columna "Acciones"
                filaDatos.push(celda.textContent.trim());
            }
        });
        datos.push(filaDatos);
    });

    // Asigna los datos al formulario oculto
    document.querySelector("#perfilUsuarioDatos").value = JSON.stringify(datos);
    document.querySelector("#perfilUsuarioColumnas").value = JSON.stringify(columnas);
}

document.addEventListener("DOMContentLoaded", function () {
    // Referencia al botón para abrir el modal
    const btnGraficos = document.querySelector(".btn-graficos");

    // Referencias a los inputs de rango y al botón de actualización
    const rangeStartInput = document.getElementById("rangeStart");
    const rangeEndInput = document.getElementById("rangeEnd");
    const updateChartButton = document.getElementById("updateChartButton");

    let registrosChartInstance; // Para mantener la instancia del gráfico

    btnGraficos.addEventListener("click", function () {
        // Extraer todos los datos de la tabla al abrir el modal
        const rows = document.querySelectorAll("#tabla-perfil-usuario tbody tr");
        const allData = {
            labels: [],
            flujoAgua: [],
            nivelAgua: [],
            temperatura: [],
        };

        rows.forEach((row, index) => {
            const cells = row.querySelectorAll("td");
            allData.labels.push(`Registro ${index + 1}`); // Etiqueta de registro
            allData.flujoAgua.push(parseFloat(cells[1].textContent.trim())); // Flujo de Agua
            allData.nivelAgua.push(parseFloat(cells[2].textContent.trim())); // Nivel de Agua
            allData.temperatura.push(parseFloat(cells[3].textContent.trim())); // Temperatura
        });

        // Actualiza el gráfico según el rango seleccionado
        updateChartButton.addEventListener("click", function () {
            // Obtener los valores de rango
            const rangeStart = parseInt(rangeStartInput.value, 10) - 1 || 0; // Ajustar a índice
            const rangeEnd = parseInt(rangeEndInput.value, 10) || allData.labels.length;

            // Filtrar los datos según el rango
            const filteredData = {
                labels: allData.labels.slice(rangeStart, rangeEnd),
                flujoAgua: allData.flujoAgua.slice(rangeStart, rangeEnd),
                nivelAgua: allData.nivelAgua.slice(rangeStart, rangeEnd),
                temperatura: allData.temperatura.slice(rangeStart, rangeEnd),
            };

            // Crear o actualizar el gráfico
            const ctx = document.getElementById("registrosChart").getContext("2d");
            if (registrosChartInstance) {
                registrosChartInstance.destroy(); // Destruir gráfico previo
            }

            registrosChartInstance = new Chart(ctx, {
                type: "bar", // Tipo de gráfico
                data: {
                    labels: filteredData.labels,
                    datasets: [
                        {
                            label: "Flujo de Agua (L/s)",
                            data: filteredData.flujoAgua,
                            backgroundColor: "#409C8C", // Niagara 500
                            borderColor: "#2D7468", // Niagara 600
                            borderWidth: 2,
                        },
                        {
                            label: "Nivel de Agua (m)",
                            data: filteredData.nivelAgua,
                            backgroundColor: "#80C8B8", // Niagara 300
                            borderColor: "#409C8B8", // Niagara 500
                            borderWidth: 2,
                        },
                        {
                            label: "Temperatura (°C)",
                            data: filteredData.temperatura,
                            backgroundColor: "#AFEDD3", // Niagara 200
                            borderColor: "#80C8B8", // Niagara 300
                            borderWidth: 2,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: "top",
                            labels: {
                                color: "#0E2523", // Niagara 950
                                font: { size: 14 },
                            },
                        },
                    },
                    scales: {
                        x: {
                            ticks: { color: "#0E2523" },
                            grid: { color: "#AFEDD3" },
                        },
                        y: {
                            ticks: { color: "#0E2523" },
                            grid: { color: "#AFEDD3" },
                        },
                    },
                },
            });
        });
    });
});
