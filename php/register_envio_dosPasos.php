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

    // Verificar si se ha proporcionado el email
    if (!empty($data['emailUser'])) {
        $email = trim($data['emailUser']);

        // Crear una instancia de la conexión
        $conn = new Connection();
        $pdo = $conn->connect(); // Obtener el objeto PDO

        // Verificar si el email existe
        $loginQuery = "SELECT loginID FROM login WHERE email = :email";
        $loginStmt = $pdo->prepare($loginQuery);
        $loginStmt->bindParam(':email', $email);
        $loginStmt->execute();
        $loginResult = $loginStmt->fetch(PDO::FETCH_ASSOC);

        if ($loginResult) {
            $loginID = $loginResult['loginID']; // Obtener el loginID

            // Generar el código de verificación y la fecha de expiración (1 minuto)
            $codigoVerificacion = mt_rand(100000, 999999); // Código de 6 dígitos
            $fechaExpiracion = date('Y-m-d H:i:s', strtotime('+1 minute'));

            // Insertar el código de verificación en la tabla twoStepsVerification
            $insertQuery = "INSERT INTO twoStepsVerification (codeTwoSteps, expires, loginID) VALUES (:codeTwoSteps, :expires , :loginID)";
            $insertStmt = $pdo->prepare($insertQuery);

            try {
                $insertStmt->execute([$codigoVerificacion, $fechaExpiracion, $loginID]);

                // Enviar correo electrónico con el código de verificación
                $mail = new PHPMailer(true);
                $mail->SMTPDebug = SMTP::DEBUG_OFF; // Cambia a SMTP::DEBUG_SERVER para ver los detalles del envío
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'mythicalsuport@gmail.com';
                $mail->Password   = 'clkubdhsvgbhcnpq'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                $mail->setFrom('mythicalsuport@gmail.com', 'Soporte MWM');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Código de Verificación en Dos Pasos';
                $mail->Body    = "Hola, tu código de verificación es: $codigoVerificacion. Este código expira en 1 minuto.";
                $mail->CharSet = 'UTF-8';

                if ($mail->send()) {
                    echo json_encode(['status' => 'success', 'message' => 'El código de verificación ha sido enviado a tu correo.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No se pudo enviar el correo.']);
                }
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Error en el envío del correo: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'El correo no está registrado.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos para enviar el código de verificación.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}

exit();
