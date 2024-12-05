document.addEventListener('DOMContentLoaded', () => {
    // Obtener el userID desde el servidor
    fetch('../php/getUserID.php')
        .then(response => response.json())
        .then(data => {
            if (data.userID) {
                // Aquí tienes el userID
                const userId = data.userID;

                // Los botones para seleccionar el modo de juego
                const modeButtons = document.querySelectorAll('a[data-game-mode-id]');

                modeButtons.forEach(button => {
                    button.addEventListener('click', event => {
                        event.preventDefault(); // Prevenir la navegación inmediata

                        const gameModeID = button.getAttribute('data-game-mode-id');

                        const requestData = {
                            gameModeID: parseInt(gameModeID),
                            userID: parseInt(userId) // Usar el userID obtenido
                        };

                        fetch('../php/seleccionSolitario.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(requestData)
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.status === 'success') {
                                console.log("Selección registrada con éxito:", result.message);
                                window.location.href = button.getAttribute('href');
                            } else {
                                console.error("Error al procesar la selección:", result.message);
                            }
                        })
                        .catch(error => {
                            console.error("Error en la solicitud al servidor:", error);
                        });
                    });
                }
            )} else {
                console.error("No se pudo obtener el userID: ", data.error);
            }
        })
        .catch(error => {
            console.error("Error al obtener el userID:", error);
        });
});
