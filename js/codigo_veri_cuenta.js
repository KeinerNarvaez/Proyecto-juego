window.addEventListener('DOMContentLoaded', () => {
    // Llamamos al botón para verificar el código
    let botonCodigo = document.getElementById('botonVerificarCodigo');

    botonCodigo.addEventListener('click', function(event) {
        event.preventDefault();

        // Unificar los inputs del código que brinda el usuario
        const codigo = [
<<<<<<< HEAD
            document.getElementById('input1-codigo').value,
            document.getElementById('input2-codigo').value,
            document.getElementById('input3-codigo').value,
            document.getElementById('input4-codigo').value,
            document.getElementById('input5-codigo').value,
            document.getElementById('input6-codigo').value
        ].join(''); // Unir los 6 inputs
=======
            document.getElementById('input1').value,
            document.getElementById('input2').value,
            document.getElementById('input3').value,
            document.getElementById('input4').value,
            document.getElementById('input5').value,
            document.getElementById('input6').value
        ].join(''); //las unifico los 6
>>>>>>> 168af8e860bbf2dd50049d750e278b9a0f516de2

        // Empaquetar el código en un objeto
        const data = {
             codigo
        };

        // Enviar el código al servidor mediante fetch
        fetch('./php/register_codigo_cuenta.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data) // Enviar el código en formato JSON
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert('Cuenta verificada correctamente, ya puedes iniciar sesion normalmente.');
                setTimeout(() => {
                    window.location.href = 'login.html'; // Redirigir después de 3 segundos
                }, 3000);
            } else {
                alert(result.message); // Mostrar mensaje de error
            }
        })
        .catch(error => {
            console.error('Error:', error); // Manejo de errores en la consola
        });
    });
});