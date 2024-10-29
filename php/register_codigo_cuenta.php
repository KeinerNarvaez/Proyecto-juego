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
            $query = "SELECT accountActivationID FROM accountactivation WHERE activationCode = :activationCode AND activation = '0'";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':activationCode', $codigoIngresado);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si el código coincide y está pendiente de verificación
            if ($result) {
                $accountActivationID = $result['accountActivationID']; // Obtener el ID de activación

                // Actualizar el estado del código a '1' (verificado)
                $updateQuery = "UPDATE accountactivation SET activation = '1' WHERE activationCode = :activationCode";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->bindParam(':activationCode', $codigoIngresado);
                $updateStmt->execute();

                // Obtener el userID de la tabla user donde accountActivationID es '0'
                $selectUserQuery = "SELECT userID FROM user WHERE accountActivationID = 0 LIMIT 1";
                $selectUserStmt = $pdo->prepare($selectUserQuery);
                $selectUserStmt->execute();
                $userResult = $selectUserStmt->fetch(PDO::FETCH_ASSOC);

                if ($userResult) {
                    $userID = $userResult['userID']; // Obtener el ID del usuario

                    // Guardar el accountActivationID en la tabla user como llave foránea
                    $updateUserQuery = "UPDATE user SET accountActivationID = :accountActivationID WHERE userID = :userID";
                    $updateUserStmt = $pdo->prepare($updateUserQuery);
                    $updateUserStmt->bindParam(':accountActivationID', $accountActivationID);
                    $updateUserStmt->bindParam(':userID', $userID);
                    $updateUserStmt->execute();

                    // Eliminar el código de activación verificado de la base de datos
                    $deleteQuery = "DELETE FROM accountactivation WHERE accountActivationID = :accountActivationID";
                    $deleteStmt = $pdo->prepare($deleteQuery);
                    $deleteStmt->bindParam(':accountActivationID', $accountActivationID);
                    $deleteStmt->execute();

                    // Respuesta de éxito en formato JSON
                    echo json_encode(['status' => 'success', 'message' => 'Código verificado y usuario actualizado correctamente']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No se encontró ningún usuario para asociar']);
                }
            } else {
                // Si el código no es correcto o ya fue utilizado
                echo json_encode(['status' => 'error', 'message' => 'El código es incorrecto o ya fue utilizado']);
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
