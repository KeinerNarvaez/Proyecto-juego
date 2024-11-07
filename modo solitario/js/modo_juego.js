document.querySelectorAll(".text-boton").forEach(button => {
    button.addEventListener("click", function (e) {
        e.preventDefault();  // Evita la navegación inmediata

        const gameModeID = this.getAttribute("data-game-mode-id"); // Captura el ID de modo de juego

        // Envía los datos al servidor
        fetch("../php/register_modo_juego.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ gameModeID })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Modo de juego guardado exitosamente.");
                window.location.href = this.getAttribute("href"); // Navega después de guardar
            } else {
                console.error("Error al guardar el modo de juego:", data.message);
            }
        })
        .catch(error => console.error("Error en la petición:", error));
    });
});
