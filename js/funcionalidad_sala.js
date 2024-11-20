class codigo {
    constructor(numeroCaracteres) {
        this.caracteres = numeroCaracteres;
    }

    generarCodigo() {
        const caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        const num = caracteres.length;
        let result = "";
        for (let i = 0; i < this.caracteres; i++) {
            result += caracteres.charAt(Math.floor(Math.random() * num));
        }
        return result;  // Devuelve el código generado
    }
}

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
class tarjetasUsuario {
    creadorTarjeta() {
        fetch('./php/sala.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(),
        })
            .then(response => response.json())
            .then(data => {
                console.log('Datos recibidos:', data);
                data.forEach(item => {
                    console.log('GamerTag:', item.gamerTag);
                    this.estructuraTabla(item.gamerTag); // Envía cada gamerTag
                });
            })
            .catch(error => console.error('Error:', error));
    }

    estructuraTabla(data){
            const clientId = `client-${Date.now()}`; 
            let perfil = document.createElement('div'); 
            perfil.classList.add('perfil'); 
            perfil.id = clientId; 
            perfil.innerHTML = ` 
                <div class="perfil-jugador"> 
                    <i class="fa-solid fa-user"></i> 
                    <h1>${data}</h1> 
                </div> 
            `; 
            document.getElementById('personasConectadas').appendChild(perfil); 
    }
    
}

class sala {
    constructor() {
        this.cronometro = new Cronometro(9, 59, () => this.sinTiempo());
        this.tarjeta = new tarjetasUsuario();
        this.codigo = new codigo(6);
    }

    iniciar_juego() {
        this.tarjeta.creadorTarjeta();
        this.generarCodigo();
        document.getElementById('iniciar_juego').addEventListener('click', () => {
            this.cronometro.iniciar();
            document.getElementById('botones').innerHTML='';
        });
    }

    generarCodigo() {
        const codigoGenerado = this.codigo.generarCodigo();
        document.getElementById('codigo-sala').innerText = codigoGenerado;
    }

    sinTiempo() {
        alert('¡Se acabó el tiempo!');
    }
}

const miSala = new sala();
miSala.iniciar_juego();