<?php
class connection {
    private $host = "localhost";
    private $dbname = "mwm_db";
    private $user = "root";
    private $password = "";
    private $charset = "utf8";

    function connect(){
        try{
            $connection = "mysql:host" . $this->host . "; dbname=" . $this->dbname . "; charset=" . $this->charset;
            $options =[
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false
            ];

            $pdo = new PDO($connection, $this->user, $this->password, $options);
            return $pdo;


        }catch(Exception $e){
            echo "error conexion: ". $e->getMessage();
            exit;
            }
        }
    }
    