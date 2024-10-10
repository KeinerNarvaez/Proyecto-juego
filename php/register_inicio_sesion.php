<?php
include_once '../app/config/connection.php'; // Incluye la conexión a la base de datos

// Establecer el encabezado de contenido JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decodificar los datos recibidos
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si se ha enviado el email y la contraseña
    if (!empty($data['emailUser']) && !empty($data['passwordUser'])) {
        $emailUsuario = trim($data['emailUser']);
        $contrasenaUsuario = trim($data['passwordUser']);

        // Crear una instancia de la conexión
        $conn = new Connection();
        $pdo = $conn->connect(); // Obtener el objeto PDO

        try {
            // Buscar al usuario por su email
            $sql = "SELECT * FROM login WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $emailUsuario);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si el usuario existe
            if ($user) {
                // Verificar la contraseña
                if ($contrasenaUsuario === $user['password']) { // Comparar sin hashear
                    // Obtener el userID para verificar el accountActivationID
                    $userId = $user['userID'];
                    $activationCheckSql = "SELECT accountActivationID FROM user WHERE userID = :userID";
                    $activationStmt = $pdo->prepare($activationCheckSql);
                    $activationStmt->bindParam(':userID', $userId);
                    $activationStmt->execute();
                    $userActivation = $activationStmt->fetch(PDO::FETCH_ASSOC);

                    // Verificar el accountActivationID
                    if ($userActivation && $userActivation['accountActivationID'] == 1) {
                        // Respuesta de éxito en formato JSON
                        echo json_encode(['status' => 'success', 'message' => 'Inicio de sesión exitoso']);
                    } else {
                        // La cuenta no está activada
                        echo json_encode(['status' => 'error', 'message' => 'La cuenta no está activada,verifica el código.']);
                    }
                } else {
                    // Contraseña incorrecta
                    echo json_encode(['status' => 'error', 'message' => 'Correo o contraseña incorrectos']);
                }
            } else {
                // El usuario no existe
                echo json_encode(['status' => 'error', 'message' => 'Correo o contraseña incorrectos']);
            }
        } catch (PDOException $e) {
            // Manejo de errores
            echo json_encode(['status' => 'error', 'message' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
        }

        // Cerrar la conexión
        $pdo = null;
    } else {
        // Si faltan datos
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
    }
} else {
    // Método de solicitud no permitido
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}
?>
