let minutos = 1; 
let segundos = 59; 
const cronometro = document.getElementById('cronometro');
    const intervalo = setInterval(() => {
    if (segundos<10) {
        cronometro.innerHTML = minutos + ':0' + segundos;
    }else{
        cronometro.innerHTML = minutos + ':' + segundos;
    }
    if (minutos === 0 && segundos === 0) {
        reinicioColor()
        perdio()
        clearInterval(intervalo); 
        setTimeout(paginaPerdio,4400);
    } else if (segundos === 0) {
        minutos--;
        segundos = 59;
    } else {
        segundos--;
    }
}, 1000);



