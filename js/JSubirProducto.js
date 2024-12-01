function validateImages() {
    var photoInput = document.getElementById('imagenes');
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
// Función para habilitar/deshabilitar el campo de precio
function togglePrecio() {
    var cotizacionSelect = document.getElementById('cotizacion');
    var precioInput = document.getElementById('precio');

    // Checa si la opción seleccionada es "Sí" (valor "1")
    if (cotizacionSelect.value === "1") {
        precioInput.value = ''; // Limpia el campo si se desactiva
        precioInput.disabled = true; // Desactiva el campo de precio
    } else {
        precioInput.disabled = false; // Activa el campo de precio
    }
}

// Escucha cambios en el select de cotización
document.getElementById('cotizacion').addEventListener('change', togglePrecio);

// Llama a la función al cargar la página para manejar el estado inicial
document.addEventListener('DOMContentLoaded', togglePrecio);
