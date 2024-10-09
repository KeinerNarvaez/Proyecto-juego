window.addEventListener('DOMContentLoaded', () => {
    // Llamamos al botón para verificar el código
    let botonContrasena = document.getElementById('boton-contrasena');

    botonContrasena.addEventListener('click', function(event) {
        event.preventDefault();

        // Unificar los inputs del código que brinda el usuario
        const codigo = [
            document.getElementById('input1-codigo').value,
            document.getElementById('input2-codigo').value,
            document.getElementById('input3-codigo').value,
            document.getElementById('input4-codigo').value,
            document.getElementById('input5-codigo').value,
            document.getElementById('input6-codigo').value
        ].join(''); // Unir los 6 inputs

        // Empaquetar el código en un objeto
        const data = {
            codigo: codigo
        };

        // Enviar el código al servidor mediante fetch
        fetch('./php/register_codigo_contrasena.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data) // Enviar el código en formato JSON
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert('Código verificado correctamente. Redirigiendo para renovar la contraseña...');
                setTimeout(() => {
                    window.location.href = 'renovar_contrasena.html'; // Redirigir después de 5 segundos
                }, 5000);
            } else {
                alert(result.message); // Mostrar mensaje de error
            }
        })
        .catch(error => {
            console.error('Error:', error); // Manejo de errores en la consola
        });
    });
});
