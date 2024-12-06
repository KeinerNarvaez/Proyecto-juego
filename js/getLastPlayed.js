document.addEventListener('DOMContentLoaded', () => {
    const contenedorBoton = document.getElementById('boton-envio');
    const enlaceBoton = document.getElementById('enlace-boton');

    const manejarRespuesta = (data) => {
        console.log('Datos recibidos:', data);

        if (data.status === 'success') {
            const { nextLevel, mode } = data.data;
            let href = '';

            // Generar el enlace basado en el nivel y el modo
            if (mode === 'posiciones') {
                switch (nextLevel) {
                    case 'level1':
                        href = '../nivelesPociones/mapapociones.html';
                        break;
                    case 'level2':
                        href = '../nivelesPociones/mapapociones2.html';
                        break;
                    case 'level3':
                        href = '../nivelesPociones/mapapociones3.html';
                        break;
                    case 'level4':
                        href = '../nivelesPociones/mapapociones4.html';
                        break;
                    case 'level5':
                        href = '../nivelesPociones/mapapociones5.html';
                        break;
                }
            } else if (mode === 'hechizos') {
                switch (nextLevel) {
                    case 'spellLevel1':
                        href = '../nivelesHechizo/mapaHechizo.html';
                        break;
                    case 'spellLevel2':
                        href = '../nivelesHechizo/mapaHechizo2.html';
                        break;
                    case 'spellLevel3':
                        href = '../nivelesHechizo/mapaHechizo3.html';
                        break;
                    case 'spellLevel4':
                        href = '../nivelesHechizo/mapaHechizo4.html';
                        break;
                    case 'spellLevel5':
                        href = '../nivelesHechizo/mapaHechizo5.html';
                        break;
                }
            }

            // Si se encontró un nivel, actualizar el botón
            if (href) {
                enlaceBoton.innerText = 'Continuar';
                enlaceBoton.onclick = (event) => {
                    event.preventDefault();
                    window.location.href = href; // Redirecciona al nivel
                };
                enlaceBoton.style.display = 'inline-block'; // Asegúrate de mostrar el botón si está oculto
            }
        } else if (data.status === 'no_game') {
            contenedorBoton.innerHTML = `<p>${data.message}</p>`;
        }
    };

    fetch('../php/getLastPlayed.php')
        .then((response) => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(manejarRespuesta)
        .catch((error) => {
            console.error('Error al obtener los datos:', error);
        });
});
