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
            mostrarMensaje("Las contraseñas no son iguales", true);
            return;
        }

        // Validar la contraseña nueva
        const resultadoValidacion = validarContraseña(contrasenaNueva);
        if (resultadoValidacion !== "valida") {
            mostrarMensaje(resultadoValidacion, true);
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
                // Mostrar el modal de éxito
                mostrarModalExito('Contraseña renovada correctamente');
                setTimeout(() => {
                    window.location.href = 'index.html';
                }, 3000);
            } else {
                mostrarMensaje(result.message, true);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Función para mostrar el mensaje en el div "respuesta"
    function mostrarMensaje(mensaje, esError) {
        const respuestaDiv = document.getElementById('respuesta');
        respuestaDiv.style.color = esError ? 'red' : 'green';
        respuestaDiv.innerHTML = `<p>${mensaje}</p>`;
        respuestaDiv.style.visibility = "visible";
    }

   // Función para mostrar el modal de éxito con el mensaje personalizado
   function mostrarModalExito() {
    const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
    const mensajeModalBody = document.getElementById('mensajeModalBody');
    
    mensajeModalBody.innerHTML = `
       <div class="alert alert-secondary" style="text-align: center; margin-top:-12px; " >
            <h1 style="font-size: 55px; ">Contraseña renovada correctamente</h1>
           <br> 
           <i class="fa-solid fa-check" style="display: block; font-size: 80px; margin-left: -12px auto;"></i>
       </div>
    `;
    
    mensajeModal.show();
}
});
