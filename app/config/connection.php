<?php
class connection {
    public $host = 'localhost';
    public $dbname = 'bd_MWM';
    public $port = "5432";
    public $user = 'MWM_admin';
    public $password = 'david2312@';
    public $driver = 'pgsql';
    public $connect;

    public static function getConnection() {
        try {
            $connection = new connection();
            $connection->connect = new PDO("{$connection->driver}:host={$connection->host};port={$connection->port};dbname={$connection->dbname}", $connection->user, $connection->password);
            $connection->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $connection->connect;
        } catch (PDOException $e) {
            echo "error: " . $e->getMessage();
        }
    }
}

// Establecer la conexión
connection::getConnection();
?>
