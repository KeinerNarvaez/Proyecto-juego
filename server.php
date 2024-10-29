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
    public function __construct() { 
        $this->clients = new SplObjectStorage(); 
        $this->chat = new SplObjectStorage(); 
        $this->usernames = []; // Almacena los gamertags 
    } 
    public function onOpen(ConnectionInterface $conn) { 
        $this->clients->attach($conn); 
        $this->chat->attach($conn); 
        echo "Nueva conexión! ({$conn->resourceId})\n"; 
    } 
    public function onMessage(ConnectionInterface $from, $msg) { 
        $data = json_decode($msg, true); 
        $gamerTag = $data['gamerTag'] ?? 'Desconocido'; 
        // Guardar el gamertag 
        $this->usernames[$from->resourceId] = $gamerTag; 
        $dataChat= json_decode($msg,true); 
        foreach ($this->chat as $chat) { 
            if ($from !== $chat) { 
                $chat->send(json_encode(['mensajes' => $dataChat['mensajes']])); 
            } 
        } 
        // Notificar a todos los clientes sobre el nuevo usuario 
        foreach ($this->clients as $client) { 
            $client->send(json_encode([ 
                'message' => 'Nuevo usuario conectado', 
                'gamerTag' => $gamerTag, 
                'sender' => $from === $client // Indica si el mensaje es del cliente actual 
            ])); 
        } 
        // Enviar la lista actual de usuarios conectados a todos 
        $this->broadcastUserList(); 
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