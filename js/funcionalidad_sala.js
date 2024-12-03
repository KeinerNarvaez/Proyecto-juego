class Codigo {
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

class TarjetasUsuario {
    constructor(codigo) {
        this.codigo = codigo;
        this.socket = new WebSocket("ws://localhost:8080"); // Asegúrate de que esta URL sea la correcta
    }

    conectarSocket() {
        this.socket.onmessage = (event) => {
            const data = JSON.parse(event.data);

            if (data.message === 'Nuevo usuario online' && data.codigo === this.codigo) {
                this.agregarPerfil(data.gamerTag);
            }

            if (data.message === 'Lista de usuarios') {
                const cuerpoActivos = document.getElementById('personasConectadas');
                cuerpoActivos.innerHTML = ''; // Limpiar el contenedor de perfiles

                // Agregar los perfiles de los usuarios conectados
                data.users.forEach(user => {
                    this.agregarPerfil(user);
                });
            }
        };

        this.socket.onclose = (event) => {
            if (!event.wasClean) console.log('Conexión cerrada por el servidor');
        };

        this.socket.onerror = (error) => console.error('Error en WebSocket:', error);
    }

    agregarPerfil(gamerTag) {
        const clientId = `client-${Date.now()}`;
        let perfil = document.createElement('div');
        perfil.classList.add('perfil');
        perfil.id = clientId;
        perfil.innerHTML = `
            <div class="perfil-jugador">
                <i class="fa-solid fa-user"></i>
                <h1>${gamerTag}</h1>
            </div>
        `;
        document.getElementById('personasConectadas').appendChild(perfil);
    }
}


class Sala {
    constructor() {
        this.cronometro = new Cronometro(9, 59, () => this.sinTiempo());
        this.codigo = new Codigo(6);
    }

    iniciarJuego() {
        this.generarCodigo();
        document.getElementById('iniciar_juego').addEventListener('click', () => {
            this.cronometro.iniciar();
            document.getElementById('botones').innerHTML = '';
        });
    }

    generarCodigo() {
        const codigoGenerado = 'KFC123'//this.codigo.generarCodigo();
        console.log('Código generado:', codigoGenerado);
        var inputSelect = document.getElementById("select");
        var select = inputSelect.options[inputSelect.selectedIndex].text;
        console.log(select);
        this.tarjeta = new TarjetasUsuario(codigoGenerado);
        // Validar que la selección sea válida
        if (select !== "Hechizos" && select !== "Pociones de color") {
            console.error("Selección inválida");
            return;
        }

        fetch('./php/hostroom.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ codigoGenerado, select })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('codigo-sala').innerText = data.codigoGenerado;
                } else {
                    console.error('Error del servidor:', data.message);
                }
            })
            .catch(error => console.error('Error al enviar el código:', error));
    }

    sinTiempo() {
        alert('¡Se acabó el tiempo!');
    }
}

const miSala = new Sala();
miSala.iniciarJuego();
