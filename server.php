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

    public function __construct() {  
        $this->clients = new SplObjectStorage();  
        $this->user_online = new SplObjectStorage();  
        $this->chat = new SplObjectStorage();  
        $this->usernames = []; // Almacena los gamertags  
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
            // Procesar la conexión del usuario
            $gamerTag = $data['gamerTag'];
            $this->usernames[$from->resourceId] = $gamerTag;

            // Notificar a todos los clientes sobre el nuevo usuario
            foreach ($this->user_online as $user_online) {  
                $user_online->send(json_encode([  
                    'message' => 'Nuevo usuario online',
                    'roomCode'=> $data['roomCode'],  
                    'gamerTag' => $gamerTag  
                ]));  
            }
            echo "Nuevo usuario: {$gamerTag} con código de sala: {$data['roomCode']}\n";
            // Enviar la lista actual de usuarios conectados a todos
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
        // Eliminar al usuario de la lista al desconectarse  
        unset($this->usernames[$conn->resourceId]);  
        $this->clients->detach($conn);  
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