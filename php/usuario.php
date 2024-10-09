<?php
class Usuario {
    private $nombre; //nameUser
    private $apellido; //lastName
    
    private $pdo; //conexion

    public function __construct($nombre, $apellido, $pdo) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->pdo = $pdo;
    }

    public function guardarUsuario() {
        try {
            $sql = "INSERT INTO user (name, lastName) VALUES (:name, :lastName)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $this->nombre);
            $stmt->bindParam(':lastName', $this->apellido);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            echo "Error en guardarUsuario: " . $e->getMessage();
        }
    }
}

?>