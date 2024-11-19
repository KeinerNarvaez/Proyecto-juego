<?php
include_once '../app/config/connection.php'; // Incluye la conexión a la base de datos

// Establecer el encabezado de contenido JSON
header('Content-Type: application/json');

// Crear una instancia de la conexión
$conn = new Connection();
$pdo = $conn->connect(); // Obtener el objeto PDO

try {
    // Consulta SQL para obtener el gamerTag
    $sql = "SELECT user.gamerTag FROM player INNER JOIN user ON player.userID = user.userID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener todos los resultados

    // Enviar los resultados como JSON
    echo json_encode($results);
} catch (PDOException $e) {
    // Manejo de errores
    echo json_encode(['status' => 'error', 'message' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
}

// Cerrar la conexión
$pdo = null;


