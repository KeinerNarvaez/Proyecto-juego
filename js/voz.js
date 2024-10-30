document.addEventListener('DOMContentLoaded', () => {
    const btnContainer = document.getElementById('voz');
    const textArea = document.getElementById('message');

    const recognition = new webkitSpeechRecognition();
    recognition.continuous = true;
    recognition.lang = 'es-ES';
    recognition.interimResults = false;

    // Delegación de eventos: escucha clics en el contenedor del botón
    btnContainer.addEventListener('click', (event) => {
        if (event.target.closest('#vozActivo')) {  // Si el clic es en el botón de activar
            recognition.start();
            btnContainer.innerHTML = `<button class="voz" style="background-color: rgb(209, 33, 33);position: absolute;width: 9.6%;height:5%;" type="button" id="vozDesactivo"><i class="fa-solid fa-microphone-slash" style="color: #ffffff;"></i></button>`;
        } else if (event.target.closest('#vozDesactivo')) {  // Si el clic es en el botón de desactivar
            recognition.abort();
            btnContainer.innerHTML = `<button class="voz" style="background-color: rgb(33, 209, 33);position: absolute;width: 9.6%;height:5%;" type="button" id="vozActivo"><i class="fa-solid fa-microphone" style="color: #ffffff;"></i></button>`;
        }
    });

    recognition.onresult = (event) => {
        const texto = event.results[event.results.length - 1][0].transcript;
        textArea.value = texto;
    };
});


