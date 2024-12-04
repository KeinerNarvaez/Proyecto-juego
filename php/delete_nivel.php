<?php
session_start();
include_once '../app/config/connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data['onlyID']) && !empty($_SESSION['userID'])) {
        $onlyID = intval($data['onlyID']);
        $userID = intval($_SESSION['userID']);
        $conn = new Connection();
        $pdo = $conn->connect();

        // Iniciar la transacción
        $pdo->beginTransaction();

        try {
            // Primero, obtener los IDs de las tablas dependientes
            $stmt = $pdo->prepare('SELECT spellLevelID, levelPositionID FROM only WHERE onlyID = :onlyID');
            $stmt->execute([':onlyID' => $onlyID]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $spellLevelID = $result['spellLevelID'];
                $levelPositionID = $result['levelPositionID'];

                // Eliminar en la tabla relacionada `spellLevel` si `spellLevelID` no es nulo
                if ($spellLevelID > 0) {
                    $stmt = $pdo->prepare('DELETE FROM spellLevel WHERE spellLevelID = :spellLevelID');
                    $stmt->execute([':spellLevelID' => $spellLevelID]);
                }

                // Eliminar en la tabla relacionada `levelPosition` si `levelPositionID` no es nulo
                if ($levelPositionID > 0) {
                    $stmt = $pdo->prepare('DELETE FROM levelPosition WHERE levelPositionID = :levelPositionID');
                    $stmt->execute([':levelPositionID' => $levelPositionID]);
                }
            }

            // Eliminar en la tabla principal (only)
            $stmt = $pdo->prepare('DELETE FROM only WHERE onlyID = :onlyID AND userID = :userID');
            $stmt->execute([':onlyID' => $onlyID, ':userID' => $userID]);

            // Confirmar la transacción
            $pdo->commit();

            echo json_encode(['status' => 'success', 'message' => 'Nivel eliminado correctamente.']);
        } catch (Exception $e) {
            // Si ocurre un error, revertir los cambios
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar nivel: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos o sesión expirada.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>
