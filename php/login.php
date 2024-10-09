<?php
include_once '../app/config/connection.php';
include 'usuario.php';

// Crear una instancia de la conexión
$conn = new connection();
$pdo = $conn->connect(); // Obtener el objeto PDO

if (!$pdo) {
    die('Error: No se pudo conectar a la base de datos.');
}

class Login {
    private $email;
    private $contrasena;
    private $userId; // Agregar userId
    private $pdo;

    public function __construct($email, $contrasena, $userId, $pdo) {
        $this->email = $email;
        $this->contrasena = $contrasena;
        $this->userId = $userId; // Guardar userId
        $this->pdo = $pdo;
    }

    public function guardarLogin() {
        try {
            $hashedPassword = password_hash($this->contrasena, PASSWORD_DEFAULT);
            $sql = "INSERT INTO login (email, password, userID) VALUES (:email, :password, :userID)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':userID', $this->userId); // Usar userID aquí
            $stmt->execute();
        } catch (Exception $e) {
            echo "Error en guardarLogin: " . $e->getMessage();
        }
    }

    public function autenticar() {
        try {
            // Obtener userID usando el email
            $userCheckSql = "SELECT userID FROM login WHERE email = :email";
            $userStmt = $this->pdo->prepare($userCheckSql);
            $userStmt->bindParam(':email', $this->email);
            $userStmt->execute();
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return ['status' => 'error', 'message' => 'Correo o contraseña incorrectos'];
            }

            $userId = $user['userID'];

            // Verificar el accountActivationID en la tabla user usando userID
            $activationCheckSql = "SELECT accountActivationID FROM user WHERE userID = :userID";
            $activationStmt = $this->pdo->prepare($activationCheckSql);
            $activationStmt->bindParam(':userID', $userId);
            $activationStmt->execute();
            $userActivation = $activationStmt->fetch(PDO::FETCH_ASSOC);

            // Depuración
            var_dump($userActivation);

            // Si no se encontró el usuario o si accountActivationID es 0, no continuar
            if (!$userActivation || $userActivation['accountActivationID'] == 0) {
                return ['status' => 'error', 'message' => 'La cuenta no está activada. Por favor, verifica tu correo.'];
            }

            // Proceder con la autenticación si accountActivationID es 1
            $sql = "SELECT * FROM login WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($this->contrasena, $user['password'])) {
                return ['status' => 'success', 'message' => 'Inicio de sesión exitoso'];
            } else {
                return ['status' => 'error', 'message' => 'Correo o contraseña incorrectos'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()];
        }
    }
}
