<?php
include_once '../app/config/connection.php';
session_start();

header('Content-Type: application/json');

try {
    // Verificar la sesión
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
    // Validar formato del código
    if (!preg_match('/^[A-Z0-9]{6}$/', $codigoGenerado)) {
        echo json_encode(['status' => 'error', 'message' => 'Formato del código inválido.']);
        exit;
    }

    // Conexión a la base de datos
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
        // Actualizar el código si ya existe el registro
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
        // Insertar un nuevo registro si no existe
        $insertQuery = "INSERT INTO player (userID, roomCode) VALUES (:userID, :codigoGenerado)";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $insertStmt->bindParam(':codigoGenerado', $codigoGenerado, PDO::PARAM_STR);
        $insertStmt->execute();

        // Obtener el playerID después de la inserción
        $playerID = $pdo->lastInsertId(); 
    }

    // Ahora, verifica si el roomCode también existe en la tabla hostroom
    $checkHostRoomQuery = "SELECT hostRoomID FROM hostroom WHERE roomCode = :codigoGenerado";
    $checkHostRoomStmt = $pdo->prepare($checkHostRoomQuery);
    $checkHostRoomStmt->bindParam(':codigoGenerado', $codigoGenerado, PDO::PARAM_STR);
    $checkHostRoomStmt->execute();

    $hostRoomID = $checkHostRoomStmt->fetchColumn();

    if ($hostRoomID) {
        // Si el roomCode existe en ambas tablas (player y hostroom), inserta en privatematch
        $insertMatchQuery = "INSERT INTO privatematch (hostRoomID, playerID) VALUES (:hostRoomID, :playerID)";
        $insertMatchStmt = $pdo->prepare($insertMatchQuery);
        $insertMatchStmt->bindParam(':hostRoomID', $hostRoomID, PDO::PARAM_INT);
        $insertMatchStmt->bindParam(':playerID', $playerID, PDO::PARAM_INT);
        $insertMatchStmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Partida creada correctamente.']);
    } else {
        // Si no existe un roomCode válido en hostroom
        echo json_encode(['status' => 'error', 'message' => 'Código inválido en la sala de host.']);
    }

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>



