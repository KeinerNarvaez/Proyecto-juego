window.addEventListener('DOMContentLoaded', () => {
    const boton = document.getElementById('boton-envio');

    boton.addEventListener('click', function (event) {
        event.preventDefault();

        let nombreUsuario = document.getElementById('name').value;
        let apellidoUsuario = document.getElementById('lastName').value;
        let emailUsuario = document.getElementById('email').value;
        let contraseñaUsuario = document.getElementById('password').value;

        const data = {
            nombreUsuario,
            apellidoUsuario,
            emailUsuario,
            contraseñaUsuario
        }

        fetch('./php/register_cuenta.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            const alertContainer = document.getElementById('alert-container'); // Asegúrate de tener un contenedor para las alertas
            alertContainer.innerHTML = ''; // Limpiar contenido previo

            if (result.status === 'success') {
                const alertContainer = document.getElementById('alert-container');
                alertContainer.innerHTML = '<div class="alert alert-success">' + result.message + '</div>'; // Esto mostrará la alerta generada por PHP
                window.location.href = 'codigo_cuenta.html';
            } else {
                // Mostrar errores en el contenedor
                alertContainer.innerHTML = result.message; // Esto mostrará la alerta generada por PHP
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const alertContainer = document.getElementById('alert-container');
            alertContainer.innerHTML = '<div class="alert alert-danger">' + error + '</div>'; // Mensaje de error en caso de fallo
        });
    });
});
