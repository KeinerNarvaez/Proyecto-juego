let gamerTag = null;
let roomCode = null;

window.addEventListener('DOMContentLoaded', () => {
    // Crear la conexión WebSocket solo una vez
    const socket = new WebSocket("ws://localhost:8080");
    
    // Configurar el evento onopen para el WebSocket
    socket.onopen = () => {
        console.log("Conectado al servidor WebSocket");

        // Obtener información del usuario desde el servidor
        fetch('./php/sala.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            console.log('Datos recibidos:', data);

            // Verificar si se obtuvieron datos
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(item => {
                    console.log('GamerTag:', item.gamerTag);
                    console.log('RoomCode:', item.roomCode);

                    // Actualizar las variables globales
                    gamerTag = item.gamerTag;
                    roomCode = item.roomCode;

                    // Enviar el mensaje al WebSocket
                    if (socket.readyState === WebSocket.OPEN) {
                        socket.send(JSON.stringify({ 
                            message: 'Nuevo usuario online', 
                            roomCode: item.roomCode,
                            gamerTag: item.gamerTag
                        }));
                    } else {
                        console.error('WebSocket no está abierto.');
                    }
                });
            } else {
                console.log('No se encontraron usuarios');
            }
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error);
        });
    };

    // Evento para manejar la desconexión
    window.addEventListener("beforeunload", () => {
        if (socket.readyState === WebSocket.OPEN && gamerTag && roomCode) {
            socket.send(JSON.stringify({
                message: 'Usuario desconectado',
                gamerTag: gamerTag,
                roomCode: roomCode
            }));
        }
        socket.close();
    });

    // Manejo de errores y cierre de conexión
    socket.onerror = (error) => {
        console.error("Error en la conexión WebSocket:", error);
    };

    socket.onclose = () => {
        console.log("Conexión WebSocket cerrada");
    };
});




