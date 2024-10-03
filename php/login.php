<?php
class Login {
    private $email;
    private $contrasena;
    private $userId;
    private $pdo;

    public function __construct($email, $contrasena, $userId, $pdo) {
        $this->email = $email;
        $this->contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $this->userId = $userId;
        $this->pdo = $pdo;
    }

    public function guardarLogin() {
        try {
            $sql = "INSERT INTO login (email, password, userID) VALUES (:email, :password, :userID)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->contrasena);
            $stmt->bindParam(':userID', $this->userId);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Error en guardarLogin: " . $e->getMessage();
        }
    }
}
?>
