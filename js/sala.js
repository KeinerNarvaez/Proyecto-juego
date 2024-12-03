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
        const alerta = document.getElementById('alerta');

        // Validación de que el código tiene 6 caracteres
        if (codigo.length !== 6) {
            alerta.innerText = 'Por favor, ingresa un código de 6 caracteres.';
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
            const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
            const mensajeModalBody = document.getElementById('mensajeModalBody');
            if (data.status === 'success') {
                // Si el código es correcto, mostrar un mensaje de éxito
                mensajeModalBody.innerHTML = `
                    <div class="alert alert-secondary" style="text-align: center; margin-top:-12px;">
                        <h1 style="font-size: 65px;">Espere un momento mientras carga</h1>
                        <br>
                        <i class="fa-solid fa-spinner fa-spin-pulse" style="display: block; font-size: 100px; margin: 20px auto ; margin-top:-12px"></i>
                    </div>
                `;
                mensajeModal.show();

                // Redirigir después de unos segundos
                setTimeout(() => {
                    window.location.href = 'avatar.html';
                }, 5000);
            } else {
                // Mostramos el mensaje de error al usuario
                alerta.innerText = data.message;
            }
        })
        .catch(error => {
            // Manejo de errores de red
            console.error('Error:', error);
            alerta.innerText = 'Hubo un problema al procesar tu solicitud.';
        });
    });
});

