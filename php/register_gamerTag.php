<?php
// Incluir la conexión a la base de datos
include_once '../app/config/connection.php';

// Establecer el encabezado de contenido JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decodificar los datos recibidos
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si se recibió el alias (gamerTag)
    if (!empty($data['alias'])) {
        $alias = trim($data['alias']);

        // Crear instancia de conexión
        $conn = new Connection();
        $pdo = $conn->connect();

        try {
            // Obtener el userID de la tabla user (sin recibir el userID ni el correo en el post)
            // Seleccionamos el userID basado en algún criterio (por ejemplo, el código de activación de cuenta ya verificado)
            // Aquí se asume que el último usuario con código de activación verificado es el correcto
            $query = "SELECT userID FROM user WHERE accountActivationID IS NOT NULL ORDER BY accountActivationID DESC LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $userID = $result['userID'];

                // Verificar si ya existe un `gamerTag` para este usuario
                $checkQuery = "SELECT COUNT(*) FROM user WHERE userID = :userID AND gamerTag IS NOT NULL";
                $checkStmt = $pdo->prepare($checkQuery);
                $checkStmt->bindParam(':userID', $userID);
                $checkStmt->execute();
                $gamerTagExists = $checkStmt->fetchColumn();

                if ($gamerTagExists) {
                    // Si ya existe un alias, actualizarlo
                    $updateQuery = "UPDATE user SET gamerTag = :alias WHERE userID = :userID";
                    $updateStmt = $pdo->prepare($updateQuery);
                    $updateStmt->bindParam(':alias', $alias);
                    $updateStmt->bindParam(':userID', $userID);
                    if ($updateStmt->execute()) {
                        echo json_encode(['status' => 'success', 'message' => 'Alias actualizado correctamente']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el alias']);
                    }
                } else {
                    // Si no existe un alias, insertarlo
                    $insertQuery = "INSERT INTO user (userID, gamerTag) VALUES (:userID, :alias)";
                    $insertStmt = $pdo->prepare($insertQuery);
                    $insertStmt->bindParam(':alias', $alias);
                    $insertStmt->bindParam(':userID', $userID);
                    if ($insertStmt->execute()) {
                        echo json_encode(['status' => 'success', 'message' => 'Alias guardado correctamente']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Error al guardar el alias']);
                    }
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se encontró el userID válido']);
            }

        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()]);
        }

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Alias no proporcionado']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}
