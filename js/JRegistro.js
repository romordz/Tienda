function validateForm() {
    return validateImage() && validateUsername() && validateEmail() && validateBirthdate() && validateFullName();
}

function validateImage() {
    var photoInput = document.getElementById('photo');
    var errorMessage = document.getElementById('photo-error');
    var file = photoInput.files[0];

    errorMessage.style.display = 'none';
    errorMessage.textContent = '';

    if (file) {
        var maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            errorMessage.style.display = 'block';
            errorMessage.textContent = 'El archivo es demasiado grande. El tamaño máximo permitido es de 2 MB.';
            photoInput.value = '';
            return false;
        }

        if (file.type !== 'image/jpeg') {
            errorMessage.style.display = 'block';
            errorMessage.textContent = 'Solo se permiten archivos en formato JPG.';
            photoInput.value = '';
            return false;
        }
    }

    return true;
}

function validateUsername() {
    var usernameInput = document.getElementById('username');
    var errorMessage = document.getElementById('username-error');
    var username = usernameInput.value;

    errorMessage.style.display = 'none';
    errorMessage.textContent = '';

    if (username.length < 3) {
        errorMessage.style.display = 'block';
        errorMessage.textContent = 'El nombre de usuario debe tener al menos 3 caracteres.';
        return false;
    }

    fetch('php/sesion/check_username.php?username=' + encodeURIComponent(username))
        .then(response => response.text())
        .then(data => {
            if (data !== 'available') {
                errorMessage.style.display = 'block';
                errorMessage.textContent = data;
            }
        });

    return true;
}

function validateEmail() {
    var emailInput = document.getElementById('email');
    var errorMessage = document.getElementById('email-error');
    var email = emailInput.value;

    errorMessage.style.display = 'none';
    errorMessage.textContent = '';

    var emailPattern = /^[^\s@]+@(outlook\.com|hotmail\.com|gmail\.com|yahoo\.com)$/i;
    
    if (!emailPattern.test(email)) {
        errorMessage.style.display = 'block';
        errorMessage.textContent = 'Por favor, ingresa un correo electrónico de un dominio válido (outlook, hotmail, gmail, yahoo).';
        return false;
    }

    fetch('php/sesion/check_email.php?email=' + encodeURIComponent(email))
        .then(response => response.text())
        .then(data => {
            if (data !== 'available') {
                errorMessage.style.display = 'block';
                errorMessage.textContent = data;
            }
        });

    return true;
}


function validateBirthdate() {
    var birthdateInput = document.getElementById('birthdate');
    var errorMessage = document.getElementById('birthdate-error');

    var [year, month, day] = birthdateInput.value.split('-');
    var birthdate = new Date(year, month - 1, day);
    var today = new Date();

    errorMessage.style.display = 'none';
    errorMessage.textContent = '';

    today.setHours(0, 0, 0, 0);
    birthdate.setHours(0, 0, 0, 0);

    console.log(today);
    console.log(birthdate);
    console.log(birthdateInput.value);

    if (birthdate.getTime() >= today.getTime()) {
        errorMessage.style.display = 'block';
        errorMessage.textContent = 'La fecha de nacimiento no puede ser la fecha actual ni una fecha futura.';
        return false;
    }

    return true;
}

function validateFullName() {
    var fullNameInput = document.getElementById('full-name');
    var fullName = fullNameInput.value;
    var errorMessage = document.getElementById('full-name-error');

    errorMessage.style.display = 'none';
    errorMessage.textContent = '';

    var namePattern = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;

    if (!namePattern.test(fullName)) {
        errorMessage.style.display = 'block';
        errorMessage.textContent = 'El nombre solo puede contener letras y espacios.';
        return false;
    }

    return true;
}

function validatePassword() {
    var passwordInput = document.getElementById('password');
    var password = passwordInput.value;
    var errorMessage = document.getElementById('password-error');

    errorMessage.style.display = 'none';
    errorMessage.textContent = '';

    var passwordPattern = /^(?=.*[a-zñÑ])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-zñÑ\d!@#$%^&*()_+]{8,}$/;

    if (!passwordPattern.test(password)) {
        errorMessage.style.display = 'block';
        errorMessage.textContent = 'La contraseña debe tener al menos 8 caracteres en total, incluyendo 1 mayuscula, 1 minuscula, 1 número y 1 símbolo especial';
        return false;
    }

    return true;
}