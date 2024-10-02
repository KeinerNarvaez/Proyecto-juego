<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id_usuario=$_GET['id_usuario'];
    $usuario=$_POST['usuario'];
    $contrasena=$_POST['contrasena'];

    
    include('../conexion.php');
    
    $sql="update usuario set usuario='$usuario', contrasena='$contrasena' where id_usuario='$id_usuario'";
    
    $result=mysqli_query($con,$sql);
    
    header('Location: ../layout_l/index_l.php');
}
?>