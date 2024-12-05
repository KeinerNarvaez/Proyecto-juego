<?php  
require __DIR__ . '/vendor/autoload.php';  
use Ratchet\Server\IoServer;  
use Ratchet\Http\HttpServer;  
use Ratchet\WebSocket\WsServer;  
use Ratchet\MessageComponentInterface;  
use Ratchet\ConnectionInterface;  

echo "El servidor está corriendo...\n";  

class Chat implements MessageComponentInterface {  
    protected $clients;  
    protected $chat;  
    protected $usernames;
    protected $user_online;  
    protected $rooms;

    public function __construct() {  
        $this->clients = new SplObjectStorage();  
        $this->user_online = new SplObjectStorage();  
        $this->chat = new SplObjectStorage();  
        $this->usernames = [];
        $this->rooms = [];
        
    }  

    public function onOpen(ConnectionInterface $conn) {  
        $this->clients->attach($conn);  
        $this->user_online->attach($conn);  
        $this->chat->attach($conn);  
        echo "Nueva conexión! ({$conn->resourceId})\n";  
    }  

    public function onMessage(ConnectionInterface $from, $msg) {  
        $data = json_decode($msg, true);  
if (isset($data['gamerTag']) && $data['message'] === 'Nuevo usuario online') {  
            $gamerTag = $data['gamerTag'];
            $roomCode = $data['roomCode'];
        
            $this->usernames[$from->resourceId] = $gamerTag;
        
            // Agregar usuario a la sala
            if (!isset($this->rooms[$roomCode])) {
                $this->rooms[$roomCode] = [];
            }
            $this->rooms[$roomCode][] = ['gamerTag' => $gamerTag];
        
            // Notificar a todos los clientes sobre el nuevo usuario
            foreach ($this->clients as $client) {  
                $client->send(json_encode([  
                    'message' => 'Nuevo usuario online',  
                    'roomCode' => $roomCode,  
                    'gamerTag' => $gamerTag  
                ]));  
            }
        
            echo "Nuevo usuario: {$gamerTag} con código de sala: {$roomCode}\n";            
            $this->broadcastUserList();
        }
        // Verificar si el mensaje es un evento de conexión
        if (isset($data['gamerTag']) && $data['message'] === 'Nuevo usuario conectado') {  
            // Procesar la conexión del usuario
            $gamerTag = $data['gamerTag'];
            $this->usernames[$from->resourceId] = $gamerTag;

            // Notificar a todos los clientes sobre el nuevo usuario
            foreach ($this->clients as $client) {  
                $client->send(json_encode([  
                    'message' => 'Nuevo usuario conectado',  
                    'gamerTag' => $gamerTag  
                ]));  
            }

            // Enviar la lista actual de usuarios conectados a todos
            $this->broadcastUserList();
        } elseif (isset($data['mensajes'])) {  
            // Procesar solo el mensaje de chat
            foreach ($this->chat as $chat) {  
                if ($from !== $chat) {  
                    $chat->send(json_encode(['mensajes' => $data['mensajes']]));  
                }  
            }  
        }  
    }  

    protected function broadcastUserList() {  
        $userList = [];  
        foreach ($this->usernames as $id => $gamerTag) {  
            $userList[] = $gamerTag;  
        }  
        foreach ($this->clients as $client) {  
            $client->send(json_encode(['message' => 'Lista de usuarios', 'users' => $userList]));  
        }  
    }  

    public function onClose(ConnectionInterface $conn) {  
        // Eliminar al usuario de la lista de usernames  
        if (isset($this->usernames[$conn->resourceId])) {
            $gamerTag = $this->usernames[$conn->resourceId];
            unset($this->usernames[$conn->resourceId]);
    
            // Notificar a todos los clientes que el usuario se desconectó
            foreach ($this->clients as $client) {
                $client->send(json_encode([
                    'message' => 'Usuario desconectado',
                    'gamerTag' => $gamerTag
                ]));
            }
    
            echo "Usuario {$gamerTag} desconectado\n";
        }
    
        // Eliminar al usuario del listado de conexiones activas
        $this->clients->detach($conn);
    
        // Opcional: Aquí puedes manejar la eliminación del usuario de las salas, si es necesario
        if (isset($this->rooms)) {
            foreach ($this->rooms as $roomCode => $users) {
                foreach ($users as $key => $user) {
                    if ($user['gamerTag'] === $gamerTag) {
                        unset($this->rooms[$roomCode][$key]);
    
                        // Si la sala queda vacía, puedes eliminarla opcionalmente
                        if (empty($this->rooms[$roomCode])) {
                            unset($this->rooms[$roomCode]);
                            echo "La sala {$roomCode} está vacía y se ha eliminado.\n";
                        }
                        break;
                    }
                }
            }
        }
    
        echo "Conexión {$conn->resourceId} desconectada\n";
    }
    

    public function onError(ConnectionInterface $conn, \Exception $e) {  
        echo "Error: {$e->getMessage()}\n";  
        $conn->close();  
    }  
}  

$server = IoServer::factory(  
    new HttpServer(  
        new WsServer(  
            new Chat()  
        )  
    ),  
    8080  
);  
$server->run();