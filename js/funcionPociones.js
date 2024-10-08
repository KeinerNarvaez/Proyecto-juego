class Cronometro {
    constructor(minutos, segundos, callback) {
        this.minutos = minutos;
        this.segundos = segundos;
        this.callback = callback;
        this.intervalo = null;
        this.elemento = document.getElementById('cronometro');
    }

    tiempo() {
        if (this.segundos < 10) {
            this.elemento.innerHTML = `${this.minutos}:0${this.segundos}`;
        } else {
            this.elemento.innerHTML = `${this.minutos}:${this.segundos}`;
        }
        if (this.minutos === 0 && this.segundos === 0) {
            clearInterval(this.intervalo);
            this.callback();
        } else if (this.segundos === 0) {
            this.minutos--;
            this.segundos = 59;
        } else {
            this.segundos--;
        }
    }

    iniciar() {
        this.intervalo = setInterval(() => {
            this.tiempo();
        }, 1000);
    }



    detenerTiempo() {
        clearInterval(this.intervalo);
    }
}

class Corazon {
    constructor() {
        this.corazonesRestantes = 3;
        this.corazonesContainer = document.querySelector('.corazones');
    }

    mostrar() {
        this.corazonesContainer.innerHTML = '';
        for (let i = 0; i < this.corazonesRestantes; i++) {
            const corazonDiv = document.createElement('div');
            corazonDiv.className = 'corazon';
            corazonDiv.innerHTML = '<img src="../Assest/corazon.png" alt="">';
            this.corazonesContainer.appendChild(corazonDiv);
        }
    }

    perderCorazon() {
        if (this.corazonesRestantes > 0) {
            this.corazonesRestantes--;
            this.mostrar();
        }
    }

    estaVivo() {
        return this.corazonesRestantes > 0;
    }
}

class ColorMezcla {
    constructor() {
        this.colorMezclado = [0, 0, 0, 0];
        this.colorMezclaElement = document.getElementById('colorMezcla');
    }

    mezclar(color1, color2) {
        const rMezclado = Math.round((color1[0] + color2[0]) / 2);
        const gMezclado = Math.round((color1[1] + color2[1]) / 2);
        const bMezclado = Math.round((color1[2] + color2[2]) / 2);
        const aMezclado = (color1[3] + color2[3]) / 2;
        this.colorMezclado = [rMezclado, gMezclado, bMezclado, aMezclado];
        this.actualizarColor();
    }

    actualizarColor() {
        this.colorMezclaElement.style.background = `rgba(${this.colorMezclado[0]}, ${this.colorMezclado[1]}, ${this.colorMezclado[2]}, ${this.colorMezclado[3]})`;
    }

    reiniciar() {
        this.colorMezclado = [0, 0, 0, 0];
        this.actualizarColor();
    }
    
}

class Juego {
    constructor() {
        this.imagenes = ['pocionAmarilla', 'pocionAnaranjado', 'pocionVerde', 'pocionRoja', 'pocionAzul'];
        this.cronometro = new Cronometro(1, 59, () => this.perder());
        this.corazones = new Corazon();
        this.colorMezcla = new ColorMezcla();
        this.bruja = document.querySelector('.bruja');
        this.iniciar();
    }

    iniciar() {
        this.cronometro.iniciar();
        this.corazones.mostrar();
        this.crearBotellas();
        this.agregarEventos();
    }

    crearBotellas() {
        const botella = document.querySelector('#botellas');
        while (this.imagenes.length) {
            const index = Math.floor(Math.random() * this.imagenes.length);
            const div = document.createElement('div');
            div.className = 'item col-2';
            div.id = this.imagenes[index];
            div.draggable = true;
            div.style.backgroundImage = `url('../Assest/${this.imagenes[index]}.png')`;
            botella.appendChild(div);
            this.imagenes.splice(index, 1);
        }
    }

