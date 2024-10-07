<?php

class ActivarCuenta {
    private $codigo; // Código de activación
    private $pdo;
    private $email;
    private $correo=[];

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
    public function guardarCodigo($email) {
        try {
            require_once 'login.php';
            require_once 'enviar_correo.php';
      // Incluye la clase Mailer para enviar correos

            $mailer = new mailer();           // Instancia del objeto Mailer para enviar correos

            // Asunto y cuerpo del correo de verificación
            $correo = [
                'asunto' => "Código de verificación de Mythical Witch Mixes",
                'cuerpo' => "Querido jugador: <br>
                            <p>Por favor, ingrese este código de activación para completar su registro en Mythical Witch Mixes:</p> <br>
                            Su código de verificación es: <b>{$this->codigo}</b>"
            ]; // Usa el código de activación generado

            // Definir fecha de expiración (1 minuto desde ahora)
            $expires = date('Y-m-d H:i:s', strtotime('+1 minutes'));

            // Preparar la consulta SQL para insertar el código y la fecha de expiración
            $sql = "INSERT INTO accountactivation (activationCode, expires) VALUES (:activationCode, :expires)";
            $stmt = $this->pdo->prepare($sql);      
            $stmt->bindParam(':activationCode', $this->codigo);
            $stmt->bindParam(':expires', $expires);

            if ($stmt->execute()) {
                // El código fue insertado correctamente
            } else {
                // Manejo de error en caso de que la ejecución falle
                throw new Exception("Error al guardar el código de activación.");
            }

            // Enviar el correo
            if ($mailer->enviarEmail($this->email, $asunto, $cuerpo)) {
                echo "Se ha enviado el código de verificación a su correo electrónico: {$this->email}";
            } else {
                echo "Error al enviar el correo de verificación.";
            }

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
