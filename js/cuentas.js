function validarContraseña(contraseñaUsuario) {
    const longitudMinima = 8;
    const tieneMayuscula = /[A-Z]/.test(contraseñaUsuario);
    const tieneMinuscula = /[a-z]/.test(contraseñaUsuario);
    const tieneNumero = /[0-9]/.test(contraseñaUsuario);
    const tieneEspecial = /[!@#$%^&*(),.?":{}|<>]/.test(contraseñaUsuario);

    if (contraseñaUsuario.length < longitudMinima) {
        return "La contraseña debe tener al menos 8 caracteres.";
    }
    if (!tieneMayuscula) {
        return "La contraseña debe contener al menos una letra mayúscula.";
    }
    if (!tieneMinuscula) {
        return "La contraseña debe contener al menos una letra minúscula.";
    }
    if (!tieneNumero) {
        return "La contraseña debe contener al menos un número.";
    }
    if (!tieneEspecial) {
        return "La contraseña debe contener al menos un carácter especial.";
    }
    return "valida";
}

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
            return;
        }

        // Validar la contraseña
        const resultadoValidacion = validarContraseña(contraseñaUsuario);
        if (resultadoValidacion !== "valida") {
            mostrarMensajeModal(resultadoValidacion, true);
            return;
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
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(result => {
            if (result.status === 'error') {
                mostrarMensajeModal(result.message, true);
            } else {
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
            if (response) {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            }
        })
        .then(result => {
            if (result && result.status === 'success') {
                setTimeout(() => {
                    window.location.href = 'codigo_cuenta.html';
                }, 2000);
            } else if (result) {
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
