// Llenar datos en el modal Eliminar Usuario
document.querySelectorAll('.btn-danger[data-bs-target="#eliminarUsuarioModal"]').forEach(button => {
    button.addEventListener('click', function () {
        // Encuentra la fila más cercana al botón
        var registro = this.closest('tr');
        
        // Extrae los valores de las columnas de la fila
        var idUsuario = registro.querySelector('td:nth-child(1)').innerText;
        var nombreUsuario = registro.querySelector('td:nth-child(2)').innerText;
        var emailUsuario = registro.querySelector('td:nth-child(3)').innerText;
        var rolUsuario = registro.querySelector('td:nth-child(4)').innerText;

        // Inserta los valores en el modal
        document.getElementById('deleteIdUsuario').innerText = idUsuario;
        document.getElementById('deleteNombreUsuario').innerText = nombreUsuario;
        document.getElementById('deleteEmailUsuario').innerText = emailUsuario;
        document.getElementById('deleteRolUsuario').innerText = rolUsuario;

        // Actualiza la acción del formulario para que apunte al usuario correcto
        document.getElementById('deleteForm').action = `/registros_usuarios/${idUsuario}`;
    });
});

//----------------------------------------------------------------

// Evitar que el submenú se cierre automáticamente al hacer clic en el menú principal
document.addEventListener('DOMContentLoaded', function () {
    // Evitar que el submenú se cierre automáticamente al hacer clic
    document.querySelectorAll('.dropdown-submenu .dropdown-item').forEach(function (element) {
        element.addEventListener('click', function (e) {
            e.stopPropagation(); // Evita que el evento cierre el menú principal
        });
    });
});

//----------------------------------------------------------------

// Modal de Aviso
document.addEventListener('DOMContentLoaded', function () {
    var importResultModalElement = document.getElementById('importResultModal');
    if (importResultModalElement && importDone === '1') { // Detectar si debe mostrar el modal
        var importResultModal = new bootstrap.Modal(importResultModalElement);
        importResultModal.show();
    }
});

