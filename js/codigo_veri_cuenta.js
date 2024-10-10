window.addEventListener('DOMContentLoaded', () => {
    // Llamamos al botón para verificar el código
    let botonCodigo = document.getElementById('botonVerificarCodigo');

    botonCodigo.addEventListener('click', function(event) {
        event.preventDefault();

        // Unificar los inputs del código que brinda el usuario
        const codigo = [
            document.getElementById('input1-codigo').value,
            document.getElementById('input2-codigo').value,
            document.getElementById('input3-codigo').value,
            document.getElementById('input4-codigo').value,
            document.getElementById('input5-codigo').value,
            document.getElementById('input6-codigo').value
        ].join(''); // Unir los 6 inputs

        // Empaquetar el código en un objeto
        const data = {
             codigo
        };

        // Enviar el código al servidor mediante fetch
        fetch('./php/register_codigo_cuenta.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data) // Enviar el código en formato JSON
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                // Si el código es correcto, mostrar un mensaje de éxito
                const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
                const mensajeModalBody = document.getElementById('mensajeModalBody');
                
                mensajeModalBody.innerHTML = `
                <div class="alert alert-secondary" style="text-align: center; margin-top:-12px;" >
                    Cuenta verificada correctamente, ya puedes iniciar sesión normalmente. 
                    <br> 
                    <i class="fa-solid fa-check" style="display: block; font-size: 80px; margin: 20px auto;"></i>
                </div>
             `;

                mensajeModal.show();

                setTimeout(() => {
                    window.location.href = 'login.html'; // Redirigir después de 3 segundos
                }, 3000);
            } else {
                      // Mostrar modal de error 
                      const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
                      const mensajeModalBody = document.getElementById('mensajeModalBody');
                      mensajeModalBody.innerHTML = `
                        <div class="alert alert-danger" style="font-size: 70px;">
                            ${result.message}
                            <br>
                            <i class="fa-solid fa-xmark" style="display: flex; justify-content: center; font-size: 120px; color: red; margin-left:350px;"></i>
                        </div>
                    `;
                    mensajeModal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error); // Manejo de errores en la consola
        });
    });
});