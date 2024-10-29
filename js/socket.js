const socket = new WebSocket("ws://localhost:8080");

socket.onopen = () => {
    console.log("Connected");
};

socket.onclose = (event) => {
    if (event.wasClean) {
        console.log('Closed by the client');
    } else {
        console.log('Closed by the server');
    }
};

socket.onerror = (error) => {
    console.error(error);
};

socket.onmessage = (event) => {
    let data = JSON.parse(event.data);
    if (data.mensajes) { // Asegúrate de que mensajes está definido
        let text = document.createElement('div');
        text.classList.add('other');
        text.innerText = data.mensajes;

        // Agregar el elemento al DOM
        document.getElementById('messages').appendChild(text);

        // Agregar el evento click al nuevo elemento
        text.addEventListener("click", e => {
            e.preventDefault();
            textoVoz(text.innerText);
        });
    }
};

document.getElementById('send').addEventListener('click', () => {
    let mensajes = document.getElementById('message').value;
    document.getElementById('message').value = ''; // Limpiar el campo de entrada

    let text = document.createElement('div');
    text.classList.add('me');
    text.innerText = mensajes;

    // Agregar el mensaje del usuario al DOM
    document.getElementById('messages').appendChild(text);

    // Enviar el mensaje a través del WebSocket
    socket.send(JSON.stringify({
        mensajes
    }));
});

function textoVoz(texto) {
    let utterance = new SpeechSynthesisUtterance(texto);
    window.speechSynthesis.speak(utterance); // Cambia Synth por window.speechSynthesis
}



