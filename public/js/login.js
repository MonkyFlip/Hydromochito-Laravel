document.addEventListener('DOMContentLoaded', function() {
    var errors = JSON.parse(document.getElementById('error-data').textContent);

    if (errors.length > 0) {
        var errorMessagesContainer = document.getElementById('error-messages');
        errors.forEach(function(error) {
            var errorParagraph = document.createElement('p');
            errorParagraph.textContent = error;
            errorMessagesContainer.appendChild(errorParagraph);
        });
        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    }

    var passwordInput = document.getElementById('registerPassword');
    var passwordStrengthBar = document.getElementById('passwordStrengthBar');

    passwordInput.addEventListener('input', function() {
        var value = passwordInput.value;
        var strength = 0;

        // Verificar requisitos
        if (/[a-z]/.test(value)) strength += 1; // Minúsculas
        if (/[A-Z]/.test(value)) strength += 1; // Mayúsculas
        if (/[0-9]/.test(value)) strength += 1; // Números
        if (/[@$!%*#?&]/.test(value)) strength += 1; // Carácter especial
        if (value.length >= 8) strength += 1; // Longitud mínima

        // Actualizar barra de progreso
        var percentage = (strength / 5) * 100;
        passwordStrengthBar.style.width = percentage + '%';
        passwordStrengthBar.setAttribute('aria-valuenow', percentage);

        // Cambiar color de la barra según la fuerza
        if (strength <= 2) {
            passwordStrengthBar.classList.remove('bg-success', 'bg-warning');
            passwordStrengthBar.classList.add('bg-danger');
        } else if (strength === 3 || strength === 4) {
            passwordStrengthBar.classList.remove('bg-success', 'bg-danger');
            passwordStrengthBar.classList.add('bg-warning');
        } else if (strength === 5) {
            passwordStrengthBar.classList.remove('bg-warning', 'bg-danger');
            passwordStrengthBar.classList.add('bg-success');
        }
    });
});
