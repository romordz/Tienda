function toggleFormularioTarjeta() {
    const formulario = document.getElementById('formulario-tarjeta');
    formulario.style.display = (formulario.style.display === 'none' || formulario.style.display === '') ? 'block' : 'none';
}

(function () {
    const API_BASE = window.location.pathname.includes('/pantallas/') ? '../' : '';

    function verificarTarjeta() {
        fetch(`${API_BASE}php/pago/verificar_tarjeta.php`)
            .then(response => response.json())
            .then(data => {
                if (data.tiene_tarjeta) {
                    location.href = `${API_BASE}php/pago/pagar.php`;
                } else {
                    alert('No tienes una tarjeta registrada. Agrega una tarjeta para proceder con el pago.');
                    document.getElementById('btn-add-card').style.display = 'block';
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function validateExpirationDate() {
        const expirationInput = document.getElementById('fecha_vencimiento');
        const errorMessage = document.getElementById('fecha-vencimiento-error');
        const expirationDate = new Date(expirationInput.value);
        const today = new Date();

        errorMessage.style.display = 'none';
        errorMessage.textContent = '';

        today.setHours(0, 0, 0, 0);

        if (expirationDate < today) {
            errorMessage.style.display = 'block';
            errorMessage.textContent = 'La tarjeta ya esta vencida';
            return false;
        }

        return true;
    }

    function validateCVV() {
        const cvvInput = document.getElementById('cvv');
        const errorMessage = document.getElementById('cvv-error');
        const cvv = cvvInput.value;

        errorMessage.style.display = 'none';
        errorMessage.textContent = '';

        if (!/^\d{3}$/.test(cvv)) {
            errorMessage.style.display = 'block';
            errorMessage.textContent = 'El CVV debe ser un número de 3 dígitos.';
            return false;
        }

        return true;
    }

    async function convertirACurrency(totalMXN, currency) {
        const apiKey = 'c21cbe079e98f4b8a1e96c84';
        const endpoint = `https://v6.exchangerate-api.com/v6/${apiKey}/pair/MXN/${currency}`;

        try {
            const response = await fetch(endpoint);
            const data = await response.json();

            if (data.conversion_rate) {
                const totalUSD = (totalMXN * data.conversion_rate).toFixed(2);
                document.getElementById('resultado-conversion').innerText = `Total en ${currency}: $${totalUSD} ${currency}`;
            } else {
                document.getElementById('resultado-conversion').innerText = "Error en la conversión. Inténtalo más tarde.";
            }
        } catch (error) {
            document.getElementById('resultado-conversion').innerText = "Error de conexión. Verifica tu internet.";
            console.error("Error en la API:", error);
        }
    }

    window.verificarTarjeta = verificarTarjeta;
    window.validateExpirationDate = validateExpirationDate;
    window.validateCVV = validateCVV;
    window.convertirACurrency = convertirACurrency;
})();
