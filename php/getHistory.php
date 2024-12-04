<?php
session_start();
include_once '../app/config/connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!empty($_SESSION['userID'])) {
        $userID = intval($_SESSION['userID']);
        $conn = new Connection();
        $pdo = $conn->connect();

        try {
            // Consulta para obtener los datos del historial
            $stmt = $pdo->prepare('
                SELECT bestScore, timesCompleted, timesDefeated 
                FROM history 
                WHERE userID = :userID
            ');
            $stmt->execute([':userID' => $userID]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode(['status' => 'success', 'data' => $result]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se encontró historial para el usuario.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener historial: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Sesión expirada.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
