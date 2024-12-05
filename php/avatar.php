<?php
include_once '../app/config/connection.php'; 
session_start();
header('Content-Type: application/json');
$conn = new Connection();
$pdo = $conn->connect();

try {
    if (!isset($_SESSION['userID'])) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado.']);
        exit;
    }    
    $sql = "SELECT avatar FROM avatar";  
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    foreach ($results as $row) {
        $data[] = [
            'imagen_base64' => base64_encode($row['avatar'])
        ];
    }
    echo json_encode($data); 
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
}
