<?php
session_start(); // Iniciar la sesión

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

        // Verificar si existe `userID` en la sesión
        if (isset($_SESSION['userID'])) {
            $userID = $_SESSION['userID']; // Leer el userID desde la sesión

            // Crear instancia de conexión
            $conn = new Connection();
            $pdo = $conn->connect();

            try {
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
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontró el userID en la sesión']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Alias no proporcionado']);
    }
} 
// Manejar solicitud GET para consultar el alias
elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Verificar si existe `userID` en la sesión
    if (isset($_SESSION['userID'])) {
        $userID = $_SESSION['userID']; // Leer el userID desde la sesión

        // Crear instancia de conexión
        $conn = new Connection();
        $pdo = $conn->connect();

        try {
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
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage() ]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se encontró el userID en la sesión']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}
