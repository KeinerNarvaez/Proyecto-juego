<?php
// Incluir la conexión a la base de datos
include_once '../app/config/connection.php';

// Establecer el encabezado de contenido JSON
header('Content-Type: application/json');

// Manejar solicitud POST para guardar el alias
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el contenido de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data['alias'])) {
        $alias = trim($data['alias']);

        // Crear instancia de conexión
        $conn = new Connection();
        $pdo = $conn->connect();

        try {
            // Obtener el userID basado en el último usuario con cuenta activada
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
} 
// Manejar solicitud GET para consultar el alias
elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Crear instancia de conexión
    $conn = new Connection();
    $pdo = $conn->connect();

    try {
        // Obtener el userID basado en el último usuario con cuenta activada
        $query = "SELECT userID FROM user WHERE accountActivationID IS NOT NULL ORDER BY accountActivationID DESC LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $userID = $result['userID'];

            // Consultar si existe un gamerTag asociado a este userID
            $checkQuery = "SELECT gamerTag FROM user WHERE userID = :userID";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->bindParam(':userID', $userID);
            $checkStmt->execute();
            $gamerTag = $checkStmt->fetchColumn();

            if ($gamerTag) {
                // Devolver el gamerTag si existe
                echo json_encode(['status' => 'success', 'gamerTag' => $gamerTag]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se encontró un gamerTag para el usuario']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontró el userID válido']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}
