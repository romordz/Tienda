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
function togglePrecio() {
    var cotizacionSelect = document.getElementById('cotizacion');
    var precioInput = document.getElementById('precio');

    if (cotizacionSelect.value === "1") {
        precioInput.value = '';
        precioInput.disabled = true;
    } else {
        precioInput.disabled = false;
    }
}

document.getElementById('cotizacion').addEventListener('change', togglePrecio);

document.addEventListener('DOMContentLoaded', togglePrecio);
