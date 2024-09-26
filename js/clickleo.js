// Selecciona todos los botones con la clase "text-boton"
const buttons = document.querySelectorAll(".text-boton");
// Selecciona el elemento de audio
const audio = document.getElementById("clickSound");

// Añade el evento "click" a cada botón
buttons.forEach(button => {
    button.addEventListener("click", (event) => {
        audio.play(); // Reproduce el sonido
        
        // Si el botón tiene un enlace (como en el caso de JUGAR)
        if (button.getAttribute("href")) {
            event.preventDefault(); // Evita la redirección inmediata
            setTimeout(() => {
                window.location.href = button.getAttribute("href"); // Redirige después de un pequeño retraso
            }, 300); // 300ms de retraso para que el sonido se reproduzca
        }
    });
});




