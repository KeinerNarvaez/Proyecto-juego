window.addEventListener('DOMContentLoaded', () => {
    // Llamamos al botón para verificar el código
    let botonVerificarCodigo = document.getElementById('botonVerificarCodigo');

    botonVerificarCodigo.addEventListener('click', function(event) {
        event.preventDefault();

        const codigo = [
            document.getElementById('input1-codigo').value,
            document.getElementById('input2-codigo').value,
            document.getElementById('input3-codigo').value,
            document.getElementById('input4-codigo').value,
            document.getElementById('input5-codigo').value,
            document.getElementById('input6-codigo').value
        ].join(''); 
        const data = {
            codigoGenerado: codigo 
        };
        fetch('./php/ingresar_sala.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data) 
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                console.log(data.message);
                
              const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
              const mensajeModalBody = document.getElementById('mensajeModalBody');
           mensajeModalBody.innerHTML = `
           <div class="alert alert-secondary" style="text-align: center; margin-top:-12px;" >
               Código verificado correctamente. Redirigiendo para renovar la contraseña
               <br> 
               <i class="fa-solid fa-check" style="display: block; font-size: 80px; margin: 20px auto;"></i>
           </div>
        `;
           mensajeModal.show();
               setTimeout(() => {
                    window.location.href = 'renovar_contrasena.html'; // Redirigir después de 3 segundos
                }, 3000); 
            } else {
             // Mostrar modal de error 
             const mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
             const mensajeModalBody = document.getElementById('mensajeModalBody');
             mensajeModalBody.innerHTML = `
               <div class="alert alert-danger" style="font-size: 70px;">
                   ${data.message}
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
