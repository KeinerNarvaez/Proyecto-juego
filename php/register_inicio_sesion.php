<?php
include_once '../app/config/connection.php'; // Incluye la conexión a la base de datos
include_once 'login.php'; // Incluye la clase Login

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true); // Recibe los datos enviados en JSON

    // Validamos que se haya enviado el email y la contraseña
    if (isset($data['emailUser']) && isset($data['passwordUser'])) { // Cambié password a passwordUser
        $emailUsuario = $data['emailUser'];
        $contrasenaUsuario = $data['passwordUser']; // Asegúrate de usar el mismo nombre de variable

        // Conexión a la base de datos
        $conn = new Connection();
        $pdo = $conn->connect();

        // Crear instancia de la clase Login y autenticar
        $login = new Login($emailUsuario, $contrasenaUsuario, null, $pdo);
        $response = $login->autenticar(); // Autenticar al usuario

        // Enviar respuesta en formato JSON
        echo json_encode($response);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
    }
}
?>
