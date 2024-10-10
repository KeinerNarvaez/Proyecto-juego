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

class Juego {
    constructor() {
        const imagen=[0,1,2];
        const objetivo=document.getElementById('objetivo');
        this.sub1=document.getElementById('sub1');
        this.sub2=document.getElementById('sub2');
        this.imagenes= Math.floor(Math.random()*imagen.length);
        if (this.imagenes===0) {
            this.valorImagen = ['cuervo', 'uña', 'patas', 'mano', 'ojo'];
            objetivo.innerHTML="Poción del Poder Bestial";
            this.sub1.innerHTML="1. Fuerte y temido, en la sombra acecho.";
            this.sub2.innerHTML="2. En el charco me escondo, con saltos y brincos.";
        }else if (this.imagenes===1) {
            this.valorImagen = ['cuervo', 'patas', 'fenix', 'mano', 'uña']; 
            objetivo.innerHTML="Poción del Tiempo";
            this.sub1.innerHTML="1. De la muerte vengo, buscando lo que se ha perdido.";
            this.sub2.innerHTML="2. En cenizas renazco, en llamas me convierto.";
        } else if(this.imagenes===2) {
            this.valorImagen = ['cuervo', 'colmillo', 'fenix', 'uña', 'mano'];
            objetivo.innerHTML="Poción del Despertar";
            this.sub1.innerHTML="1. Desde lo profundo, vengo a despertar, ¿quién soy que con mi garra, todo puedo cambiar?";
            this.sub2.innerHTML="2. Oscura mensajera de la noche sin par.";
        } else{
            console.log('error al cargar imagenes');
        }
        this.cronometro = new Cronometro(1, 59, () => this.perder());
        this.corazones = new Corazon();
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
        this.subvalor="line-through red";

        botella.addEventListener('dragstart', e => {
            e.dataTransfer.setData('id', e.target.id);
        });

        colorBruja.addEventListener('dragover', e => {
            e.preventDefault();
        });


        colorBruja.addEventListener('drop', e => {
            const id = e.dataTransfer.getData('id');
                this.escogerElemento(id);
                this.procesarDrop(id);
        });
    }
    escogerElemento(id){
        if (this.imagenes === 0) {
            if (id == 'uña') {
                this.sub1.style.textDecoration = "line-through red";
            } else if (id == 'patas') {
                this.sub2.style.textDecoration = "line-through red";
            } else{
                this.sub1.style.textDecoration = "";
                this.sub2.style.textDecoration = "";
            }
        }else if (this.imagenes === 1) {
            if (id == 'mano') {
                this.sub1.style.textDecoration = "line-through red";
            } else if (id == 'fenix') {
                this.sub2.style.textDecoration = "line-through red";
            } else{
                this.sub1.style.textDecoration = "";
                this.sub2.style.textDecoration = "";
            }
        }
        else if (this.imagenes === 2) {
            if (id == 'uña') {
                this.sub1.style.textDecoration = "line-through red";
            } else if (id == 'cuervo') {
                this.sub2.style.textDecoration = "line-through red";
            } else{
                this.sub1.style.textDecoration = "";
                this.sub2.style.textDecoration = "";
            }
        }
        
    }

    procesarDrop(id) {
        if (this.imagenes===0) {
            if (id !== 'uña' && id !== 'patas') {
            this.corazones.perderCorazon();
            }
        }else if (this.imagenes===1) {
            if (id !== 'mano' && id !== 'fenix') {
                this.corazones.perderCorazon();
            }
        }else if (this.imagenes===2) {
            if (id !== 'uña' && id !== 'cuervo') {
                this.corazones.perderCorazon();
            }
        }


        if (!this.corazones.estaVivo()) {
            this.perder();
        } else if (this.sub1.style.textDecoration === this.subvalor && this.sub2.style.textDecoration === this.subvalor) {
            this.ganar();
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
        this.puntaje();
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