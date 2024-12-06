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
                       CASE 
                           WHEN s.spellLevelID IS NOT NULL THEN "Hechizos"
                           WHEN l.levelPositionID IS NOT NULL THEN "Posiciones"
                           ELSE "Desconocido"
                       END AS modo,
                       CASE
                           -- Hechizos
                           WHEN s.spellLevelID IS NOT NULL THEN
                               (CASE 
                                   WHEN s.level5 = 1 THEN 5
                                   WHEN s.level4 = 1 THEN 4
                                   WHEN s.level3 = 1 THEN 3
                                   WHEN s.level2 = 1 THEN 2
                                   WHEN s.level1 = 1 THEN 1
                                   ELSE 0
                               END)
                           -- Posiciones
                           WHEN l.levelPositionID IS NOT NULL THEN
                               (CASE 
                                   WHEN l.level5 = 1 THEN 5
                                   WHEN l.level4 = 1 THEN 4
                                   WHEN l.level3 = 1 THEN 3
                                   WHEN l.level2 = 1 THEN 2
                                   WHEN l.level1 = 1 THEN 1
                                   ELSE 0
                               END)
                           ELSE 0
                       END AS nivel
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
