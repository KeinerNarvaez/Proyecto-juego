<?php
include('../config/connection.php');

if (isset($_POST['register'])) {
    if (strlen($_POST['name']) >= 1 && 
    strlen($_POST['lastName']) >= 1 && 
    strlen($_POST['email']) >= 1 && 
    strlen($_POST['password']) >= 1) {
        
        // Obtener los valores del formulario
        $name = trim($_POST['name']);
        $lastName = trim($_POST['lastName']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        
        // Generar fecha actual en formato correcto
        $logindate = date('Y-m-d');

        // Conectar a la base de datos
        $connect = connection::getConnection();

        // Usar consultas preparadas para evitar inyección SQL
        try {
            // Comenzar una transacción
            $connect->beginTransaction();

            // Preparar la primera consulta sin userID
            $sql1 = 'INSERT INTO "user" (name, "lastName") VALUES (:name, :lastName)';
            $stmt1 = $connect->prepare($sql1);
            $stmt1->bindParam(':name', $name);
            $stmt1->bindParam(':lastName', $lastName);
            $stmt1->execute(); // Ejecutar la consulta

            // Preparar la segunda consulta
            $sql2 = 'INSERT INTO login (email, password, logindate) VALUES (:email, :password, :logindate)'; 
            $stmt2 = $connect->prepare($sql2);
            $stmt2->bindParam(':email', $email);
            $stmt2->bindParam(':password', $password);
            $stmt2->bindParam(':logindate', $logindate);
            $stmt2->execute(); // Ejecutar la consulta



            // Confirmar la transacción
            $connect->commit();
            echo "<h3>Registro exitoso</h3>";

        } catch (PDOException $e) {
            // En caso de error, hacer rollback
            $connect->rollBack();
            echo "Error en la consulta: " . $e->getMessage();
        }
    } else {
        echo "<h3>Por favor, complete todos los campos</h3>";
    }
}
?>
