window.addEventListener('DOMContentLoaded', () => {
    const boton = document.getElementById('boton-verificar');

    boton.addEventListener('click', function (event) {
        event.preventDefault();

        // Obtener los valores de los campos
        const emailUser = document.getElementById('email').value;
        const passwordUser = document.getElementById('password').value;

        // Verificar que los campos no estén vacíos
        if (!emailUser || !passwordUser) {
            mostrarMensajeModal('¡Todos los campos son obligatorios para iniciar sesión!', true);
            return; // Detener la ejecución si hay campos vacíos
        }

        // Empaquetar los datos
        const data = {
            emailUser: emailUser,
            passwordUser: passwordUser
        };

        // Primer fetch: para verificar las credenciales
        fetch('./php/register_inicio_sesion.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red');
            }
            return response.json();
        })
        .then(result => {
            console.log(result); // Para depurar la respuesta
            if (result.status === 'success') {
                // Si el inicio de sesión es exitoso, procedemos a enviar el código de verificación
                return fetch('./php/register_envio_dosPasos.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ emailUser })
                });
            } else {
                // Mostrar modal de error si el inicio de sesión falla
                mostrarMensajeModal(result.message, true);
                throw new Error('Error de inicio de sesión');
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red al enviar el código de verificación');
            }
            return response.json();
        })
        .then(result => {
            if (result.status === 'success') {
                mostrarMensajeModal('El código de verificación ha sido enviado a tu correo.', false);
                setTimeout(() => {
                    window.location.href = 'codigo_verificacion.html'; // Redirigir a la página para introducir el código
                }, 5000);
            } else {
                mostrarMensajeModal(result.message, true);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensajeModal('Ocurrió un error al procesar tu solicitud: ' + error.message, true);
        });
    });

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
