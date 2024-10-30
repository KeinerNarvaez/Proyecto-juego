document.addEventListener("DOMContentLoaded", function() {
  const backgroundMusic = document.getElementById('backgroundMusic');

  // Carga volumen y tiempo de reproducción
  const savedVolume = localStorage.getItem('musicVolume') || 1.0;
  const savedTime = localStorage.getItem('musicTime') || 0;

  backgroundMusic.volume = parseFloat(savedVolume);
  backgroundMusic.currentTime = parseFloat(savedTime);
  backgroundMusic.play();

  // Guarda el tiempo de reproducción actual antes de salir de la página
  window.addEventListener('beforeunload', () => {
      localStorage.setItem('musicTime', backgroundMusic.currentTime);
      localStorage.setItem('musicVolume', backgroundMusic.volume);
  });

  // Ajuste de volumen con el slider (en caso de que esté en la página)
  const volumeSlider = document.getElementById('sliderEfecto');
  if (volumeSlider) {
      volumeSlider.value = savedVolume * 100;
      volumeSlider.addEventListener('input', (e) => {
          const volume = e.target.value / 100;
          backgroundMusic.volume = volume;
          localStorage.setItem('musicVolume', volume);
      });
  }
});
