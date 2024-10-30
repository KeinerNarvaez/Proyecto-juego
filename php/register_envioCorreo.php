<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../phpMailer/PHPMailer.php';
require '../phpMailer/SMTP.php';
require '../phpMailer/Exception.php';   
include_once '../app/config/connection.php';

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

        // Crear una instancia de la conexión
        $conn = new Connection();
        $pdo = $conn->connect(); // Obtener el objeto PDO

        // Generar el código de activación y la fecha de expiración (24 horas)
        $codigoActivacion = mt_rand(100000, 999999); // Código de 6 dígitos
        $fechaActual = date('Y-m-d H:i:s');
        $fechaExpiracion = date('Y-m-d H:i:s', strtotime('+1 minutes'));

        // Insertar el código de activación en la tabla accountActivation
        $insertQuery = "INSERT INTO accountActivation (activationCode, expires) VALUES (?, ?)";
        $insertStmt = $pdo->prepare($insertQuery);

        try {
            $insertStmt->execute([$codigoActivacion, $fechaExpiracion]);

            $mail = new PHPMailer(true);

            // Configuración del correo
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
            $mail->Subject = 'Código de Activación';
            $mail->Body = "
            <div style='font-family: Arial, sans-serif; color: #333; padding: 20px; border: 1px solid #ddd; max-width: 600px; margin: auto;'>
                <h2 style='text-align: center; color: #4CAF50;'>Activación de Cuenta en MWM</h2>
                <p>Hola <strong>$nombreUsuario $apellidoUsuario</strong>,</p>
                <p>Gracias por registrarte. Para activar tu cuenta, utiliza el siguiente código de activación:</p>
                <div style='text-align: center; font-size: 24px; font-weight: bold; color: #FF5722; margin: 20px 0;'>$codigoActivacion</div>
                <p style='margin: 10px 0;'>Este código expirará en <strong>1 minuto</strong>, así que por favor úsalo lo antes posible.</p>
                <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
                <p style='font-size: 12px; color: #999; margin-top: 20px;'>Atentamente,<br>Soporte MWM</p>
                <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
                <p style='text-align: center; font-size: 12px; color: #777;'>© 2024 Mythical Witch Mixes. Todos los derechos reservados.</p>
            </div>
        ";
            $mail->CharSet = 'UTF-8'; // Establece la codificación a UTF-8

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
