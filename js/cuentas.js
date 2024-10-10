window.addEventListener('DOMContentLoaded', () => {
    const boton = document.getElementById('boton-envio');
    
    boton.addEventListener('click', function (event) {
        event.preventDefault();

        // Obtener los valores de los campos
        const nombreUsuario = document.getElementById('name').value;
        const apellidoUsuario = document.getElementById('lastName').value;
        const emailUsuario = document.getElementById('email').value;
        const contraseñaUsuario = document.getElementById('password').value;

        // Validar que los campos no estén vacíos
        if (!nombreUsuario || !apellidoUsuario || !emailUsuario || !contraseñaUsuario) {
            mostrarMensajeModal('¿Sabías que es obligatorio llenar todos los campos requeridos para crear tu cuenta?', true);
            return; // Detener la ejecución si hay campos vacíos
        }

        const data = {
            nombreUsuario,
            apellidoUsuario,
            emailUsuario,
            contraseñaUsuario
        };

        // Fetch para el registro
        fetch('./php/register_cuenta.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            // Verificar si la respuesta es válida
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(result => {
            if (result.status === 'error') {
                // Mostrar error si el registro falla
                mostrarMensajeModal(result.message, true);
            } else {
                // Si el registro es exitoso, enviar el correo
                return fetch('./php/register_envioCorreo.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        nombreUsuario,
                        apellidoUsuario,
                        emailUsuario,
                    })
                });
            }
        })
        .then(response => {
            // Verificar si la respuesta del envío del correo es válida
            if (response) {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            }
        })
        .then(result => {
            if (result && result.status === 'success') {
                // Mostrar mensaje de éxito si el correo se envió correctamente
                mostrarMensajeModal('El correo se envió correctamente. Por favor, revisa tu bandeja de entrada.', false);
                setTimeout(() => {
                    window.location.href = 'codigo_cuenta.html';
                }, 5000); // Redirigir después de 10 segundos

            } else if (result) {
                // Mostrar error si el envío del correo falla
                mostrarMensajeModal(result.message, true);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensajeModal('Ocurrió un error al procesar tu solicitud: ' + error.message, true);
        });
    });

    // Función para mostrar el modal con mensaje
    function mostrarMensajeModal(mensaje, esError) {
        const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
        const mensajeModalBody = document.getElementById('mensajeModalBody');

        const alertType = esError ? 'alert-danger' : 'alert-secondary';
        mensajeModalBody.innerHTML = `
            <div class="alert ${alertType}" style="display: flex; flex-direction: column; align-items: center;">
                ${mensaje}
                <div style="display: flex; align-items: center; margin-top: 2px;">
                    <img src="./Assest/emoji.png" style="width: 90px; margin-right: 10px;" alt="emoji" />
                </div>
            </div>
        `;
        mensajeModal.show();
    }
});
