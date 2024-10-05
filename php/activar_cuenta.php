<?php
class ActivarCuenta {
    private $codigo; // Código de activación
    private $pdo;

    public function __construct($pdo) {
        $this->codigo = $this->generate_code(); // Generar el código de activación
        $this->pdo = $pdo;
    }

    // Método para generar un código de 6 dígitos
    public function generate_code() {
        return rand(100000, 999999); // Generar un número aleatorio de 6 dígitos
    }

    // Método para guardar el código junto con la fecha de expiración
    public function guardarCodigo() {
        try {
            // Definir fecha de expiración (1 minuto desde ahora)
            $expires = date('Y-m-d H:i:s', strtotime('+1 minutes'));

            // Preparar la consulta SQL para insertar el código y la fecha de expiración
            $sql = "INSERT INTO accountactivation (activationCode, expires) VALUES (:activationCode, :expires)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':activationCode', $this->codigo);
            $stmt->bindParam(':expires', $expires);
            $stmt->execute();

            // Devolver el ID del registro insertado
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            echo "Error en guardarCodigo: " . $e->getMessage();
        }
    }

    // Método para verificar si el código es válido y no ha expirado
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
                return ['status' => 'success', 'message' => 'Código válido'];
            } else {
                return ['status' => 'error', 'message' => 'Código expirado o no válido'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Error en la verificación: ' . $e->getMessage()];
        }
    }
}
?>
