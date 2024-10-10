<?php
class connection {
    private $host = "localhost";
    private $dbname = "mwm_db";
    private $user = "root";
    private $password = "";
    private $charset = "utf8";

    function connect(){
        try{
            $connection = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=" . $this->charset;
            $options = [
                //modo de manejo de errores
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                //desactiva emulacion de consultas y utiliza consultas nativas
                PDO::ATTR_EMULATE_PREPARES   => false
            ];

            $pdo = new PDO($connection, $this->user, $this->password, $options);
            return $pdo;

        } catch(Exception $e) {
            echo "error conexion: " . $e->getMessage();
            exit;
        }
    }
}
