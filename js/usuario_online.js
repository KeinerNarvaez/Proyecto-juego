window.addEventListener('DOMContentLoaded', () => {
    // Crear la conexión WebSocket solo una vez
    const socket = new WebSocket("ws://localhost:8080");

    // Configurar el evento onopen para el WebSocket
    socket.onopen = () => {
        console.log("Conectado al servidor WebSocket");

        // Ahora que la conexión está abierta, se puede proceder a enviar mensajes
        fetch('./php/sala.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            // Si no necesitas enviar datos, elimina la propiedad body
            body: JSON.stringify({}) // O simplemente elimina esta línea si no es necesario
        })
        .then(response => response.json())
        .then(data => {
            console.log('Datos recibidos:', data);

            // Verificar si se obtuvieron datos
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(item => {
                    console.log('GamerTag:', item.gamerTag);
                    console.log('RoomCode:', item.roomCode);

                    // Enviar el mensaje al WebSocket solo después de que la conexión esté abierta
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

    window.addEventListener("beforeunload", () => {
        if (socket.readyState === WebSocket.OPEN && gamerTag) {
            socket.send(JSON.stringify({
                message: 'Usuario desconectado',
                gamerTag: gamerTag
            }));
        }
        socket.close();
    });


    socket.onerror = (error) => {
        console.error("Error en la conexión WebSocket:", error);
    };

    socket.onclose = () => {
        console.log("Conexión WebSocket cerrada");
    };
});



