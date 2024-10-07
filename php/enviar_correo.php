<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class Mailer {

    public function enviarEmail($email, $asunto, $cuerpo) {
        require './phpMailer/PHPMailer.php';
        require './phpMailer/SMTP.php';
        require './phpMailer/Exception.php';

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
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
            $mail->Subject =$asunto;
            $mail->Body = mb_convert_encoding($cuerpo, 'ISO-8859-1', 'UTF-8');
            

            // Envío del correo
            if ($mail->send()) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            echo "No fue posible enviar el correo electronico, error de envio: {$mail->ErrorInfo}";
            return false;
        }
    }
}
