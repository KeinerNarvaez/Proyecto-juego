<?php
session_start();

// Verificar si el userID está en la sesión
if (isset($_SESSION['userID'])) {
    echo json_encode(['userID' => $_SESSION['userID']]);
} else {
    echo json_encode(['error' => 'No estás logueado']);
}
?>
