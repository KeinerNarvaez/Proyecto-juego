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

    // Verificar si el email existe en la base de datos
    if (!empty($data['emailUsuario'])) {
        $emailUsuario = trim($data['emailUsuario']);

        // Crear una instancia de la conexión
        $conn = new connection();
        $pdo = $conn->connect(); // Obtener el objeto PDO

        // Verificar si el email existe
        $query = "SELECT loginID FROM login WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $emailUsuario);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $userID = $result['loginID']; // Asegúrate de usar el nombre correcto del campo aquí

            // Generar un código de verificación aleatorio
            $codigoVerificacion = mt_rand(100000, 999999); // Código de 6 dígitos
            $fechaActual = date('Y-m-d H:i:s');

            // Insertar el código de verificación en la tabla recoverpassword
            $insertQuery = "INSERT INTO recoverpassword (userID, code, codeEstatus, applicationDate) VALUES (?, ?, '0', ?)";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->execute([$userID, $codigoVerificacion, $fechaActual]);

            // Configuración del servidor SMTP
            $mail = new PHPMailer(true);
            try {
                $mail->SMTPDebug = SMTP::DEBUG_OFF; // Cambia a SMTP::DEBUG_SERVER para obtener detalles sobre la conexión
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'mythicalsuport@gmail.com';
                $mail->Password   = 'clkubdhsvgbhcnpq'; // Usa variables de entorno para seguridad
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                // Destinatarios
                $mail->setFrom('mythicalsuport@gmail.com', 'Soporte MWM');
                $mail->addAddress($emailUsuario);

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Código de Verificación para Recuperación de Contraseña';
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
                <p>Hola,</p>
                <p>Has solicitado restablecer tu contraseña. Utiliza el siguiente código de verificación para continuar con el proceso:</p>
                <div style='text-align: center; padding: 15px; background-color: #2b2e4a; color: #fffb00; font-size: 24px; font-weight: bold; border-radius: 8px; margin: 20px 0;'>{$codigoVerificacion}</div>
                <p style='margin-bottom: 20px;'>Si no solicitaste esta recuperación, ignora este correo.</p>
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
</html>
            ";
                $mail->CharSet = 'UTF-8'; // Establece la codificación a UTF-8

                // Enviar el correo
                if ($mail->send()) {
                    echo json_encode(['status' => 'success', 'message' => 'El código de verificación ha sido enviado a tu correo.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No se pudo enviar el correo.']);
                }
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Error en el envío del correo: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'El correo no está registrado en el sistema.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Falta el correo electrónico.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}

exit();
