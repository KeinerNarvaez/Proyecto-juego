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
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Correo de Activación</title>
    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Kavoon&display=swap' rel='stylesheet'>
</head>
<body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;'>
    <table style='max-width: 600px; margin: 20px auto; background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;'>
        <tr>
            <td style='background-color: #2b2e4a; padding: 20px; text-align: center;'>
                <h1 style='font-size: 26px; font-family: Kavoon, Arial, serif; color: #FF9900; margin: 0;'>Mythical Witch Mixes</h1>
            </td>
        </tr>
        <tr>
            <td style='padding: 20px; font-size: 16px; line-height: 1.6; color: #333333;'>
                <p>Hola <strong>{$nombreUsuario} {$apellidoUsuario}</strong>,</p>
                <p>Gracias por registrarte. Para activar tu cuenta en <strong>Mythical Witch Mixes</strong>, usa el siguiente código de activación:</p>
                <div style='text-align: center; padding: 15px; background-color: #2b2e4a; color: #fffb00; font-size: 24px; font-weight: bold; border-radius: 8px; margin: 20px 0;'>{$codigoActivacion}</div>
                <p>Este código expirará en <strong>1 minuto</strong>, así que por favor úsalo lo antes posible.</p>
                <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
                <p style='font-size: 16px; color: #666666; margin-top: 20px;'>Atentamente,<br>Soporte de Mythical Witch Mixes</p>
            </td>
        </tr>
        <tr>
            <td style='padding: 15px; text-align: center; background-color: #f4f4f4; color: #999999; font-size: 14px;'>
                <p style='margin: 0;'>© 2024 Mythical Witch Mixes. Todos los derechos reservados.</p>
            </td>
        </tr>
    </table>
</body>
</html>";


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
