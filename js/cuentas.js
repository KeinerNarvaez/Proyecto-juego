window.addEventListener('DOMContentLoaded', ()=>{
   

    //variable de boton
    const boton = document.getElementById('boton-envio');

    boton.addEventListener('click' , function(event){
        event.preventDefault();
         
         
      //obtenemos los inputs de los datos del formulario
      let nombreUsuario = document.getElementById('name').value;
      let apellidoUsuario = document.getElementById('lastName').value;
      let emailUsuario = document.getElementById('email').value;
      let contraseñaUsuario = document.getElementById ('password').value;

        //empaquetamiento de datos
        const data = {
            nombreUsuario,
            apellidoUsuario,
            emailUsuario,
            contraseñaUsuario
        }

        fetch('./php/register_cuenta.php',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)  
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert('Cuenta creada exitosamente');
            } else {
                alert('Error: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    })
 
})


