window.addEventListener('DOMContentLoaded', () => {
    const botonVerificar = document.getElementById('botonVerificarCodigo');

    botonVerificar.addEventListener('click', function (event) {
        event.preventDefault();

        // Obtener el userId desde un elemento en el DOM (puede ser un input oculto o cualquier otro método)
        const userId = document.getElementById('userId').value; // Asegúrate de tener un input con este ID

        const codigo = [
            document.getElementById('input1').value,
            document.getElementById('input2').value,
            document.getElementById('input3').value,
            document.getElementById('input4').value,
            document.getElementById('input5').value,
            document.getElementById('input6').value
        ].join('');

        fetch('./php/register_codigo_cuenta.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ codigo, userId }) // Ahora incluimos userId
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert('Código verificado correctamente, cuenta activada.');
            } else {
                alert(result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
