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
                $mail->Body    = "Hola, tu código de verificación es: $codigoVerificacion. Utilízalo para restablecer tu contraseña.";
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
