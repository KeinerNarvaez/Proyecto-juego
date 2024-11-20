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
    creadorTarjeta() {
        const socket = new WebSocket("ws://localhost:8080"); 
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
                    this.conectarSocket(item.gamerTag); 
                });
            })
            .catch(error => console.error('Error:', error));
    }

    conectarSocket(gamerTag) { 
        socket.onopen = () => { 
            console.log("Conectado al servidor WebSocket"); 
            // Enviar solo el mensaje de conexión una vez
            socket.send(JSON.stringify({ 
                message: 'Nuevo usuario conectado', 
                gamerTag: gamerTag 
            })); 
        };

        socket.onmessage = (event) => { 
            const data = JSON.parse(event.data); 

            if (data.message === 'Nuevo usuario conectado' && data.gamerTag === gamerTag) { 
                this.agregarPerfil(data.gamerTag); // Agrega la tarjeta del propio usuario 
            } 

            if (data.message === 'Lista de usuarios') { 
                const cuerpoActivos = document.getElementById('personasConectadas'); 
                cuerpoActivos.innerHTML = ''; // Limpiar el contenedor de perfiles 
                
                data.users.forEach(user => { 
                    this.agregarPerfil(user); 
                }); 
            } 
        }; 

        socket.onclose = (event) => { 
            if (!event.wasClean) console.log('Conexión cerrada por el servidor'); 
        }; 

        socket.onerror = (error) => console.error(error); 
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
        this.tarjeta = new TarjetasUsuario();
        this.cronometro = new Cronometro(9, 59, () => this.sinTiempo());
        this.codigo = new Codigo(6);
    }

    iniciarJuego() {
        this.tarjeta.creadorTarjeta();
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
