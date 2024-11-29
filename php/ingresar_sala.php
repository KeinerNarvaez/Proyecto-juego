<?php
include_once '../app/config/connection.php';
session_start();

header('Content-Type: application/json');

try {
    // Verificar que el usuario esté autenticado
    if (!isset($_SESSION['userID'])) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado.']);
        exit;
    }

    $userID = $_SESSION['userID'];
    $codigo = json_decode(file_get_contents('php://input'), true);

    // Verificar que se envió el código generado
    if (!isset($codigo['codigoGenerado'])) {
        echo json_encode(['status' => 'error', 'message' => 'Código no proporcionado.']);
        exit;
    }

    $codigoGenerado = $codigo['codigoGenerado']; 

    // Validar el formato del código (6 caracteres alfanuméricos)
    if (!preg_match('/^[A-Z0-9]{6}$/', $codigoGenerado)) {
        echo json_encode(['status' => 'error', 'message' => 'Formato del código inválido.']);
        exit;
    }

    // Conectar a la base de datos
    $conn = new Connection();
    $pdo = $conn->connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si ya existe un registro para el usuario
    $checkQuery = "SELECT COUNT(*) FROM player WHERE userID = :userID";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $checkStmt->execute();

    $exists = $checkStmt->fetchColumn() > 0;

    if ($exists) {
        // Si el usuario ya tiene un registro, actualizar el código de la sala
        $updateQuery = "UPDATE player SET roomCode = :codigoGenerado WHERE userID = :userID";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':codigoGenerado', $codigoGenerado, PDO::PARAM_STR);
        $updateStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $updateStmt->execute();

        // Obtener el playerID después de la actualización
        $selectQuery = "SELECT playerID FROM player WHERE userID = :userID";
        $selectStmt = $pdo->prepare($selectQuery);
        $selectStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $selectStmt->execute();
        $player = $selectStmt->fetch(PDO::FETCH_ASSOC);
        $playerID = $player['playerID'];
    } else {
        // Si no existe el usuario en la tabla, insertarlo
        $insertQuery = "INSERT INTO player (userID, roomCode) VALUES (:userID, :codigoGenerado)";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $insertStmt->bindParam(':codigoGenerado', $codigoGenerado, PDO::PARAM_STR);
        $insertStmt->execute();

        // Obtener el playerID después de la inserción
        $playerID = $pdo->lastInsertId();
    }

    // Verificar si el roomCode existe en la tabla hostroom
    $checkHostRoomQuery = "SELECT hostRoomID FROM hostroom WHERE roomCode = :codigoGenerado";
    $checkHostRoomStmt = $pdo->prepare($checkHostRoomQuery);
    $checkHostRoomStmt->bindParam(':codigoGenerado', $codigoGenerado, PDO::PARAM_STR);
    $checkHostRoomStmt->execute();

    $hostRoomID = $checkHostRoomStmt->fetchColumn();

    if ($hostRoomID) {
        // Si el código de la sala existe, agregar el jugador a la tabla privatematch
        $insertMatchQuery = "INSERT INTO privatemath (hostRoomID, playerID) VALUES (:hostRoomID, :playerID)";
        $insertMatchStmt = $pdo->prepare($insertMatchQuery);
        $insertMatchStmt->bindParam(':hostRoomID', $hostRoomID, PDO::PARAM_INT);
        $insertMatchStmt->bindParam(':playerID', $playerID, PDO::PARAM_INT);
        $insertMatchStmt->execute();

        // Enviar respuesta de éxito
        echo json_encode(['status' => 'success', 'message' => 'Jugador agregado al juego exitosamente.']);
    } else {
        // Si el código no existe en la tabla hostroom
        echo json_encode(['status' => 'error', 'message' => 'El código de la sala no existe.']);
    }

} catch (Exception $e) {
    // Manejo de errores
    error_log("Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>


