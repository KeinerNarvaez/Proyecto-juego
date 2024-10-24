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
            $mail->Body    = "Hola $nombreUsuario, tu código de activación es: $codigoActivacion. Este código expira en 1 minuto.";
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
