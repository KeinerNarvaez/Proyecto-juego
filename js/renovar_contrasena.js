window.addEventListener('DOMContentLoaded', () => {
    const botonRenovar = document.getElementById('boton-renovar');

    botonRenovar.addEventListener('click', function(event) {
        event.preventDefault();

        const contrasenaNueva = document.getElementById('contrasena-nueva').value;
        const contrasenaVerificada = document.getElementById('contrasena-verificada').value;

        if (contrasenaNueva !== contrasenaVerificada) {
            alert("Las contraseñas no son iguales");
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
                alert('Contraseña renovada correctamente');
                setTimeout(() => {
                    window.location.href = 'index.html';
                }, 5000);
            } else {
                alert(result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
