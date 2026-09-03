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
