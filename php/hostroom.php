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

    if ($codigo['select']=='Hechizos'){
        $position=0;
        $spell=1;
    } else if ($codigo['select']=='Pociones de color'){
        $position=1;
        $spell=0;
    } else{
        echo json_encode(['status' => 'error', 'message' => 'error en la eleccion de modo']);
        exit;
    }

    // Verificar que se envió el código generado
    if (!isset($codigo['codigoGenerado'])) {
        echo json_encode(['status' => 'error', 'message' => 'Código no proporcionado.']);
        exit;
    }

    $codigoGenerado = $codigo['codigoGenerado'];
    $_SESSION['codigo'] = $codigo['codigoGenerado'];
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
    $checkQuery = "SELECT COUNT(*) FROM hostroom WHERE userID = :userID";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $checkStmt->execute();

    $exists = $checkStmt->fetchColumn() > 0;

    if ($exists) {
        // Actualizar el código si ya existe el registro
        $updateQuery = "UPDATE hostroom SET roomCode = :codigoGenerado,  levelPosition =:position , spelllevel= :spell WHERE userID = :userID";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':codigoGenerado', $codigoGenerado, PDO::PARAM_STR);
        $updateStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $updateStmt->bindParam(':position', $position, PDO::PARAM_INT);
        $updateStmt->bindParam(':spell', $spell, PDO::PARAM_INT);
        $updateStmt->execute();
    } else {
        // Insertar un nuevo registro si no existe
        $insertQuery = "INSERT INTO hostroom (userID, roomCode,levelPosition, spelllevel) VALUES (:userID, :codigoGenerado, :position, :spell)";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $insertStmt->bindParam(':position', $position, PDO::PARAM_INT);
        $insertStmt->bindParam(':spell', $spell, PDO::PARAM_INT);
        $insertStmt->bindParam(':codigoGenerado', $codigoGenerado, PDO::PARAM_STR);
        $insertStmt->execute();
    }

    echo json_encode(['status' => 'success', 'codigoGenerado' => $codigoGenerado]);

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

