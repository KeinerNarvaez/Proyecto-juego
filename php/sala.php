<?php
include_once '../app/config/connection.php'; 
session_start();
header('Content-Type: application/json');
$conn = new Connection();
$pdo = $conn->connect();
try {
    $codigoGenerado=$_SESSION['codigo'];
    $sql = "SELECT user.gamerTag FROM player INNER JOIN user ON player.userID = user.userID WHERE roomCode=:codigoGenerado";  
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codigoGenerado', $codigoGenerado, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
}


$pdo = null;
