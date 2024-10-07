<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require './activar_cuenta.php';

class Mailer {

    public function enviarEmail($email, $asunto, $cuerpo) {
        require './phpMailer/src/PHPMailer.php';
        require './phpMailer/src/SMTP.php';
        require './phpMailer/src/exception.php';

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mythicalsuport@gmail.com';
            $mail->Password   = 'soporte_MWM@';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Destinatarios
            $mail->setFrom('mythicalsuport@gmail.com', 'Soporte MWM');
            $mail->addAddress($email);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $cuerpo;
            $mail->setLanguage('es', '../phpMailer/language/phpmailer.lang-es.php');

            // Envío del correo
            if ($mail->send()) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
            return false;
        }
    }
}
