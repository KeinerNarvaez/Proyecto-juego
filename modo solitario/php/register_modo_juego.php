<?php

session_start(); // Iniciar sesión

// Incluir la conexión a la base de datos
include_once '../app/config/connection.php';

// Establecer el encabezado de contenido JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el contenido de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si se recibió el `gameModeID` y si existe `userID` en la sesión
    if (!empty($data['gameModeID']) && isset($_SESSION['userID'])) {
        $gameModeID = intval($data['gameModeID']);
        $userID = $_SESSION['userID']; // Obtener el userID de la sesión

        // Crear instancia de conexión
        $conn = new Connection();
        $pdo = $conn->connect();

        try {
            // Verificar si ya existe una entrada para este usuario y modo de juego
            $checkQuery = "SELECT COUNT(*) FROM only WHERE userID = :userID AND gameModeID = :gameModeID";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->bindParam(':userID', $userID);
            $checkStmt->bindParam(':gameModeID', $gameModeID);
            $checkStmt->execute();
            $exists = $checkStmt->fetchColumn();

            if ($exists) {
                // Si ya existe el modo de juego para el usuario, responder sin insertar
                echo json_encode(['status' => 'success', 'message' => 'Modo de juego ya registrado para el usuario']);
            } else {
                // Insertar el modo de juego en la tabla `only` si no existe
                $insertQuery = "INSERT INTO only (userID, gameModeID) VALUES (:userID, :gameModeID)";
                $insertStmt = $pdo->prepare($insertQuery);
                $insertStmt->bindParam(':userID', $userID);
                $insertStmt->bindParam(':gameModeID', $gameModeID);

                if ($insertStmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Modo de juego guardado correctamente']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error al guardar el modo de juego']);
                }
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos o sesión no iniciada']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}
