document.addEventListener('DOMContentLoaded', () => {
    const historyContainer = document.getElementById('history-container'); // Asegúrate de tener un div con este ID.

    // Función para obtener el historial
    const fetchHistory = async () => {
        try {
            const response = await fetch('../php/getHistory.php', { method: 'GET' });
            const data = await response.json();

            if (data.status === 'success') {
                renderHistory(data.data);
            } else {
                console.error(data.message);
                historyContainer.innerHTML = `<p>${data.message}</p>`;
            }
        } catch (error) {
            console.error('Error fetching history:', error);
            historyContainer.innerHTML = `<p>Error al cargar el historial.</p>`;
        }
    };

    // Renderizar el historial con formato <div class="informe">
    const renderHistory = (history) => {
        historyContainer.innerHTML = `
            <div class="informe">
                <h3>Mejor puntaje: ${history.bestScore}</h3>
                <h3>Veces completado: ${history.timesCompleted}</h3>
                <h3>Veces derrotado: ${history.timesDefeated}</h3>
            </div>
        `;
    };

    fetchHistory(); // Llamar al historial al cargar la página
});
