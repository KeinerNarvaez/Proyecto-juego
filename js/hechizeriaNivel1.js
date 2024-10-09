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

    }

    actualizarColor() {

    }

    reiniciar() {

    }
    
}

class Juego {
    constructor() {
        const imagen=[0,1,2];
        const objetivo=document.getElementById('objetivo');
        this.imagenes= Math.floor(Math.random()*imagen.length);
        if (this.imagenes===0) {
            this.valorImagen = ['cuervo', 'colmillo', 'fenix', 'mano', 'ojo'];
            objetivo.innerHTML="Elixir de inmortalidad";
        }else if (this.imagenes===1) {
            this.valorImagen = ['cuervo', 'colmillo', 'fenix', 'mano', 'patas']; 
        } else if(this.imagenes===2) {
            this.valorImagen = ['cuervo', 'colmillo', 'fenix', 'uña', 'ojo'];
        } else{
            console.log('error al cargar imagenes');
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
        this.crearItem();
        this.agregarEventos();
    }

    crearItem() {
        const botella = document.querySelector('#item');
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
        const botella = document.querySelector('#item');
        const colorBruja = document.querySelector('#arrastreBruja');
        this.contador=0;

        botella.addEventListener('dragstart', e => {
            e.dataTransfer.setData('id', e.target.id);
        });

        colorBruja.addEventListener('dragover', e => {
            e.preventDefault();
        });

        if (this.imagenes===0 && id !=='fenix' && id !=='colmillo') {
            
        }
        colorBruja.addEventListener('drop', e => {
            const id = e.dataTransfer.getData('id');
            this.contador++
        });
    }

    procesarDrop(id) {
        if (this.imagenes===0) {
            if (id !== 'fenix' && id !== 'colmillo') {
            this.corazones.perderCorazon();
            setTimeout(() => this.colorMezcla.reiniciar(), 1000);
            }
        }else if (this.imagenes===1) {
            if (id !== 'pocionAmarilla' && id !== 'pocionVerde') {
                this.corazones.perderCorazon();
                setTimeout(() => this.colorMezcla.reiniciar(), 1000);
            }
        }else if (this.imagenes===2) {
            if (id !== 'pocionRoja' && id !== 'pocionVerde') {
                this.corazones.perderCorazon();
                setTimeout(() => this.colorMezcla.reiniciar(), 1000);
            }
        }


        if (!this.corazones.estaVivo()) {
            this.perder();
        } else if (this.colorMezcla.colorMezclaElement.style.background === this.colorGano) {
            this.ganar();
        } else if (this.colorMezcla.colorMezclaElement.style.background === this.colorAlreves) {
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
        this.cronometro.detenerTiempo();

        let valorMinuto = this.cronometro.minutos;
        let valorSegundos = this.cronometro.segundos;
        const dataOperaciones = {
            puntaje,
            valorMinuto,
            valorSegundos
        };
    
        fetch('../php/datosJuegos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataOperaciones)
        })
        .then(response => response.json())
        .then(data => {
            let puntajePrint = "";   
            // Recorremos los datos que vienen del servidor
            data.forEach(oper => {
                puntajePrint = oper.puntajes
            });
            puntuacionGano.innerHTML = puntajePrint ;
        })
        .catch(error => {
            console.log(error);
        });
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
        this.colorMezcla.reiniciar(); 
    }



    puntaje() {   
        let puntaje =(this.cronometro.minutos + this.cronometro.segundos)/this.corazones.corazonesRestantes;
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
    
            const puntos = new bootstrap.Modal(document.getElementById('puntajes'), {
                backdrop: 'static',
                keyboard: false
            });    
            puntos.show();
        })
        .catch(error => {
            console.error('Error al enviar los datos:', error);
        });
    }
}

new Juego();