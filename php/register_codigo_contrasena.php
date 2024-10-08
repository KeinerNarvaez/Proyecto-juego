<?php
include_once '../app/config/connection.php';

// Establecer el encabezado de contenido JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si el código fue enviado
    if (!empty($data['codigo'])) {
        $codigoIngresado = trim($data['codigo']);

        // Crear una instancia de la conexión
        $conn = new connection();
        $pdo = $conn->connect(); // Obtener el objeto PDO

        // Verificar si el código ingresado existe en la base de datos y tiene estado '0'
        $query = "SELECT * FROM recoverpassword WHERE code = :code AND codeEstatus = '0'";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':code', $codigoIngresado);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si el código coincide y está pendiente
        if ($result) {
            // Actualizar el estado del código a 'verificado'
            $updateQuery = "UPDATE recoverpassword SET codeEstatus = '1' WHERE code = :code";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':code', $codigoIngresado);
            $updateStmt->execute();

            // Retornar respuesta de éxito
            echo json_encode(['status' => 'success', 'message' => 'Código verificado correctamente']);
        } else {
            // Código incorrecto o ya verificado
            echo json_encode(['status' => 'error', 'message' => 'El código es incorrecto o ya fue utilizado']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se recibió ningún código']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}
?>
