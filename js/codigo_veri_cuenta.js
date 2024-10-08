window.addEventListener('DOMContentLoaded',() =>{
    //declaro la variable del boton para confirmar el código
    const botonVerificar =  document.getElementById('botonVerificarCodigo');

    botonVerificar.addEventListener('click', function (event){
        event.preventDefault();

        //empaquetamos el código para cumplir con el fetch
        const codigo = [
            document.getElementById('input1').value,
            document.getElementById('input2').value,
            document.getElementById('input3').value,
            document.getElementById('input4').value,
            document.getElementById('input5').value,
            document.getElementById('input6').value
        ].join(''); // unir los 6 dígitos en una cadena
        
        fetch('./php/'),{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ codigo })
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