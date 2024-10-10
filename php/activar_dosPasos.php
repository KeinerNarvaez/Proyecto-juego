<?php

class VerificacionDosPasos {
    private $codigo; // Código de verificación de dos pasos
    private $pdo;

    public function generate_code() {
        return rand(100000, 999999); // Generar un número aleatorio de 6 dígitos
    }

    public function __construct($pdo) {
        $this->codigo = $this->generate_code(); // Generar el código de verificación
        $this->pdo = $pdo;
    }

    // Método para guardar el código junto con la fecha de expiración
    public function guardarCodigo() {
        try {
            // Definir fecha de expiración (1 minuto desde ahora)
            $expires = date('Y-m-d H:i:s', strtotime('+1 minutes'));

            // Preparar la consulta SQL para insertar el código y la fecha de expiración
            $sql = "INSERT INTO twostepsverification (codeTwoSteps, expires) VALUES (:codeTwoSteps, :expires)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':codeTwoSteps', $this->codigo);
            $stmt->bindParam(':expires', $expires);
            $stmt->execute(); // Ejecutar la consulta

            // Responder con JSON de éxito
            return json_encode(['status' => 'success', 'message' => 'Código de verificación guardado correctamente.']);
        } catch (Exception $e) {
            // Responder con JSON de error
            header('Content-Type: application/json');
            return json_encode(['status' => 'error', 'message' => "Error en guardarCodigo: " . $e->getMessage()]);
        }
    }
}
