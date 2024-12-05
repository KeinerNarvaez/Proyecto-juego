<?php
session_start();
include_once '../app/config/connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_SESSION['userID'])) {
        $userID = intval($_SESSION['userID']);
        $conn = new Connection();
        $pdo = $conn->connect();

        try {
            // Obtener la partida más reciente con alias únicos
            $stmt = $pdo->prepare('
                SELECT 
                    o.onlyID, 
                    o.levelPositionID, 
                    o.spellLevelID,
                    l.level1 AS position_level1, l.level2 AS position_level2, l.level3 AS position_level3, l.level4 AS position_level4, l.level5 AS position_level5,
                    s.level1 AS spell_level1, s.level2 AS spell_level2, s.level3 AS spell_level3, s.level4 AS spell_level4, s.level5 AS spell_level5
                FROM only o
                LEFT JOIN levelPosition l ON o.levelPositionID = l.levelPositionID
                LEFT JOIN spellLevel s ON o.spellLevelID = s.spellLevelID
                WHERE o.userID = :userID
                ORDER BY o.onlyID DESC
                LIMIT 1
            ');
            $stmt->execute([':userID' => $userID]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                if ($result['levelPositionID'] !== null) {
                    // Verificar niveles de posiciones
                    $levels = [
                        'level1' => $result['position_level1'],
                        'level2' => $result['position_level2'],
                        'level3' => $result['position_level3'],
                        'level4' => $result['position_level4'],
                        'level5' => $result['position_level5']
                    ];
                    foreach ($levels as $key => $value) {
                        if ($value === 0) {
                            echo json_encode([
                                'status' => 'success',
                                'data' => [
                                    'onlyID' => $result['onlyID'],
                                    'nextLevel' => $key,
                                    'mode' => 'posiciones',
                                    'message' => 'Continúa desde el próximo nivel.'
                                ]
                            ]);
                            exit;
                        }
                    }

                    // Todos los niveles completados
                    echo json_encode([
                        'status' => 'completed',
                        'mode' => 'posiciones',
                        'message' => 'Ya completaste todos los niveles de este modo.'
                    ]);
                    exit;
                } elseif ($result['spellLevelID'] !== null) {
                    // Verificar niveles de hechizos
                    $levels = [
                        'level1' => $result['spell_level1'],
                        'level2' => $result['spell_level2'],
                        'level3' => $result['spell_level3'],
                        'level4' => $result['spell_level4'],
                        'level5' => $result['spell_level5']
                    ];
                    foreach ($levels as $key => $value) {
                        if ($value === 0) {
                            echo json_encode([
                                'status' => 'success',
                                'data' => [
                                    'onlyID' => $result['onlyID'],
                                    'nextLevel' => str_replace('level', 'spellLevel', $key),
                                    'mode' => 'hechizos',
                                    'message' => 'Continúa desde el próximo hechizo.'
                                ]
                            ]);
                            exit;
                        }
                    }

                    // Todos los hechizos completados
                    echo json_encode([
                        'status' => 'completed',
                        'mode' => 'hechizos',
                        'message' => 'Ya completaste todos los hechizos de este modo.'
                    ]);
                    exit;
                } else {
                    // No hay partida creada
                    echo json_encode([
                        'status' => 'no_game',
                        'message' => 'No has creado una partida aún.'
                    ]);
                    exit;
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se encontró ninguna partida guardada.']);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener la partida: ' . $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Sesión expirada.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
    exit;
}
