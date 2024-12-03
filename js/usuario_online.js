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
            body: JSON.stringify(), // Si no necesitas enviar datos, puedes dejarlo vacío
        })
        .then(response => response.json())
        .then(data => {
            console.log('Datos recibidos:', data);
            data.forEach(item => {
                console.log('GamerTag:', item.gamerTag);
                console.log('RoomCode:', item.roomCode);

                // Enviar el mensaje al WebSocket solo después de que la conexión esté abierta
                socket.send(JSON.stringify({ 
                    message: 'Nuevo usuario online', 
                    roomCode: item.roomCode,
                    gamerTag: item.gamerTag
                }));
            });
        })
        .catch(error => console.error('Error:', error));
    };

    // Configurar el error y cierre del WebSocket
    socket.onerror = (error) => {
        console.error("Error en la conexión WebSocket:", error);
    };

    socket.onclose = () => {
        console.log("Conexión WebSocket cerrada");
    };
});


