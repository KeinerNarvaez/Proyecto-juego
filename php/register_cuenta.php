<?php
// Incluir la conexión y las clases
include_once '../app/config/connection.php';
include_once 'usuario.php';
include_once 'login.php';
include_once 'activar_cuenta.php';
include_once 'parametros.php'; 
include_once 'enviar_correo.php';
$errors = [];
$completado =[];

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos enviados en formato JSON
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar que los datos existan
    if (isset($data['nombreUsuario']) && isset($data['apellidoUsuario']) && isset($data['emailUsuario']) && isset($data['contraseñaUsuario'])) {

        $nombreUsuario = $data['nombreUsuario'];
        $apellidoUsuario = $data['apellidoUsuario'];
        $emailUsuario = $data['emailUsuario'];
        $contraseñaUsuario = $data['contraseñaUsuario'];

        // Conectar a la base de datos
        $conn = new Connection();
        $pdo = $conn->connect();

        $codigoActivacion = $activacion->guardarCodigo(); // Generar código
        $asunto = "Activa tu cuenta - mythicar witch mixes.";
        $cuerpo = "Estimado jugador: Para continuar con tu registro ingresa el siguiente código: " . $codigoActivacion;


        // Verificar si el formato del email es válido
        if (!validarEmail($emailUsuario)) {
            $errors[] = "El correo electrónico es inválido";
        } else {
         // Verificar si el email ya existe para evitar duplicidad
        if (emailExiste($emailUsuario, $pdo)) {
            $errors[] = "El correo electrónico ya está registrado";
        }
        }

        try {
            // Iniciar la transacción
            $pdo->beginTransaction();

            if (count($errors) === 0) {

                    if ($mailer->enviarEmail($emailUsuario, $asunto, $cuerpo)) {
                        $completado[] = "Correo electrónico enviado a $emailUsuario";
                    } else {
                        $errors[] = "Error al enviar el correo";
                    }
                
                // Crear el objeto Usuario
                $usuario = new Usuario($nombreUsuario, $apellidoUsuario, $pdo);
                $userId = $usuario->guardarUsuario(); // Guardar usuario y obtener su ID

                // Crear el objeto Login sin hashear la contraseña (texto plano)
                $login = new Login($emailUsuario, $contraseñaUsuario, $userId, $pdo);
                $login->guardarLogin(); // Guardar el login asociado al usuario

                // Crear y guardar el código de activación  (esto es lo que genera el error asi que 
                // proviene de las clases activar_cuenta.php)
                
                $activacion = new ActivarCuenta($pdo);
                $codigoActivacion = $activacion->guardarCodigo();   // Guardar el código de activación y pasar el correo 


                // Confirmar la transacción
                $pdo->commit();

                // Responder con un mensaje de éxito
                echo json_encode(['status' => 'success', 'message' => implode(", ", $completado)]);
            } else {
                // Si hay errores, devolverlos
                echo json_encode(['status' => 'error', 'message' => implode(", ", $errors)]);
            }
        } catch (Exception $e) {
            // En caso de error, revertir la transacción
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Error al crear la cuenta: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
    }
}