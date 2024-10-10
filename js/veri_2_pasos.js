window.addEventListener('DOMContentLoaded' , ()=>{
    //declaramos la variable del boton 
    let botonVeri = document.getElementById('boton-veri');

    botonVeri.addEventListener('click' , function(event){
        event.preventDefault;

         // Unificar los inputs del código que brinda el usuario
         const codigo = [
        document.getElementById('cod-1').value,
        document.getElementById('cod-2').value,
        document.getElementById('cod-3').value,
        document.getElementById('cod-4').value,
        document.getElementById('cod-5').value,
         document.getElementById('cod-6').value
       ].join(''); // Unir los 6 inputs

       // Empaquetar el código en un objeto
       const data = {
         codigo 
       }

        // Enviar el código al servidor mediante fetch
         fetch('./php/', {
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
               Código verificado correctamente. Redirigiendo para el menu.
               <br> 
               <i class="fa-solid fa-check" style="display: block; font-size: 80px; margin: 20px auto;"></i>
           </div>
        `;
           mensajeModal.show();
               setTimeout(() => {
                    window.location.href = 'menu.html'; // Redirigir después de 3 segundos
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
        })
    })
})