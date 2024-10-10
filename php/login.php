<?php
class Login {
    private $email; // emailUser
    private $contrasena; // Ahora se guardará sin hashing
    private $userId; // userId
    private $pdo; // conexxion

    public function __construct($email, $contrasena, $userId, $pdo) {
        $this->email = $email;
        $this->contrasena = $contrasena; // Guardar la contraseña en texto claro
        $this->userId = $userId;
        $this->pdo = $pdo;
    }

    public function guardarLogin() {
        try {
            $sql = "INSERT INTO login (email, password, userID) VALUES (:email, :password, :userID)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->contrasena); // Guardar sin hashear
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
            if ($user && $this->contrasena === $user['password']) { // Comparar sin hashear
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