let tiempoRestante = 120; // 2 minutos en segundos

    const cronometro = document.getElementById('cronometro');

    const intervalo = setInterval(() => {
        const minutos = Math.floor(tiempoRestante / 60);
        const segundos = tiempoRestante % 60;

        // Formatear el tiempo
        cronometro.textContent = `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;

        if (tiempoRestante <= 0) {
            clearInterval(intervalo);
            alert("¡Se acabó el tiempo!");
        } else {
            tiempoRestante--;
        }
    }, 1000 /*milisegundos por si se me olvida*/);