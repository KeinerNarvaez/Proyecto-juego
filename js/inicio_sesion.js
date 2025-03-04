window.addEventListener('DOMContentLoaded', () => {
    // Variable para el botón de iniciar sesión
    const boton = document.getElementById('boton-verificar');

    boton.addEventListener('click', function (event) {
        event.preventDefault();

        // Obtener los valores de los campos
        const emailUser = document.getElementById('email').value;
        const passwordUser = document.getElementById('password').value;

        // Verificar que los campos no estén vacíos
        if (!emailUser || !passwordUser) {
            const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
            const mensajeModalBody = document.getElementById('mensajeModalBody');

            // Mostrar modal si faltan campos
            mensajeModalBody.innerHTML = `
                <div class="alert alert-danger" style="display: flex; flex-direction: column; margin-top:1px; align-items: center; padding-left: 25px; height: 340px;">
                  <h1 style="font-size: 55px;" >  ¡Todos los campos son obligatorios para iniciar sesión!      </h1>
                    <div style="display: flex; align-items: center; margin-top: 2px;">
                        <img src="./Assest/emoji.png" style="width: 90px; margin-right: 10px;" alt="emoji" />
                    </div>
                </div>
            `;
            mensajeModal.show();
            return; // Detener la ejecución si hay campos vacíos
        }

        // Empaquetar los datos
        const data = {
            emailUser: emailUser, // Cambiar el nombre de la propiedad a 'emailUser'
            passwordUser: passwordUser // Cambiar el nombre de la propiedad a 'passwordUser'
        };

        // Hacer la petición fetch
        fetch('./php/register_inicio_sesion.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
            .then(response => {
                // Verifica si la respuesta es válida
                if (!response.ok) {
                    throw new Error('Error en la red');
                }
                return response.json();
            })
            .then(result => {
                const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
                const mensajeModalBody = document.getElementById('mensajeModalBody');

                if (result.status === 'success') {
                // Si el código es correcto, mostrar un mensaje de éxito
              const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
              const mensajeModalBody = document.getElementById('mensajeModalBody');
              mensajeModalBody.innerHTML = `
              <div class="alert alert-secondary" style="text-align: center; margin-top:-12px " >
              <h1 style="font-size: 65px;"> Inicio de sesión correctamente </h1>
               <br> 
               <i class="fa-solid fa-check" style="display: block; font-size: 100px; margin: 20px auto ; margin-top:-12px"></i>
            </div>
         `;
        mensajeModal.show();
                    // Redirigir después de unos segundos
                    setTimeout(() => {
                        window.location.href = 'menu.html';
                    }, 2000);
                } else {
                    // Mostrar modal de error con mensaje del servidor
                    mensajeModalBody.innerHTML = `
                        <div class="alert alert-danger" style="font-size: 55px;">
                            ${result.message}
                            <br>
                            <i class="fa-solid fa-xmark" style="display: flex; justify-content: center; font-size: 120px; color: red;"></i>
                        </div>
                    `;
                    mensajeModal.show();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
                const mensajeModalBody = document.getElementById('mensajeModalBody');

                // Mostrar modal de error en caso de fallo
                mensajeModalBody.innerHTML = `
                    <div class="alert alert-danger" style="font-size: 70px;">
                        Error en el servidor
                        <br>
                        <i class="fa-solid fa-xmark" style="display: flex; justify-content: center; font-size: 120px; color: red;"></i>
                    </div>
                `;
                mensajeModal.show();
            });
    });
});