window.addEventListener('DOMContentLoaded', () => {
    // Declaro la variable del botón para el correo electrónico
    const botonVerificar = document.getElementById('boton-contrasena');

    botonVerificar.addEventListener('click', function (event) {
        event.preventDefault();

        // Obtenemos el email del usuario mediante el input
        const emailUsuario = document.getElementById('email-usuario').value;

        // Validar que el campo de email no esté vacío
        if (!emailUsuario) {
            alert('Campo vacío');
            return; // Detener la ejecución si hay campos vacíos
        }

        // Preparar los datos para enviar
        const data = { emailUsuario }; // Cambiar a un objeto

        fetch('./php/register_envioCorreo_contrasena.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data) // Convertir a JSON
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                setTimeout(() => {
                    window.location.href = 'codigo_contrasena.html';
                }, 2000); // Redirigir después de 2 segundos
            } else {
                alert(result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
