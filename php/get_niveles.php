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
            $stmt = $pdo->prepare('
                SELECT o.onlyID, 
                       COALESCE(s.spellLevelID, l.levelPositionID) AS nivel, 
                       CASE WHEN s.spellLevelID IS NOT NULL THEN "Hechizos" ELSE "Posiciones" END AS modo
                FROM only o
                LEFT JOIN spellLevel s ON o.spellLevelID = s.spellLevelID
                LEFT JOIN levelPosition l ON o.levelPositionID = l.levelPositionID
                WHERE o.userID = :userID
            ');
            $stmt->execute([':userID' => $userID]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'data' => $result]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener niveles: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Sesión expirada.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
