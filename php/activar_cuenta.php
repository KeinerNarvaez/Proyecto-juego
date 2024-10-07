<?php

class ActivarCuenta {
    private $codigo; // Código de activación
    private $pdo;

    public function generate_code() {
        return rand(100000, 999999); // Generar un número aleatorio de 6 dígitos
    }

    public function __construct($pdo) {
        $this->codigo = $this->generate_code(); // Generar el código de activación
        $this->pdo = $pdo;
    }

    // Método para generar un código de 6 dígitos


    // Método para guardar el código junto con la fecha de expiración y enviar el correo de verificación
    public function guardarCodigo() {
    try {
        // Definir fecha de expiración (1 minuto desde ahora)
        $expires = date('Y-m-d H:i:s', strtotime('+1 minutes'));

        // Preparar la consulta SQL para insertar el código y la fecha de expiración
        $sql = "INSERT INTO accountactivation (activationCode, expires) VALUES (:activationCode, :expires)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':activationCode', $this->codigo);
        $stmt->bindParam(':expires', $expires);
    } catch (Exception $e) {
        // Responder con JSON de error
        header('Content-Type: application/json');
        return json_encode(['status' => 'error', 'message' => "Error en guardarCodigo: " . $e->getMessage()]);
    }
}


}
