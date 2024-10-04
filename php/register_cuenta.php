<?php
// Incluir la conexión y las clases
include_once '../app/config/connection.php';
include_once 'usuario.php';
include_once 'login.php';

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos enviados en formato JSON
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar que los datos existan
    if (isset($data['nombreUsuario']) && isset($data['apellidoUsuario']) && isset($data['emailUsuario']) && isset($data['contraseñaUsuario'])) {

        $nombreUsuario = $data['nombreUsuario'];
        $apellidoUsuario = $data['apellidoUsuario'];
        $emailUsuario = $data['emailUsuario'];
        $contraseñaUsuario = $data['contraseñaUsuario'];

        // Conectar a la base de datos
        $conn = new Connection();
        $pdo = $conn->connect();

        try {
            // Iniciar la transacción
            $pdo->beginTransaction();

            // Crear el objeto Usuario
            $usuario = new Usuario($nombreUsuario, $apellidoUsuario, $pdo);
            $userId = $usuario->guardarUsuario(); // Guardar usuario y obtener su ID

            // Crear el objeto Login sin hashear la contraseña
            $login = new Login($emailUsuario, $contraseñaUsuario, $userId, $pdo);
            $login->guardarLogin(); // Guardar el login asociado al usuario

            // Confirmar la transacción
            $pdo->commit();

            // Responder con un mensaje de éxito
            echo json_encode(['status' => 'success', 'message' => 'Cuenta creada exitosamente']);
        } catch (Exception $e) {
            // En caso de error, revertir la transacción
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Error al crear la cuenta: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
    }
}
?>
