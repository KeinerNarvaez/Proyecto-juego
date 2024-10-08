window.addEventListener('DOMContentLoaded' , ()=>{
    //declaro la variable para el boton de renovar la contraseña del usuario
    const botonRenovar = document.getElementById('boton-renovar');

    botonRenovar.addEventListener('click' , function(event){
        event.preventDefault();

        const contrasenaNueva = document.getElementById('contrasena-nueva"').value;
        const contrasenaVerificada = document.getElementById('contrasena-verificada').value;

        if(contrasenaNueva !== contrasenaVerificada){
            alert ("las contraseñas no son iguales")
            return; // Detener la ejecución si las contraseñas no son iguales
        }
        
        //empaquetamos las contraseña a renovar
        const data = [
            contrasenaNueva ,
            contrasenaVerificada
        ]

        
        fetch('./php/'),{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({data})
            }
            .then(response => response.json())
            .then(result => {
            if (result.status === 'success') {
                alert('Código verificado correctamente');
            } else {
                alert(result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });


    })
})