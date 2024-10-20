document.addEventListener('DOMContentLoaded', function() {
    const socket = new WebSocket("ws://localhost:8080");
    // Consultar el alias al cargar la página
    fetch('./php/register_gamerTag.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Asignar el alias al input si existe
            const aliasInput = document.getElementById('alias-usuario');
            aliasInput.value = data.gamerTag;

            const clientId = `client-${Date.now()}`;

            socket.onopen = () => {
                console.log("Connected");
                // Crear el bloque de HTML del perfil
                let perfil = document.createElement('div');
                perfil.classList.add('perfil');
                perfil.id = clientId; // Usamos el clientId como identificador único
                perfil.innerHTML = `
                <h1 id="texto_perfil">${data.gamerTag}</h1>
                <img src="./Assest/personasActivas.png" class="fa-flip" id="activo_perfil" style="width: 20%; height:80%;" alt="">`;
                // Insertar el perfil en el elemento con id 'cuerpo-activos'
                document.getElementById('cuerpo-activos').appendChild(perfil);
};
        } else {
            const clientId = `client-${Date.now()}`;

            socket.onopen = () => {
             console.log("Connected");

    // Crear el bloque de HTML del perfil
            let perfil = document.createElement('div');
            perfil.classList.add('perfil');
            perfil.id = clientId; // Usamos el clientId como identificador único
            perfil.innerHTML = `
            <h1 id="texto_perfil">Desconocido</h1>
            <img src="./Assest/personasActivas.png" class="fa-flip" id="activo_perfil" style="width: 20%; height:80%;" alt="">`;

    // Insertar el perfil en el elemento con id 'cuerpo-activos'
        document.getElementById('cuerpo-activos').appendChild(perfil);
};
        }

// Generar un ID único para cada cliente para identificar el perfil


socket.onclose = (event) => {
    if (event.wasClean) {
        console.log('Closed by the client');
    } else {
        console.log('Closed by the server');
    }

    // Eliminar el bloque de HTML del perfil al desconectarse
    let perfil = document.getElementById(clientId);
    if (perfil) {
        perfil.remove(); // Eliminar el perfil del DOM
    }
};

socket.onerror = (error) => {
    console.error(error);
};

});
});

document.getElementById('alias-usuario').addEventListener('input', function() {
    const alias = this.value;
    const aliasMessage = document.getElementById('aliasMessage');

    // Definir la longitud mínima del alias
    const longitudMinima = 5;

    // Verificar si el alias no está vacío y cumple con la longitud mínima
    if (alias.trim().length < longitudMinima) {
        aliasMessage.textContent = `El alias debe tener al menos ${longitudMinima} caracteres.`;
        aliasMessage.style.color = "red";
        return;
    } else {
        // Limpiar el mensaje si la longitud es suficiente
        aliasMessage.textContent = "";

        // Enviar alias al servidor usando fetch
        fetch('./php/register_gamerTag.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ alias })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                aliasMessage.textContent = "Alias guardado correctamente";
                aliasMessage.style.color = "green";

                // Si se guardó correctamente, actualizar el valor en el input
                document.getElementById('alias-usuario').value = alias;
            } else {
                aliasMessage.textContent = "Error al guardar el alias";
                aliasMessage.style.color = "red";
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
            aliasMessage.textContent = "Ocurrió un error al intentar guardar el alias. Detalles: " + error.message;
            aliasMessage.style.color = "red";
        });
    }
});




