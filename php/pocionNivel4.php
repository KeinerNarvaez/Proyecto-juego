<?php
session_start();
include_once '../app/config/connection.php';
header('Content-Type: application/json');

function updateGameResult($userID, $currentScore, $result, $valorMinuto, $valorSegundos) {
    try {
        $conn = new Connection();
        $pdo = $conn->connect();

        // Obtener el último levelPositionID asociado al usuario (último nivel jugado)
        $query = "SELECT levelPositionID FROM only WHERE userID = :userID ORDER BY onlyID DESC LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':userID' => $userID]);
        $levelPositionID = $stmt->fetchColumn();

        if (!$levelPositionID) {
            // Retornar el error sin salir del script
            return ['error' => 'No se encontró el modo de juego asociado al usuario'];
        }

        // Si el jugador gana, actualizamos el nivel en `levelPosition`
        if ($result === 'win') {
            $query = "UPDATE levelPosition SET level4 = 1 WHERE levelPositionID = :levelPositionID";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':levelPositionID' => $levelPositionID]);
        }

        // Manejar los resultados en `history`
        $query = "SELECT bestScore, timesDefeated FROM history WHERE userID = :userID";
        $stmt = $pdo->prepare($query);
        $stmt->execute([ ':userID' => $userID ]);
        $history = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($history) {
            if ($result === 'win' && $currentScore > $history['bestScore']) {
                // Si el jugador gana y obtiene un puntaje superior al mejor puntaje anterior
                $query = "UPDATE history SET bestScore = :bestScore WHERE userID = :userID";
                $stmt = $pdo->prepare($query);
                $stmt->execute([ ':bestScore' => $currentScore, ':userID' => $userID ]);
            } elseif ($result === 'lose') {
                // Si el jugador pierde, incrementamos el número de derrotas
                $query = "UPDATE history SET timesDefeated = timesDefeated + 1 WHERE userID = :userID";
                $stmt = $pdo->prepare($query);
                $stmt->execute([':userID' => $userID]);
            }
        } else {
            // Si no existe un historial, insertamos un nuevo registro
            $query = "INSERT INTO history (userID, bestScore, timesDefeated) 
                      VALUES (:userID, :bestScore, :timesDefeated)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([ 
                ':userID' => $userID, 
                ':bestScore' => $result === 'win' ? $currentScore : 0, 
                ':timesDefeated' => $result === 'lose' ? 1 : 0 
            ]);
        }

        // Devolver una respuesta exitosa al final
        return ['success' => 'Resultado actualizado correctamente', 'puntajes' => $currentScore];

    } catch (PDOException $e) {
        // Manejo de errores con la base de datos
        return ['error' => 'Error en la base de datos: ' . $e->getMessage()];
    }
}

// Verificación de autenticación
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $data = json_decode(file_get_contents('php://input'), true); // Obtener los datos enviados por POST

    if ($data) {
        $currentScore = $data['currentScore'];
        $result = $data['result']; // 'win' o 'lose'
        $valorMinuto = $data['valorMinuto'];
        $valorSegundos = $data['valorSegundos'];

        // Llamada a la función para actualizar el resultado del juego
        $response = updateGameResult($userID, $currentScore, $result, $valorMinuto, $valorSegundos);
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Datos inválidos']);
    }
} else {
    // Si el usuario no está autenticado, respondemos con un error
    echo json_encode(['error' => 'Usuario no autenticado']);
}
