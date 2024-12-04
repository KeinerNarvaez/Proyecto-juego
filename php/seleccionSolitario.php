<?php
session_start();
include_once '../app/config/connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data['gameModeID']) && !empty($_SESSION['userID'])) {
        $gameModeID = intval($data['gameModeID']);
        $userID = intval($_SESSION['userID']); // Suponiendo que el userID está en la sesión

        $conn = new Connection();
        $pdo = $conn->connect();

        try { 
            // Verificar si el usuario ya tiene 5 registros en la tabla `only`
            $stmtCheck = $pdo->prepare('SELECT COUNT(*) FROM only WHERE userID = :userID');
            $stmtCheck->execute([':userID' => $userID]);
            $userCount = $stmtCheck->fetchColumn();

            if ($userCount >= 5) {
                echo json_encode(['status' => 'error', 'message' => 'No puedes crear más de 5 registros.']);
                exit;
            }

            if ($gameModeID === 1) {
                // Insertar en la tabla `spellLevel`
                $stmtSpell = $pdo->prepare('INSERT INTO spellLevel () VALUES ()');
                $stmtSpell->execute(); // No es necesario pasar columnas si solo es auto-increment

                // Obtener el último `spellLevelID` insertado
                $spellLevelID = $pdo->lastInsertId();

                // Insertar en la tabla `only` con el `spellLevelID` recién generado y el `userID`
                $stmtOnly = $pdo->prepare('INSERT INTO only (spellLevelID, userID) VALUES (:spellLevelID, :userID)');
                $stmtOnly->execute([':spellLevelID' => $spellLevelID, ':userID' => $userID]);

            } 
            elseif ($gameModeID === 2) {
                // Insertar en la tabla `levelPosition` (asegurándote de que el valor se inserte correctamente)
                $stmtLevel = $pdo->prepare('INSERT INTO levelPosition () VALUES ()');
                $stmtLevel->execute(); // No es necesario pasar columnas si solo es auto-increment

                // Obtener el último `levelPositionID` insertado
                $levelPositionID = $pdo->lastInsertId();

                // Verificar si el `levelPositionID` fue insertado correctamente
                if ($levelPositionID) {
                    // Insertar en la tabla `only` con el `levelPositionID` recién generado y el `userID`
                    $stmtOnly = $pdo->prepare('INSERT INTO only (levelPositionID, userID) VALUES (:levelPositionID, :userID)');
                    $stmtOnly->execute([':levelPositionID' => $levelPositionID, ':userID' => $userID]);
                } else {
                    throw new Exception('No se pudo insertar el levelPositionID.');
                }

            } else {
                echo json_encode(['status' => 'error', 'message' => 'Modo de juego no válido.']);
                exit;
            }

            // Respuesta exitosa
            echo json_encode(['status' => 'success', 'message' => 'Modo de juego registrado correctamente.']);
            
        } catch (Exception $e) {
            // Manejo de errores
            echo json_encode(['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    } else {
        // Datos incompletos o no hay un usuario logueado
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos o sesión expirada']);
    }
} else {
    // Método de solicitud no permitido
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}
