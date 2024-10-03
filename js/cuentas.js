window.addEventListener('DOMContentLoaded', ()=>{
    
    //obtenemos los inputs de los datos del formulario
    let nombreUsuario = document.getElementById('name').value;
    let apellidoUsuario = document.getElementById('lastName').value;
    let emailUsuario = document.getElementById('email').value;
    let contraseñaUsuario = document.getElementById ('password').value;

    //boton 
    const boton = document.getElementById('boton-envio');

    boton.addEventListener('click' , function(event){
        event.preventDefault();

        const data = {
            nombreUsuario,
            apellidoUsuario,
            emailUsuario,
            contraseñaUsuario
        }

        fetch('../php/funcion_crear_cuenta.php',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
    })
 
})


