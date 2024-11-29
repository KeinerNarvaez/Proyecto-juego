window.addEventListener('DOMContentLoaded', () => {
    // Llamamos al botón para verificar el código
    let botonVerificarCodigo = document.getElementById('botonVerificarCodigo');

    botonVerificarCodigo.addEventListener('click', function(event) {
        event.preventDefault();

        // Recogemos los valores de los inputs y los unimos en una sola cadena
        const codigo = [
            document.getElementById('input1-codigo').value,
            document.getElementById('input2-codigo').value,
            document.getElementById('input3-codigo').value,
            document.getElementById('input4-codigo').value,
            document.getElementById('input5-codigo').value,
            document.getElementById('input6-codigo').value
        ].join('');

        // Validación de que el código tiene 6 caracteres
        if (codigo.length !== 6) {
            alert('Por favor, ingresa un código de 6 caracteres.');
            return;
        }

        // Datos a enviar al backend
        const data = {
            codigoGenerado: codigo
        };

        // Realizamos la petición al servidor
        fetch('./php/ingresar_sala.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data) // Convertimos el objeto en JSON
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Redirigimos si la respuesta es exitosa
                window.location.href = "avatar.html";
            } else {
                // Mostramos el mensaje de error al usuario
                alert(data.message);
            }
        })
        .catch(error => {
            // Manejo de errores de red
            console.error('Error:', error);
            alert('Hubo un problema al procesar tu solicitud.');
        });
    });
});
