<?php
// Incluir la conexión a la base de datos
include_once '../app/config/connection.php';

// Establecer el encabezado de contenido JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decodificar los datos recibidos
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si se recibió la nueva contraseña
    if (!empty($data['contrasenaVerificada'])) {
        $nuevaContrasena = trim($data['contrasenaVerificada']);

        // Crear instancia de conexión
        $conn = new connection();
        $pdo = $conn->connect();

        try {
            // Obtener el userID a partir de la tabla recoverpassword donde el código ya fue verificado
            $query = "SELECT userID FROM recoverpassword WHERE codeEstatus = '1' ORDER BY applicationDate DESC LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $userID = $result['userID'];

                // Hashear la nueva contraseña
                $hashedPassword = password_hash($nuevaContrasena, PASSWORD_DEFAULT);

                // Actualizar la contraseña en la tabla login, utilizando el campo userID
                $updateQuery = "UPDATE login SET password = :password WHERE userID = :userID";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->bindParam(':password', $hashedPassword);
                $updateStmt->bindParam(':userID', $userID);

                if ($updateStmt->execute()) {
                    // Eliminar el código de verificación de la tabla recoverpassword después de renovar la contraseña
                    $deleteQuery = "DELETE FROM recoverpassword WHERE userID = :userID";
                    $deleteStmt = $pdo->prepare($deleteQuery);
                    $deleteStmt->bindParam(':userID', $userID);
                    $deleteStmt->execute();

                    echo json_encode(['status' => 'success', 'message' => 'Contraseña renovada y código de verificación eliminado correctamente']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error al actualizar la contraseña']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se encontró el usuario para renovar la contraseña']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se recibió una nueva contraseña']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}
?>
