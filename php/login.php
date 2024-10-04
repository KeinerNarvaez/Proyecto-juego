<?php
class Login {
    private $email; // emailUser
    private $contrasena; // Contraseña en texto claro
    private $userId; // userId
    private $pdo; // conexión

    public function __construct($email, $contrasena, $userId, $pdo) {
        $this->email = $email;
        $this->contrasena = $contrasena; // Contraseña en texto claro
        $this->userId = $userId;
        $this->pdo = $pdo;
    }

    public function guardarLogin() {
        try {
            // Hashear la contraseña antes de guardarla
            $hashedPassword = password_hash($this->contrasena, PASSWORD_DEFAULT);
            $sql = "INSERT INTO login (email, password, userID) VALUES (:email, :password, :userID)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $hashedPassword); // Guardar hasheada
            $stmt->bindParam(':userID', $this->userId);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Error en guardarLogin: " . $e->getMessage();
        }
    }

    public function autenticar() {
        try {
            // Buscar al usuario por su email
            $sql = "SELECT * FROM login WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si el usuario existe y la contraseña es correcta
            if ($user && password_verify($this->contrasena, $user['password'])) { // Comparar con hash
                return ['status' => 'success', 'message' => 'Inicio de sesión exitoso'];
            } else {
                return ['status' => 'error', 'message' => 'Correo o contraseña incorrectos'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()];
        }
    }
}
?>
