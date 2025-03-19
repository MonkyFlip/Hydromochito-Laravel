// Script de Formularios Modales
document.addEventListener('DOMContentLoaded', function() {
    // Llenar datos en el modal Ver Registro
    document.querySelectorAll('.btn-primary[data-bs-target="#verRegistroModal"]').forEach(button => {
        button.addEventListener('click', function() {
            var registro = this.closest('tr');
            var idRegistro = registro.querySelector('td:nth-child(1)').innerText;
            var flujoAgua = registro.querySelector('td:nth-child(2)').innerText;
            var nivelAgua = registro.querySelector('td:nth-child(3)').innerText;
            var temp = registro.querySelector('td:nth-child(4)').innerText;
            var energia = registro.querySelector('td:nth-child(5)').innerText;
            var idUsuario = registro.querySelector('td:nth-child(6)').innerText;

            document.getElementById('verIdRegistro').innerText = idRegistro;
            document.getElementById('verFlujoAgua').innerText = flujoAgua;
            document.getElementById('verNivelAgua').innerText = nivelAgua;
            document.getElementById('verTemp').innerText = temp;
            document.getElementById('verEnergia').innerText = energia;
            document.getElementById('verIdUsuario').innerText = idUsuario;
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Llenar datos en el modal Editar Registro
        document.querySelectorAll('.btn-warning[data-bs-target^="#editModal"]').forEach(
            button => {
                button.addEventListener('click', function() {
                    var id = this.getAttribute('data-bs-target').replace(
                        '#editModal', '');
                    var modal = document.getElementById('editModal' + id);
                    var url = `/api/registros_iot/${id}`;
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            modal.querySelector('input[name="flujo_agua"]')
                                .value = data.flujo_agua;
                            modal.querySelector('input[name="nivel_agua"]')
                                .value = data.nivel_agua;
                            modal.querySelector('input[name="temp"]').value =
                                data.temp;
                            modal.querySelector('select[name="energia"]')
                                .value = data.energia;
                            modal.querySelector('input[name="nombre_usuario"]')
                                .value = data.nombre_usuario;
                            modal.querySelector('form').action =
                                `/registros_iot/${id}`;
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
    });

    // Llenar datos en el modal Eliminar Registro
    document.querySelectorAll('.btn-danger[data-bs-target="#eliminarRegistroModal"]').forEach(
        button => {
            button.addEventListener('click', function() {
                var registro = this.closest('tr');
                var idRegistro = registro.querySelector('td:nth-child(1)').innerText;
                var flujoAgua = registro.querySelector('td:nth-child(2)').innerText;
                var nivelAgua = registro.querySelector('td:nth-child(3)').innerText;
                var temp = registro.querySelector('td:nth-child(4)').innerText;
                var energia = registro.querySelector('td:nth-child(5)').innerText;
                var idUsuario = registro.querySelector('td:nth-child(6)').innerText;

                document.getElementById('deleteIdRegistro').innerText = idRegistro;
                document.getElementById('deleteFlujoAgua').innerText = flujoAgua;
                document.getElementById('deleteNivelAgua').innerText = nivelAgua;
                document.getElementById('deleteTemp').innerText = temp;
                document.getElementById('deleteEnergia').innerText = energia;
                document.getElementById('deleteIdUsuario').innerText = idUsuario;

                document.getElementById('deleteForm').action =
                    `{{ route('registros_iot.destroy', '') }}/${idRegistro}`;
            });
        });
});

// Script de Navbar
document.addEventListener('DOMContentLoaded', function() {
    var dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');

    dropdownSubmenus.forEach(function(submenu) {
        submenu.addEventListener('click', function(event) {
            event.stopPropagation(); // Evita que el evento se propague y cierre el menú
            submenu.classList.toggle(
            'show'); // Alterna la clase 'show' para abrir/cerrar el submenu
        });
    });

    // Cierra los submenús cuando se hace clic fuera del dropdown
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown-menu')) {
            dropdownSubmenus.forEach(function(submenu) {
                submenu.classList.remove('show');
            });
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Función para limpiar el filtro y redirigir a la página sin parámetros de búsqueda
    document.getElementById('clear-filter').addEventListener('click', function() {
        var form = this.closest('form');
        form.reset();
        window.location.href = form.action;
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('exportExcelButton').addEventListener('click', function() {
        let form = document.createElement('form');
        form.method = 'POST';
        form.target = '_blank';
        form.action = '{{ route("reportes.registros_iot_actual.excel") }}';
        let csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'data';
        input.value = JSON.stringify(data); // Asegúrate de que 'data' esté disponible y sea correcta.
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    });
});



//Reportes Actual de PDF y Excel
document.addEventListener('DOMContentLoaded', function () {
    /**
     * Recopila los registros visibles en la tabla.
     */
    function obtenerRegistrosVisibles() {
        const filas = document.querySelectorAll('.table tbody tr'); // Selecciona todas las filas del tbody
        const registros = [];

        filas.forEach(fila => {
            const columnas = fila.querySelectorAll('td');
            const registro = {
                id_registro: columnas[0]?.textContent.trim(),
                flujo_agua: columnas[1]?.textContent.trim(),
                nivel_agua: columnas[2]?.textContent.trim(),
                temp: columnas[3]?.textContent.trim(),
                energia: columnas[4]?.textContent.trim(),
                nombre_usuario: columnas[5]?.textContent.trim(),
            };
            registros.push(registro);
        });

        return registros;
    }

    /**
     * Prepara los datos para el botón "Previsualizar PDF".
     */
    function preparePrevisualizarPdfData() {
        const registros = obtenerRegistrosVisibles();
        const columnas = ['ID Registro', 'Flujo de Agua', 'Nivel de Agua', 'Temperatura', 'Energía', 'Usuario'];

        // Llenar campos ocultos del formulario PDF
        document.getElementById('previsualizarPdfDatos').value = JSON.stringify(registros);
        document.getElementById('previsualizarPdfColumnas').value = JSON.stringify(columnas);
    }

    /**
     * Prepara los datos para el botón "Exportar Excel".
     */
    function prepareExcelData() {
        const registros = obtenerRegistrosVisibles();
        const columnas = ['ID Registro', 'Flujo de Agua', 'Nivel de Agua', 'Temperatura', 'Energía', 'Usuario'];

        // Llenar campos ocultos del formulario Excel
        document.getElementById('excelDatos').value = JSON.stringify(registros);
        document.getElementById('excelColumnas').value = JSON.stringify(columnas);
    }

    // Asignar eventos a los botones de reporte actual
    document.querySelector('#previsualizarPdfForm button').addEventListener('click', preparePrevisualizarPdfData);
    document.querySelector('#exportExcelForm button').addEventListener('click', prepareExcelData);
});
