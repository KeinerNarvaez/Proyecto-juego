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
        const imagen=[0,1,2];
        this.colorHallar=document.querySelector('.colorHallar');
        this.imagenes= Math.floor(Math.random()*imagen.length);
        this.colorGano="";
        this.colorAlreves="";
        this.colorAlreves2="";
        this.colorAlreves3="";
        this.colorAlreves4="";
        if (this.imagenes===0) {
            this.valorImagen = ['pocionAmarilla', 'pocionAnaranjado', 'pocionVerde', 'pocionRoja', 'pocionAzul'];
            this.colorGano="rgba(32, 64, 113, 0.96)"; //color gris azulado
            this.colorAlreves="rgba(64, 0, 113, 0.824)"; //rojo+ azul
            this.colorAlreves2="rgba(128, 0, 57, 0.824)"; //azul+rojo
            this.colorAlreves3="rgba(0, 128, 57, 0.824)"; //azul+verde
            this.colorAlreves4="rgba(0, 64, 113, 0.824)"; //verde+azul
            this.colorHallar.style.background="rgba(32, 64, 113, 1.1)";
        }else if (this.imagenes===1) {
            this.valorImagen = ['pocionAmarilla', 'pocionAzul', 'pocionRoja', 'pocionBlanca', 'pocionVerde'];
            this.colorGano="rgba(128, 192, 156, 0.96)"  //color aguamarina claro
            this.colorAlreves="rgba(0, 64, 113, 0.824)"; //verde + azul
            this.colorAlreves2="rgba(128, 192, 128, 0.824)"; //verde+ blanco
            this.colorAlreves3="rgba(128, 128, 184, 0.824)"; //azul+blanco
            this.colorAlreves4="rgba(64, 64, 177, 0.824)"; // blanco azul
            this.colorHallar.style.background='rgba(127, 255, 212, 1.1)'; 
        } else if(this.imagenes===2) {
            this.valorImagen = ['pocionAmarilla', 'pocionBlanca', 'pocionVerde', 'pocionAzul', 'pocionMorada']; //anaranjado+rojo+blanco
            this.colorAlreves="rgba(128, 192, 0, 0.824)";
            this.colorAlreves2="rgba(128, 128, 57, 0.824)";
            this.colorAlreves3="rgba(64, 64, 113, 0.824)";
            this.colorAlreves4="rgba(0, 64, 113, 0.824)";
            this.colorGano="rgba(128, 192, 29, 0.96)"; //color Verde oliva
            this.colorHallar.style.background="rgba(128, 192, 29, 1.1)";
        } else{
            console.log('error al cargar imagenes',error);
        }
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
        while (this.valorImagen.length) {
            const index = Math.floor(Math.random() * this.valorImagen.length);
            const div = document.createElement('div');
            div.className = 'item col-2';
            div.id = this.valorImagen[index];
            div.draggable = true;
            div.style.backgroundImage = `url('../Assest/${this.valorImagen[index]}.png')`;
            botella.appendChild(div);
            this.valorImagen.splice(index, 1);
        }
    }

    agregarEventos() {
        const botella = document.querySelector('#botellas');
        const colorBruja = document.querySelector('#arrastreBruja');
        const reiniciarMezclaBtn = document.getElementById('reiniciarMezcla');
        this.contador=0;

        botella.addEventListener('dragstart', e => {
            e.dataTransfer.setData('id', e.target.id);
        });

        colorBruja.addEventListener('dragover', e => {
            e.preventDefault();
        });

        colorBruja.addEventListener('drop', e => {
            const id = e.dataTransfer.getData('id');
            this.contador++

            this.procesarDrop(id);            
            if (this.contador === 4) {
                this.colorMezcla.reiniciar(); 
                console.log("funciona", this.contador);
                this.contador = 0;
            }
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
            'pocionAzul': [0, 0, 225, 1.1],
            'pocionBlanca':[255, 255, 255,1.1],
            'pocionMorada':[128,0,128,1.1]
            
        };

        const colorBotella = colores[id];
        this.colorMezcla.mezclar(colorBotella, this.colorMezcla.colorMezclado);
        if (this.imagenes===0) {
            if (id !== 'pocionVerde' && id !== 'pocionAzul' && id !== 'pocionRoja') {
            this.corazones.perderCorazon();
            setTimeout(() => this.colorMezcla.reiniciar(), 1000);
            }
        }else if (this.imagenes===1) {
            if (id !== 'pocionAzul' && id !== 'pocionVerde' && id !== 'pocionBlanca') {
                this.corazones.perderCorazon();
                setTimeout(() => this.colorMezcla.reiniciar(), 1000);
            }
        }else if (this.imagenes===2) {
            if (id !== 'pocionAzul' && id !== 'pocionVerde' && id !== 'pocionAmarilla') {
                this.corazones.perderCorazon();
                setTimeout(() => this.colorMezcla.reiniciar(), 1000);
            }
        }


        if (!this.corazones.estaVivo()) {
            this.perder();
        } else if (this.colorMezcla.colorMezclaElement.style.background === this.colorGano) {
            this.ganar();
        } else if (this.colorMezcla.colorMezclaElement.style.background === this.colorAlreves ||
            this.colorMezcla.colorMezclaElement.style.background === this.colorAlreves2 ||
            this.colorMezcla.colorMezclaElement.style.background === this.colorAlreves3 ||
            this.colorMezcla.colorMezclaElement.style.background === this.colorAlreves4) {
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
    
        let puntaje = (this.cronometro.minutos + this.cronometro.segundos) / this.corazones.corazonesRestantes;
        this.cronometro.detenerTiempo();
    
        let valorMinuto = this.cronometro.minutos;
        let valorSegundos = this.cronometro.segundos;
        const dataOperaciones = {
            currentScore: puntaje,
            result: 'win',  // Enviar 'win' si ganó el juego
            valorMinuto,
            valorSegundos
        };
    
        fetch('../php/pocionNivel5.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataOperaciones)
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error en los datos: ', data.error);
                puntuacionGano.innerHTML = 'Hubo un error al obtener los puntajes';
            } else {
                puntuacionGano.innerHTML = data.puntajes || "Error al obtener puntaje";
            }
        })
        .catch(error => {
            console.log('Error al enviar los datos:', error);
        });
    }
    
    
    
    
    perder() {
        this.cronometro.detenerTiempo();
        this.bruja.style.backgroundImage = 'url("../Assest/brujaPerdio.gif")';
        this.colorMezcla.reiniciar();
        this.puntaje();
    }
    
    puntaje() {
        let puntaje = (this.cronometro.minutos + this.cronometro.segundos) / this.corazones.corazonesRestantes;
        let valorMinuto = this.cronometro.minutos;
        let valorSegundos = this.cronometro.segundos;
    
        const datosAEnviar = {
            currentScore: puntaje,
            result: 'lose',  // Enviar 'lose' si el jugador pierde
            valorMinuto,
            valorSegundos
        };
    
        fetch('../php/pocionNivel5.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datosAEnviar)
        })
        .then(response => response.json())
        .then(data => {
            const print = data.puntajes || 0;
            // Muestra el puntaje en el frontend o realiza acciones necesarias
        })
        .catch(error => console.log('Error al enviar los datos:', error));
    }
}

new Juego();