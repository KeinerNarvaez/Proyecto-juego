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
    const botonRenovar = document.getElementById('boton-renovar');

    botonRenovar.addEventListener('click', function(event) {
        event.preventDefault();

        const contrasenaNueva = document.getElementById('contrasena-nueva').value;
        const contrasenaVerificada = document.getElementById('contrasena-verificada').value;

        // Verificar que las contraseñas coincidan
        if (contrasenaNueva !== contrasenaVerificada) {
            mostrarMensajeModal('Las contraseñas no son iguales', true);
            return;
        }

        // Validar la contraseña nueva
        const resultadoValidacion = validarContraseña(contrasenaNueva);
        if (resultadoValidacion !== "valida") {
            mostrarMensajeModal(resultadoValidacion, true);
            return;
        }

        // Enviar solo la nueva contraseña
        const data = { contrasenaVerificada };

        fetch('./php/register_renovar_contrasena.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                mostrarMensajeModal('Contraseña renovada correctamente', false);

                setTimeout(() => {
                    window.location.href = 'index.html';
                }, 3000);
            } else {
                mostrarMensajeModal(result.message, true);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Función para mostrar el modal con mensaje
    function mostrarMensajeModal(mensaje, esError) {
        const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
        const mensajeModalBody = document.getElementById('mensajeModalBody');

        const alertType = esError ? 'alert-danger' : 'alert-secondary';
        const icon = esError
            ? '<i class="fa-solid fa-xmark" style="display: flex; justify-content: center; font-size: 120px; color: red; margin: 0 auto;"></i>'
            : '<i class="fa-solid fa-check" style="display: block; font-size: 80px; margin: 0 auto; margin-top: -12px;"></i>';

        mensajeModalBody.innerHTML = `
            <div class="alert ${alertType}" style="font-size: 70px; text-align: center;">
                ${mensaje}
                <br>
                ${icon}
            </div>
        `;
        mensajeModal.show();
    }
});
