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
            // Enviar un mensaje para registrar este usuario
            socket.send(JSON.stringify({
                message: 'Nuevo usuario conectado',
                gamerTag: gamerTag
            }));
        };

        socket.onmessage = (event) => {
            const data = JSON.parse(event.data);

            // Si el mensaje es 'Nuevo usuario conectado', manejarlo adecuadamente
            if (data.message === 'Nuevo usuario conectado') {
                // Si el mensaje es para este cliente, agrega su perfil
                if (data.gamerTag === gamerTag) {
                    agregarPerfil(data.gamerTag); // Agrega la tarjeta del propio usuario
                }
            }

            // Cuando el servidor envíe la lista de usuarios activos
            if (data.message === 'Lista de usuarios') {
                // Limpiar el contenedor de perfiles antes de mostrar los nuevos
                const cuerpoActivos = document.getElementById('personasConectadas');
                cuerpoActivos.innerHTML = ''; // Limpiar el contenedor de perfiles
                
                // Agregar todos los perfiles de usuarios conectados
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

    // Función para agregar perfiles al contenedor
    function agregarPerfil(gamerTag) {
        const clientId = `client-${Date.now()}`;
        let perfil = document.createElement('div');
        perfil.classList.add('perfil');
        perfil.id = clientId;
        perfil.innerHTML = `
            <div class="perfil-jugador">
                <i class="fa-solid fa-user"></i>
                <h1>${gamerTag}</h1>
            </div>
        `;
        document.getElementById('personasConectadas').appendChild(perfil);
    }
});

