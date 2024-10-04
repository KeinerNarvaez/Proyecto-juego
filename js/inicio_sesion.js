window.addEventListener('DOMContentLoaded', () => {
    // Variable para el botón de iniciar sesión
    const boton = document.getElementById('boton-verificar');

    boton.addEventListener('click', function (event) {
        event.preventDefault();

        // Obtenemos los datos del usuario mediante los inputs
        const emailUser = document.getElementById('email').value;
        const passwordUser = document.getElementById('password').value;

        // Verificamos que no estén vacíos
        if (emailUser && passwordUser) {
            // Empaquetamos las variables
            const data = {
                emailUser,
                passwordUser // Asegúrate de que sea passwordUser
            };

            // Hacemos la petición fetch
            fetch('./php/register_inicio_sesion.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Inicio de sesión exitoso');
                        // Redirigir a la página de inicio o dashboard
                        window.location.href = 'verificacion_2pasos.html';
                    } else {
                        alert(data.message); // Mostrar mensaje de error
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        } else {
            alert('Por favor, completa todos los campos');
        }
    });
});
