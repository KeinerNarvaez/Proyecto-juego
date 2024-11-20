document.addEventListener('DOMContentLoaded', function () { 
    const socket = new WebSocket("ws://localhost:8080"); 
    // Consultar el alias al cargar la página 
    fetch('./php/register_gamerTag.php', { 
        method: 'GET', 
        headers: { 'Content-Type': 'application/json' } 
    })
    .then(response => response.json()) 
    .then(data => { 
        const usuario = data.gamerTag; 
        conectarSocket(usuario); 
    });

    function conectarSocket(gamerTag) { 
        socket.onopen = () => { 
            console.log("Conectado al servidor WebSocket"); 
            // Enviar solo el mensaje de conexión una vez
            socket.send(JSON.stringify({ 
                message: 'Nuevo usuario conectado', 
                gamerTag: gamerTag 
            })); 
        };

        socket.onmessage = (event) => { 
            const data = JSON.parse(event.data); 

            if (data.message === 'Nuevo usuario conectado' && data.gamerTag === gamerTag) { 
                agregarPerfil(data.gamerTag); // Agrega la tarjeta del propio usuario 
            } 

            if (data.message === 'Lista de usuarios') { 
                const cuerpoActivos = document.getElementById('personasConectadas'); 
                cuerpoActivos.innerHTML = ''; // Limpiar el contenedor de perfiles 
                
                data.users.forEach(user => { 
                    agregarPerfil(user); 
                }); 
            } 
        }; 

        socket.onclose = (event) => { 
            if (!event.wasClean) console.log('Conexión cerrada por el servidor'); 
        }; 

        socket.onerror = (error) => console.error(error); 
    } 


});