    agregarEventos() {
        const botella = document.querySelector('#botellas');
        const colorBruja = document.querySelector('#arrastreBruja');
        const reiniciarMezclaBtn = document.getElementById('reiniciarMezcla');

        botella.addEventListener('dragstart', e => {
            e.dataTransfer.setData('id', e.target.id);
        });

        colorBruja.addEventListener('dragover', e => {
            e.preventDefault();
        });

        colorBruja.addEventListener('drop', e => {
            const id = e.dataTransfer.getData('id');
            this.procesarDrop(id);
        });
        reiniciarMezclaBtn.addEventListener('click', () => {
            this.colorMezcla.reiniciar(); 
        });
    }

    procesarDrop(id) {
        const colores = {
            'pocionAmarilla': [255, 255, 0, 1.1],
            'pocionAnaranjado': [255, 165, 0, 1.1],
            'pocionVerde': [0, 255, 0, 1.1],
            'pocionRoja': [255, 0, 0, 1.1],
            'pocionAzul': [0, 0, 225, 1.1]
        };

        const colorBotella = colores[id];
        this.colorMezcla.mezclar(colorBotella, this.colorMezcla.colorMezclado);

        if (id !== 'pocionRoja' && id !== 'pocionAzul') {
            this.corazones.perderCorazon();
            setTimeout(() => this.colorMezcla.reiniciar(), 1000);
        }

        if (!this.corazones.estaVivo()) {
            this.perder();
        } else if (this.colorMezcla.colorMezclaElement.style.background === 'rgba(64, 0, 113, 0.824)') {
            this.ganar();
        } else if (this.colorMezcla.colorMezclaElement.style.background === 'rgba(128, 0, 57, 0.824)') {
            this.avisoOrdenColor();
        }
    }

    ganar() {
        const ganas = new bootstrap.Modal(document.getElementById('ganas'), {
            backdrop: 'static',
            keyboard: false
        });
        
        const puntuacionGano = document.getElementById('puntaje-ganado');       
        ganas.show();
        let puntaje =(this.cronometro.minutos + this.cronometro.segundos)/this.corazones.corazonesRestantes;
        this.puntajes=puntaje
        console.log(this.minutos)
        this.cronometro.detenerTiempo();

        let valorMinuto = this.cronometro.minutos;
        let valorSegundos = this.cronometro.segundos;
        const datosAEnviar = {
            puntaje,
            valorMinuto,
            valorSegundos
        };
    
        fetch('../php/datosJuegos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datosAEnviar)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor: ' + response.status);
            }
            return response.json();
        }).then(data => {
            console.log(data);
            puntuacionGano.innerHTML = "Puntos: " + data.puntaje; 
        })
    }

    perder() {
        this.cronometro.detenerTiempo();
        this.bruja.style.backgroundImage = 'url("../Assest/brujaPerdio.gif")';
        this.colorMezcla.reiniciar();
        this.puntaje();
    }

    avisoOrdenColor() {
        const errorOrden = new bootstrap.Toast(document.getElementById('errorOrden'));
        errorOrden.show();
    }



    puntaje() {   
        let puntaje = (this.corazones.corazonesRestantes * 100) - (this.cronometro.minutos * 60 + this.cronometro.segundos);
        let valorMinuto = this.cronometro.minutos;
        let valorSegundos = this.cronometro.segundos;
        this.puntajes=puntaje;
        const datosAEnviar = {
            puntaje,
            valorMinuto,
            valorSegundos
        };
    
        fetch('../php/datosJuegos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datosAEnviar)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            const print = data[0]?.puntajes || 0; 
            const valorPuntaje = document.getElementById('valorPuntaje');
            valorPuntaje.innerHTML = 'Tus puntos: ' + print;
    
            const puntos = new bootstrap.Toast(document.getElementById('puntajes'));
            puntos.show(); 
        })
        .catch(error => {
            console.error('Error al enviar los datos:', error);
        });
    }
}

new Juego();
