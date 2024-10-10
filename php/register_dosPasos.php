<?php
include_once '../app/config/connection.php';

// Establecer el encabezado de contenido JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decodificar los datos recibidos
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si el código fue enviado
    if (!empty($data['codigo'])) {
        $codigoIngresado = trim($data['codigo']);

        // Crear una instancia de la conexión
        $conn = new connection();
        $pdo = $conn->connect(); // Obtener el objeto PDO

        try {
            // Verificar si el código ingresado existe en la base de datos y tiene estado '0' (pendiente de verificación)
            $query = "SELECT twoStepsVerificationID FROM twoStepsVerification WHERE codeTwoSteps = :codeTwoSteps AND verification = '0'";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':codeTwoSteps', $codigoIngresado);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si el código coincide y está pendiente de verificación
            if ($result) {
                $twoStepsVerificationID = $result['twoStepsVerificationID']; // Obtener el ID de activación

                // Actualizar el estado del código a '1' (verificado)
                $updateQuery = "UPDATE twoStepsVerification SET verification = '1' WHERE codeTwoSteps = :codeTwoSteps";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->bindParam(':codeTwoSteps', $codigoIngresado);
                $updateStmt->execute();

                // Obtener el userID de la tabla user donde twoStepsVerificationID es '0'
                $selectUserQuery = "SELECT userID FROM user WHERE twoStepsVerificationID = 0 LIMIT 1";
                $selectUserStmt = $pdo->prepare($selectUserQuery);
                $selectUserStmt->execute();
                $userResult = $selectUserStmt->fetch(PDO::FETCH_ASSOC);

                if ($userResult) {
                    $userID = $userResult['userID']; // Obtener el ID del usuario

                    // Guardar el twoStepsVerificationID en la tabla user como llave foránea
                    $updateUserQuery = "UPDATE user SET twoStepsVerificationID = :twoStepsVerificationID WHERE userID = :userID";
                    $updateUserStmt = $pdo->prepare($updateUserQuery);
                    $updateUserStmt->bindParam(':twoStepsVerificationID', $twoStepsVerificationID);
                    $updateUserStmt->bindParam(':userID', $userID);
                    $updateUserStmt->execute();

                    // Respuesta de éxito en formato JSON
                    echo json_encode(['status' => 'success', 'message' => 'Código de verificación de dos pasos guardado y usuario actualizado correctamente']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No se encontró ningún usuario para asociar']);
                }
            } else {
                // Si el código no es correcto o ya fue utilizado
                echo json_encode(['status' => 'error', 'message' => 'El código de verificación es incorrecto o ya fue utilizado']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
        }

        // Cerrar la conexión
        $pdo = null;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se recibió ningún código']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}
