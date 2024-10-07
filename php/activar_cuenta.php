
<?php

class ActivarCuenta {
    private $codigo; // Código de activación
    private $pdo;
    private $email;

    public function __construct($pdo, $email) {
        $this->email = $email;
        $this->codigo = $this->generate_code(); // Generar el código de activación
        $this->pdo = $pdo;
    }

    // Método para generar un código de 6 dígitos
    public function generate_code() {
        return rand(100000, 999999); // Generar un número aleatorio de 6 dígitos
    }

    // Método para guardar el código junto con la fecha de expiración y enviar el correo de verificación
    public function guardarCodigo() {
    try {
        // Incluye la clase Mailer para enviar correos
        require_once 'enviar_correo.php';

        $mailer = new Mailer(); // Instancia del objeto Mailer para enviar correos

        // Asunto y cuerpo del correo de verificación
        $correo = [
            'asunto' => "Código de verificación de Mythical Witch Mixes",
            'cuerpo' => "Querido jugador: Por favor, ingrese este código de activación para completar su registro en Mythical Witch Mixes: 
                        Su código de verificación es: {$this->codigo}"
        ];

        // Definir fecha de expiración (1 minuto desde ahora)
        $expires = date('Y-m-d H:i:s', strtotime('+1 minutes'));

        // Preparar la consulta SQL para insertar el código y la fecha de expiración
        $sql = "INSERT INTO accountactivation (activationCode, expires) VALUES (:activationCode, :expires)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':activationCode', $this->codigo);
        $stmt->bindParam(':expires', $expires);

        // Enviar el correo
        if ($mailer->enviarEmail($this->email, $correo['asunto'], $correo['cuerpo'])) {
            // Responder con JSON de éxito
            header('Content-Type: application/json');
            return json_encode(['status' => 'success', 'message' => "Se ha enviado el código de verificación a su correo electrónico: {$this->email}"]);
        } else {
            // Error en el envío del correo
            header('Content-Type: application/json');
            return json_encode(['status' => 'error', 'message' => 'Error al enviar el correo de verificación.']);
        }
    } catch (Exception $e) {
        // Responder con JSON de error
        header('Content-Type: application/json');
        return json_encode(['status' => 'error', 'message' => "Error en guardarCodigo: " . $e->getMessage()]);
    }
}

    //* Método para verificar si el código es válido y no ha expirado

    /*
    public function verificarCodigo($codigo) {
        try {
            // Consulta para verificar que el código no haya expirado
            $sql = "SELECT * FROM accountactivation WHERE activationCode = :activationCode AND expires > NOW()";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':activationCode', $codigo);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si el código fue encontrado y no ha expirado
            if ($result) {
                return json_encode(['status' => 'success', 'message' => 'Código válido']);
            } else {
                return json_encode(['status' => 'error', 'message' => 'Código expirado o no válido']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => 'Error en la verificación: ' . $e->getMessage()]);
        }
    } */
    

}
