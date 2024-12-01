document.querySelector('form').addEventListener('submit', function(event) {
    let emailField = document.getElementById('user');
    let passwordField = document.getElementById('password');
    let emailError = document.getElementById('email-error');
    let passwordError = document.getElementById('password-error');

    emailError.textContent = '';
    passwordError.textContent = '';

    let valid = true;

    if (emailField.value.trim() === '') {
        emailError.textContent = 'Por favor, introduce tu correo electrónico.';
        valid = false;
    }

    if (passwordField.value.trim() === '') {
        passwordError.textContent = 'Por favor, introduce tu contraseña.';
        valid = false;
    }

    if (!valid) {
        event.preventDefault();
    }
});
