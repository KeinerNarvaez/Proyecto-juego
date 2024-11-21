window.addEventListener('DOMContentLoaded', () => {
    // Declaro la variable del botón para el correo electrónico
    const botonVerificar = document.getElementById('boton-contrasena');

    botonVerificar.addEventListener('click', function (event) {
        event.preventDefault();

        // Obtenemos el email del usuario mediante el input
        const emailUsuario = document.getElementById('email-usuario').value;

        // Validar que el campo de email no esté vacío
        if (!emailUsuario) {

             // Mostrar modal de error 
             const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
             const mensajeModalBody = document.getElementById('mensajeModalBody');
             mensajeModalBody.innerHTML = `
               <div class="alert alert-danger" style="font-size: 65px; height: 350px;">
                 <h1 style="font-size: 95px; margin-left: 120px; margin-top: 25px;  ">  Campo vacío  </h1> 
                   <br>
                   <i class="fa-solid fa-xmark" style="display: flex;font-size: 120px; color: red; margin-left:350px; margin-top: -55px;  "></i>
               </div>
           `;
           mensajeModal.show();
           
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
            // Mostrar modal de error 
            const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
            const mensajeModalBody = document.getElementById('mensajeModalBody');
            mensajeModalBody.innerHTML = `
              <div class="alert alert-danger" style="font-size: 65px; height: 350px;">
                  ${result.message}
                  <br>
                  <i class="fa-solid fa-xmark" style="display: flex;font-size: 120px; color: red; margin-left:350px;"></i>
              </div>
          `;
          mensajeModal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
