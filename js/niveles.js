document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('niveles-container');

    // Función para obtener niveles
    const fetchNiveles = async () => {
        try {
            const response = await fetch('../php/get_niveles.php', { method: 'GET' });
            const data = await response.json();

            if (data.status === 'success') {
                renderNiveles(data.data);
            } else {
                console.error(data.message);
            }
        } catch (error) {
            console.error('Error fetching niveles:', error);
        }
    };

    // Renderizar niveles
    const renderNiveles = (niveles) => {
        container.innerHTML = '';

        niveles.forEach((nivel) => {
            const nivelDiv = document.createElement('div');
            nivelDiv.classList.add('partidas');

            nivelDiv.innerHTML = `
                <h1>Nivel ${nivel.nivel}</h1>
                <h3>${nivel.modo}</h3>
                <img id="delete-${nivel.onlyID}" src="../Assest/papelera-de-reciclaje.png" alt="Eliminar nivel" style="cursor: pointer;">
            `;

            // Evento para eliminar nivel
            nivelDiv.querySelector(`#delete-${nivel.onlyID}`).addEventListener('click', () => {
                deleteNivel(nivel.onlyID);
            });

            container.appendChild(nivelDiv);
        });
    };

    // Función para eliminar nivel
    const deleteNivel = async (onlyID) => {
        try {
            const response = await fetch('../php/delete_nivel.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ onlyID }),
            });

            const data = await response.json();

            if (data.status === 'success') {
                fetchNiveles();
            } else {
                console.error(data.message);
            }
        } catch (error) {
            console.error('Error deleting nivel:', error);
        }
    };

    fetchNiveles(); // Cargar niveles al inicio
});
