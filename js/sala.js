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
            } else {
                console.log(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error); // Manejo de errores en la consola
        });
    });
});
