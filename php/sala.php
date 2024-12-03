<?php
include_once '../app/config/connection.php'; 
session_start();
header('Content-Type: application/json');
$conn = new Connection();
$pdo = $conn->connect();

try {
    $userID = $_SESSION['userID'];

    $sql = "SELECT user.gamerTag, player.roomCode FROM player INNER JOIN user ON player.userID = user.userID WHERE user.userID=:userID";  
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
}

$pdo = null;

