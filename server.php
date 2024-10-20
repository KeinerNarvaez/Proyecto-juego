<?php

// Habilitar el reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir el autoloader de Composer
require __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

// Mensaje de inicio del servidor
echo "El servidor está corriendo...\n";

// Clase Chat que implementa MessageComponentInterface
class Chat implements MessageComponentInterface
{
    protected $clients; // No es necesario especificar el tipo aquí

    public function __construct()
    {
        $this->clients = new SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Almacenar la nueva conexión para enviar mensajes más tarde
        $this->clients->attach($conn);
        echo "Nueva conexión! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send(json_encode(['message' => $data['message']]));
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // La conexión se cierra, eliminarla, ya que no podemos enviarle mensajes
        $this->clients->detach($conn);
        echo "Conexión {$conn->resourceId} desconectada\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Ha ocurrido un error: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Crear el servidor
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

// Ejecutar el servidor
$server->run();
