<?php 
    require './app/config/connection.php';
    require './app/controllers/usuarioFunciones.php';

    $db = new connection();
    $con = $db->connect();

    $errors=[];

    if(isset($_POST["submit"])){
      // Validar los datos del formulario
    if (!empty($_POST)){

      $name = trim($_POST['name']);
      $lastName = trim($_POST['lastName']);
      $email = trim($_POST['email']);
      $password = trim($_POST['password']);

      $id =  registerUser([$name,$lastName], $connect);
      if($id > 0){
      $idReg = registerLogin([$email,$password,$id], $connect);
      }if($idReg > 0){
        $activationCode=generateCode();
        registerCode([$activationCode], $connect);
    }else{
      $errors[] =  " Error al registrarte ";
    }

    if(count($errors) == 0){
      echo "Se ha registrado exitosamente.";
    }else{
      print_r($errors);
    }

  } 
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.css">
    <!--Enlaces para fuentes de google-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <!--icono-->
    <link rel="shortcut icon" href="./Assest/ImagenBruja.png" type="image/x-icon">
        <!--enlace para fuente de google-->
    <link href="https://fonts.googleapis.com/css2?family=Koh+Santepheap:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/fontawesome-free-6.6.0-web/css/all.css">
    <style>
      .pass{
        transform: translateX(0px);
        position: relative;
        left: 285px;
        bottom: 30px;
        width: 30px;
      }
      .terminos{
        position: relative;
        bottom: 35px;
      }
      input[type="checkbox"] {
        display: none;
        position: relative;
      }
      label{
        padding-left: 2em;
      }
      label p{
        color: red;
        text-decoration: underline;
      }
      label::before{
        content: '';
        cursor: pointer;
        border: solid 1px #0194FF;
        border-radius: 3px;
        width: 1.5em;
        height: 1.4em;
        position:absolute;
        background-color: black;
        box-shadow: 1px 1px 10px 0.4px blue;
      }
      input[type="checkbox"]:checked + label ::before{
        color: #0194FF;
        position: absolute;
        content: '\f00c';
        margin-left: 5px;
        margin-bottom:4px;
        font-size: large;
        font-family: FontAwesome;
      }


      
    </style>
</head>
<body>
             <!--Modal-->
             <div class="modal fade" id="terminos" tabindex="-1" aria-labelledby="exampleModalLabel"  aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content"  style="background-color:#FBB347;" id="terminos-condiciones">
                    <!--modal header eliminado-->
                    <div class="modal-body" >
                      <div class="titulo-terminos" >
                        <h1>Terminos y condiciones</h1>
                        <hr>
                      </div>
                      <div class="termino">
                        <h2>1. Aceptación de los Términos</h2>
                         <p>Al crear una cuenta en Mythical Witch Mixes , aceptas cumplir y estar sujeto a los siguientes Términos y Condiciones de Uso. Estos términos pueden ser modificados ocasionalmente, por lo que te recomendamos revisarlos periódicamente. El uso continuado del Juego después de cualquier cambio se considerará como aceptación de dichos cambios.</p>
                      </div>
                      <div class="termino">
                        <h2>2.Creación de la Cuenta</h2>
                         <p>2.1. Para acceder a ciertos servicios y funciones del Juego, deberás crear una cuenta proporcionando información exacta, completa y actualizada.</p> 
                          <p>2.2. Debes ser mayor de edad según la legislación local para crear una cuenta, o contar con el consentimiento de tus padres o tutores legales si eres menor de edad. </p>
                          <p>
                            2.3. Eres responsable de la confidencialidad de tu cuenta y contraseña, así como de todas las actividades que ocurran bajo tu cuenta.
                          </p>
                      </div>
                      <div class="termino">
                        <h2>3.Contacto</h2>
                         <p>Si tienes alguna duda o inquietud sobre estos términos y condiciones, puedes contactarnos a través de franciscoandradebermeo560@gmail.com .</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
                  <!--Fin de modal-->

       <!--titulo del juego-->
       <div class="col-12 titulo" style="display: flex; flex-direction: column;margin-top: -32px;">
        <h1 class="text-titulo" style="margin-left: 70px;"> Mythical Witch </h1>
        <h1 class="text-titulo" style="margin-left: 70px;">Mixes</h1>
       </div>
   <!--/titulo del juego-->

     <!--flecha-->
     <div class="parte-izquierda" style="margin-top: -250px;">
      <div class="salir">
          <a class="text-boton" href="/login.html"><i class="fa-solid fa-arrow-left" style="font-size: 45px; color: white;"></i></a> <br>
      </div>
   </div>
     <!--/flecha-->

   <!--formulario - crear cuenta-->
   <div class="crearCuenta" style="margin-top: 42px;">

    <form action="./app/controllers/registrar.php" method="POST">

        <div class="titulo-cuenta">
            <h2>Crear cuenta</h2>
            <hr>
        </div>

        <div class="datos-crear">

            <div class="primera-parte" >
                <h3>Nombre</h3>
                <input type="text" name="name" placeholder="Registra tu nombre" required>
                <h3>Apellido</h3>
                <input type="text" name="lastName" placeholder="Registra tu apellido" required>
            </div>
            <div class="segunda-parte">
                <h3>Correo electrónico</h3>
                <input type="email" name="email" placeholder="Registra tu correo electronico" required>
                <h3>Contraseña</h3>
                <input type="password" name="password" placeholder="Registra una contraseña" id="pass" required>
                <i class="fa-solid fa-eye pass" id="ojo"></i>   
            </div>

        </div>
        <div class="terminos">
            <input type="checkbox" id="checkbox" required>
            <label for="checkbox" data-bs-toggle="modal" data-bs-target="#terminos"><p> &nbsp&nbsp &nbsp&nbsp Aceptar terminos y condiciones</p></label>
        </div>

   <!--boton-->
   <div class="boton-envio" style="margin-left: 130px; margin-top: -40px;">
    <div class=""id="boton-envio">
    <input type="submit" style="font-size: 27px;" class="text-boton" value="Siguiente" name="register" >  <!--boton, pero es un input--> 
    </div>
    </div>
    </div>
    </form>



    <!--/formulario - crear cuenta-->


  <!--audio-->
  <audio id="clickSound">
    <source src="/audio/clickleo.mp3" type="audio/mp3">
  </audio>
<!--/audio-->

    <script src="./js/contraseña.js"></script>
    <script src="./js/index.js"></script>
    <script src="./bootstrap/js/bootstrap.js"></script>
    <script src="./js/clickleo.js"></script>

</html>