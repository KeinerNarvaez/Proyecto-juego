<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../phpMailer/PHPMailer.php';
require '../phpMailer/SMTP.php';
require '../phpMailer/Exception.php';

// Deshabilitar la visualización de errores en producción
error_reporting(0); // Cambia esto a E_ALL para desarrollo

// Establecer el encabezado de contenido JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data['nombreUsuario']) && !empty($data['apellidoUsuario']) && !empty($data['emailUsuario'])) {
        $nombreUsuario = trim($data['nombreUsuario']);
        $apellidoUsuario = trim($data['apellidoUsuario']);
        $emailUsuario = trim($data['emailUsuario']);

        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Cambia a SMTP::DEBUG_SERVER para obtener detalles sobre la conexión
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mythicalsuport@gmail.com';
            $mail->Password   = 'clkubdhsvgbhcnpq'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('mythicalsuport@gmail.com', 'Soporte MWM');
            $mail->addAddress($emailUsuario);

            $mail->isHTML(true);
            $mail->Subject = 'Asunto del correo';
            $mail->Body    = "Hola $nombreUsuario, este es un correo de prueba.";

            if ($mail->send()) {
                echo json_encode(['status' => 'success', 'message' => 'El código de activación ha sido enviado a tu correo.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo enviar el correo.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error en el envío del correo: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos para enviar el correo']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}

exit();
