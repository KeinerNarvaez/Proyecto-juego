window.addEventListener('DOMContentLoaded', () => {
    const boton = document.getElementById('boton-envio');

    boton.addEventListener('click', function (event) {
        event.preventDefault();

        // Obtener los valores de los campos
        let nombreUsuario = document.getElementById('name').value;
        let apellidoUsuario = document.getElementById('lastName').value;
        let emailUsuario = document.getElementById('email').value;
        let contraseñaUsuario = document.getElementById('password').value;

// Validar que los campos no estén vacíos
if (!nombreUsuario || !apellidoUsuario || !emailUsuario || !contraseñaUsuario) {
    const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
    const mensajeModalBody = document.getElementById('mensajeModalBody');

    // Construir el mensaje con un <img> en el HTML
    mensajeModalBody.innerHTML = `
        <div class="alert alert-danger" style="display: flex; flex-direction: column; margin-top:1px; align-items: center; padding-left: 25px;   height: 340px;">
            ¿Sabías que es obligatorio llenar  todos los campos requeridos   para crear tu cuenta?
            <div style="display: flex; align-items: center; margin-top: 2px;">
                <img src="./Assest/emoji.png" style="width: 90px; margin-right: 10px;" alt="emoji" />
            </div>
        </div>
    `;
    mensajeModal.show();
    return; // Detener la ejecución si hay campos vacíos
}

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
            const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
            const mensajeModalBody = document.getElementById('mensajeModalBody');

            if (result.status === 'success') {
                mensajeModalBody.innerHTML = '<div class="alert alert-secondary" style="">' + result.message + ' <br> '+ '<i class="fa-solid fa-envelope" style ="display:flex; justify-content:center ;  font-size: 80px;"></i></div>';
                
                mensajeModal.show();

                // Redirigir después de un tiempo (opcional)
                setTimeout(() => {
                    window.location.href = 'codigo_cuenta.html';
                }, 5000); // Redirigir después de 10 segundos
            } else {
                mensajeModalBody.innerHTML = '<div class="alert alert-danger" style="font-size: 70px;">' + 
                result.message + 
                ' <br> <i class="fa-solid fa-xmark" style="display: flex; justify-content: center; font-size: 120px; color:red"></i></div>';
            mensajeModal.show();
            
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
            const mensajeModalBody = document.getElementById('mensajeModalBody');
            mensajeModalBody.innerHTML = '<div class="alert alert-danger">' + error + '</div>'; // Mensaje de error en caso de fallo
            mensajeModal.show();
        });
    });
});
