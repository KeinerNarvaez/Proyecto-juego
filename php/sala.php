<?php
include_once '../app/config/connection.php'; 
session_start();
header('Content-Type: application/json');
$conn = new Connection();
$pdo = $conn->connect();
try {
    $codigoGenerado=$_SESSION['codigo'];
    $sql = "SELECT u.gamerTag FROM privatemath pp JOIN player js ON pp.playerID = js.playerID  JOIN user u ON js.userID = u.userID WHERE pp.hostRoomID= :codigoGenerado";  
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codigoGenerado', $codigoGenerado, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
}


$pdo = null;
