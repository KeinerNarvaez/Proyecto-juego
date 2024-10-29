document.addEventListener('DOMContentLoaded', function () {
    const socket = new WebSocket("ws://localhost:8080");

    // Consultar el alias al cargar la página
    fetch('./php/register_gamerTag.php', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        const aliasInput = document.getElementById('alias-usuario');
        aliasInput.value = data.gamerTag || 'Desconocido';
        conectarSocket(aliasInput.value);
    });

    function conectarSocket(gamerTag) {
        socket.onopen = () => {
            console.log("Connected");
            socket.send(JSON.stringify({
                message: 'Nuevo usuario conectado',
                gamerTag: gamerTag
            }));
        };

        socket.onmessage = (event) => {
            const data = JSON.parse(event.data);

            if (data.message === 'Nuevo usuario conectado') {
                // Agregar la tarjeta del nuevo usuario solo si es el propio
                if (data.sender) {
                    agregarPerfil(data.gamerTag); // Agrega la tarjeta del propio usuario
                } else {
                    agregarPerfil(data.gamerTag); // Agrega la tarjeta de otros usuarios
                }
            }

            // Manejar la lista de usuarios conectados
            if (data.message === 'Lista de usuarios') {
                // Opcional: limpiar y mostrar todos los perfiles
                document.getElementById('cuerpo-activos').innerHTML = ''; // Limpiar antes de mostrar
                data.users.forEach(user => agregarPerfil(user));
            }
        };

        socket.onclose = (event) => {
            if (!event.wasClean) console.log('Closed by server');
        };

        socket.onerror = (error) => console.error(error);
    }

    function agregarPerfil(gamerTag) {
        const clientId = `client-${Date.now()}`;
        let perfil = document.createElement('div');
        perfil.classList.add('perfil');
        perfil.id = clientId;
        perfil.innerHTML = `
            <h1 id="texto_perfil">${gamerTag}</h1>
            <img src="./Assest/personasActivas.png" class="fa-flip" id="activo_perfil" style="width: 20%; height:80%;" alt="">
        `;
        document.getElementById('cuerpo-activos').appendChild(perfil);
    }

    document.getElementById('alias-usuario').addEventListener('input', function () {
        const alias = this.value;
        const aliasMessage = document.getElementById('aliasMessage');
        const minLong = 5;

        if (alias.trim().length < minLong) {
            aliasMessage.textContent = `El alias debe tener al menos ${minLong} caracteres.`;
            aliasMessage.style.color = "red";
            return;
        } else {
            aliasMessage.textContent = "";
            fetch('./php/register_gamerTag.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ alias })
            })
            .then(response => response.json())
            .then(data => {
                aliasMessage.textContent = data.status === 'success' ? "Alias guardado correctamente" : "Error al guardar el alias";
                aliasMessage.style.color = data.status === 'success' ? "green" : "red";
                if (data.status === 'success') document.getElementById('alias-usuario').value = alias;
            })
            .catch(error => {
                console.error('Error en la solicitud:', error);
                aliasMessage.textContent = `Ocurrió un error al intentar guardar el alias. Detalles: ${error.message}`;
                aliasMessage.style.color = "red";
            });
        }
    });
});

