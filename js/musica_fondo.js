document.addEventListener("DOMContentLoaded", function () {
    // Selecciona el audio de fondo
    const backgroundMusic = document.getElementById('backgroundMusic');

    if (!backgroundMusic) {
        console.error("No se encontró el elemento de música de fondo con el ID 'backgroundMusic'.");
        return;
    }

    // Recuperar ajustes guardados (volumen y tiempo)
    const savedVolume = parseFloat(localStorage.getItem('musicVolume')) || 1.0;
    const savedTime = parseFloat(localStorage.getItem('musicTime')) || 0;

    backgroundMusic.volume = savedVolume;
    backgroundMusic.currentTime = savedTime;

    // Manejo de reproducción con compatibilidad para navegadores
    backgroundMusic.play().catch(error => {
        console.warn("La reproducción automática está bloqueada. Requiere interacción del usuario.", error);
    });

    // Guardar el estado de la música antes de abandonar la página
    window.addEventListener('beforeunload', () => {
        localStorage.setItem('musicTime', backgroundMusic.currentTime);
        localStorage.setItem('musicVolume', backgroundMusic.volume);
    });

    // Control deslizante de volumen (si está presente)
    const volumeSlider = document.getElementById('sliderEfecto');
    if (volumeSlider) {
        volumeSlider.value = savedVolume * 100; // Ajustar el slider al volumen actual
        volumeSlider.addEventListener('input', (e) => {
            const volume = e.target.value / 100;
            backgroundMusic.volume = volume;
            localStorage.setItem('musicVolume', volume); // Guardar el volumen actualizado
        });
    }

    // Mensaje de inicio para confirmar que la música está lista
    console.log("Música de fondo inicializada correctamente.");
});
