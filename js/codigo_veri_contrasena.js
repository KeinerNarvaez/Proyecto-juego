window.addEventListener('DOMContentLoaded', () => {
    // Declaro la variable del botón para confirmar el código
    const botonVerificar = document.getElementById('botonVerificarCodigo');

    // Agrego el evento al botón
    botonVerificar.addEventListener('click', function (event) {
        event.preventDefault();

        // Empaquetamos el código para cumplir con el fetch
        const codigo = [
            document.getElementById('input1').value,
            document.getElementById('input2').value,
            document.getElementById('input3').value,
            document.getElementById('input4').value,
            document.getElementById('input5').value,
            document.getElementById('input6').value
        ].join(''); // Unir los 6 dígitos en una cadena

        // Realizar el fetch
        fetch('./php/register_codigo_contrasena.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ codigo })
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert('Código verificado correctamente');
                window.location.href = 'renovar_contrasena.html';
            } else {
                alert(result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
