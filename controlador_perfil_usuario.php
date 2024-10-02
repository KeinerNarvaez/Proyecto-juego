<?php 
require './app/config/connection.php';

$db = new connection();
$con = $db->connect();

if (!empty($_POST["button-validar"])) {

    if (empty($_POST["email"]) || empty($_POST["password"])) {
        echo '<script type="text/javascript">
        alert("INPUT VACIOS");
        window.location.assign("./login.html");
        </script>';
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Usar consultas preparadas para evitar inyecciones SQL
        $sql = $con->prepare("SELECT * FROM login WHERE email = :email");
        $sql->execute([':email' => $email]);

        if ($datos = $sql->fetchObject()) {
            // Verificar la contraseña
            if (password_verify($password, $datos->password)) {
                echo "<script> location.href=\"./verificacion_2pasos.html\" </script>";
            } else {
                echo '<script type="text/javascript">
                window.location.assign("./login.html");
                alert("Email o contraseña no son válidas");
                </script>';
            }
        } else {
            echo '<script type="text/javascript">
            window.location.assign("./login.html");
            alert("Email o contraseña no son válidas");
            </script>';
        }
    }
}
?>