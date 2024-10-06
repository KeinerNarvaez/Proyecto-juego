const imagenes = [
    'pocionAmarilla', 'pocionAnaranjado', 'pocionVerde',
    'pocionRoja', 'pocionMorada'
];
let tamañoBruja=document.querySelector('.tamañoBruja')
const bruja = document.querySelector('.bruja');
let colorMezcla = document.getElementById('colorMezcla');
let botella = document.querySelector('#botellas');
let colorBruja = document.querySelector('#arrastreBruja');

let terminado = imagenes.length;

while (imagenes.length) {
    const index = Math.floor(Math.random() * imagenes.length);
    const div = document.createElement('div');
    div.className = 'item col-2';
    div.id = imagenes[index];
    div.draggable = true;
    div.style.backgroundImage = `url('../Assest/`+imagenes[index]+`.png')`;
    botella.appendChild(div);
    imagenes.splice(index, 1);
}

for (let i = 0; i < terminado; i++) {
    const div = document.createElement('div');
    div.className = 'placeholder';
    div.dataset.id = i;
    colorBruja.appendChild(div);
}

botella.addEventListener('dragstart', e => {
    e.dataTransfer.setData('id', e.target.id);
});

colorBruja.addEventListener('dragover', e => {
    e.preventDefault();
    console.log('drag enter');
});

let colorMezclado =[0,0,0,0];
let corazonesRestantes = 3; 


function mostrarCorazones() {
    const corazonesContainer = document.querySelector('.corazones');
    corazonesContainer.innerHTML = ''; 
    for (let i = 0; i < corazonesRestantes; i++) {
        const corazonDiv = document.createElement('div');
        corazonDiv.className = 'corazon';
        corazonDiv.innerHTML = '<img src="../Assest/corazon.png" alt="">';
        corazonesContainer.appendChild(corazonDiv);
    }
}
function ganar() {
    let ganas= new bootstrap.Modal(document.getElementById('ganas'), {
            backdrop: 'static', // Evita que se cierre al hacer clic fuera del modal
            keyboard: false     // Desactiva el cierre con la tecla ESC
    });
    return ganas.show();
}

colorBruja.addEventListener('drop', e => {
const id = e.dataTransfer.getData('id');
    const colores = {
        'pocionAmarilla': [255, 255, 0, 1.1],  
        'pocionAnaranjado': [255, 165, 0, 1.1],  
        'pocionVerde': [0, 255, 0, 1.1],  
        'pocionRoja': [255, 0, 0, 1.1],  
        'pocionMorada': [0, 0, 225, 1.1] 
    };
    function mezcladorDeColores(color1, color2) {
        const rMezclado = Math.round((color1[0] + color2[0]) / 2); 
        const gMezclado = Math.round((color1[1] + color2[1]) / 2);
        const bMezclado = Math.round((color1[2] + color2[2]) / 2); 
        const aMezclado = (color1[3] + color2[3]) / 2;
        return [rMezclado, gMezclado, bMezclado, aMezclado]; 
    }
    const colorBotella = colores[id];    

    colorMezclado=mezcladorDeColores(colorBotella,colorMezclado);
    colorMezcla.style.background = 'rgba('+colorMezclado[0]+','+colorMezclado[1]+','+colorMezclado[2]+','+colorMezclado[3]+')';

    if (id !== 'pocionRoja' && id !== 'pocionMorada') {
        corazonesRestantes--;
        mostrarCorazones();

    }if (corazonesRestantes === 0) {            
            reinicioColor()
            perdio()
            clearInterval(intervalo); 
            setTimeout(paginaPerdio,4000);
    } else if (colorMezcla.style.background=='rgba(64, 0, 113, 0.824)') {
        ganar()
        clearInterval(intervalo); 
    }
    if (colorMezcla.style.background=='rgba(128, 0, 57, 0.824)') {
        avisoOrdenColor()
    }
    
});
function avisoOrdenColor() {
    let errorOrden = new bootstrap.Toast(document.getElementById('errorOrden'));
    return errorOrden.show();  
}
function paginaPerdio() {
    window.location.href = './perdio.html';
}

function perdio() {
    return bruja.style.backgroundImage = 'url("../Assest/brujaPerdio.gif")';
}
function reinicioColor() {
    colorMezcla.style.background='rgba(0,0,0,0)';
    colorMezclado=[0,0,0,0];
}


mostrarCorazones();


const toastTrigger = document.getElementById('liveToastBtn')
const toastLiveExample = document.getElementById('liveToast')

if (toastTrigger) {
  const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
  toastTrigger.addEventListener('click', () => {
    toastBootstrap.show()
  })
}
