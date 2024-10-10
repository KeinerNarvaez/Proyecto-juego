window.addEventListener('DOMContentLoaded', () => {
    const botonRenovar = document.getElementById('boton-renovar');

    botonRenovar.addEventListener('click', function(event) {
        event.preventDefault();

        const contrasenaNueva = document.getElementById('contrasena-nueva').value;
        const contrasenaVerificada = document.getElementById('contrasena-verificada').value;

        if (contrasenaNueva !== contrasenaVerificada) {
            // Mostrar modal de error 
            const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
            const mensajeModalBody = document.getElementById('mensajeModalBody');
            mensajeModalBody.innerHTML = `
                <div class="alert alert-danger" style="font-size: 70px;">
                    Las contraseñas no son iguales
                    <br>
                    <i class="fa-solid fa-xmark" style="display: flex; justify-content: center; font-size: 120px; color: red; margin: 0 auto;"></i>
                </div>
            `;
            mensajeModal.show();
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
                // Si la renovación de contraseña fue exitosa, mostrar un mensaje de éxito
                const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
                const mensajeModalBody = document.getElementById('mensajeModalBody');
                
                mensajeModalBody.innerHTML = `
               <div class="alert alert-secondary" style="text-align: center; height: 350px;">
                   <h1 style="font-size: 65px;"> Contraseña renovada correctamente  </h1>
                   <br> 
                   <i class="fa-solid fa-check" style="display: block; font-size: 80px; margin: 0 auto; margin-top: -12px;"></i>
              </div>
             `;

                mensajeModal.show();

                setTimeout(() => {
                    window.location.href = 'index.html';
                }, 3000);
            } else {
              // Mostrar modal de error 
              const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
              const mensajeModalBody = document.getElementById('mensajeModalBody');
              mensajeModalBody.innerHTML = `
                <div class="alert alert-danger" style="font-size: 70px;">
                    ${result.message}
                    <br>
                    <i class="fa-solid fa-xmark" style="display: flex; justify-content: center; font-size: 120px; color: red; margin: 0 auto;"></i>
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
