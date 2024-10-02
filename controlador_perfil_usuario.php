<?php 
    include('./conexion.php');

    if (!empty($_POST["button-validar"])){

        if (empty($_POST["email"]) and empty($_POST["password"])){
            echo '<script type="text/javascript">
            alert("INPUT VACIOS");
            window.location.assign("./login.html")
            </script>';
        } else {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $sql=$con->query("SELECT * FROm login  WHERE email='$email' AND password='$password' ");

            if ($datos=$sql->fetch_object()) {
                echo "<script> location.href=\"./verificacion_2pasos.html\" </script>";
            } else {
                echo '<script type="text/javascript">
                window.location.assign("./login.html");
                alert("Email o contraseña no son validas");
                </script>';
            }
        }
    } 
    ?>