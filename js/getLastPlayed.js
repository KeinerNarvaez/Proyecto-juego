const contenedorBoton = document.getElementById('boton-envio'); // Contenedor del botón
const enlaceBoton = document.getElementById('enlace-boton'); // Enlace que contiene el href

// Función para manejar la respuesta de la API
const manejarRespuesta = (data) => {
    let href = ''; // URL para el nivel correspondiente

    if (data.status === 'success') {
        console.log('Data received:', data); // Verifica que los datos estén llegando correctamente

        // Asignar URL según el modo y nivel
        if (data.mode === 'posiciones') {
            switch (data.nextLevel) {
                case 'level1':
                    href = '../nivelesPociones/mapapociones.html'; // Nivel 1 de pociones
                    break;
                case 'level2':
                    href = '../nivelesPociones/mapapociones2.html'; // Nivel 2 de pociones
                    break;
                case 'level3':
                    href = '../nivelesPociones/mapapociones3.html'; // Nivel 3 de pociones
                    break;
                case 'level4':
                    href = '../nivelesPociones/mapapociones4.html'; // Nivel 4 de pociones
                    break;
                case 'level5':
                    href = '../nivelesPociones/mapapociones5.html'; // Nivel 5 de pociones
                    break;
                default:
                    console.error('Nivel desconocido:', data.nextLevel);
                    return;
            }
        } else if (data.mode === 'hechizos') {
            switch (data.nextLevel) {
                case 'spellLevel1':
                    href = '../nivelesHechizos/mapaHechizo.html'; // Nivel 1 de hechizos
                    break;
                case 'spellLevel2':
                    href = '../nivelesHechizos/mapaHechizo2.html'; // Nivel 2 de hechizos
                    break;
                case 'spellLevel3':
                    href = '../nivelesHechizos/mapaHechizo3.html'; // Nivel 3 de hechizos
                    break;
                case 'spellLevel4':
                    href = '../nivelesHechizos/mapaHechizo4.html'; // Nivel 4 de hechizos
                    break;
                case 'spellLevel5':
                    href = '../nivelesHechizos/mapaHechizo5.html'; // Nivel 5 de hechizos
                    break;
                default:
                    console.error('Nivel desconocido:', data.nextLevel);
                    return;
            }
        }

        // Modificar el href del enlace en el HTML
        enlaceBoton.setAttribute('href', href);
        console.log('href set to:', href); // Verifica que el href se esté modificando correctamente

        // Agregar el event listener para redirigir con window.location
        enlaceBoton.addEventListener('click', (event) => {
            event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace
            console.log('Redirecting to:', href); // Verifica que la función se llame
            window.location.href = href; // Redirigir con window.location
        });

    } else if (data.status === 'completed') {
        // Si el usuario ya completó todos los niveles
        contenedorBoton.innerHTML = `<p>${data.message}</p>`;
    } else if (data.status === 'no_game') {
        // Si no hay partida creada
        contenedorBoton.innerHTML = `<p>${data.message}</p>`;
    } else {
        // En caso de error
        console.error(data.message);
    }
};

// Realiza la solicitud al backend
fetch('../php/getLastPlayed.php')
    .then((response) => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then((data) => manejarRespuesta(data))
    .catch((error) => {
        console.error('Error al obtener los datos:', error);
    });
