<?php
// Incluir la conexión y las clases
include_once '../app/config/connection.php';
include_once 'usuario.php';
include_once 'login.php';
include_once 'activar_cuenta.php';
include_once 'parametros.php'; 
$errors = [];

// Establecer el encabezado de contenido JSON
header('Content-Type: application/json');

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

        // Verificar si el formato del email es válido
        if (!validarEmail($emailUsuario)) {
            $errors[] = "El correo electrónico es inválido";
        } else {
            // Verificar si el email ya existe para evitar duplicidad
            if (emailExiste($emailUsuario, $pdo)) {
                $errors[] = "El correo electrónico ya está registrado";
            }
        }

        // Si hay errores, devolverlos en formato JSON
        if (count($errors) > 0) {
            echo json_encode(['status' => 'error', 'message' => implode(", ", $errors)]);
            exit; // Detener ejecución después de mostrar errores
        }

        try {
            // Iniciar la transacción
            $pdo->beginTransaction();

            // Crear el objeto Usuario
            $usuario = new Usuario($nombreUsuario, $apellidoUsuario, $pdo);
            $userId = $usuario->guardarUsuario(); // Guardar usuario y obtener su ID

            // Crear el objeto Login sin hashear la contraseña (texto plano)
            $login = new Login($emailUsuario, $contraseñaUsuario, $userId, $pdo);
            $login->guardarLogin(); // Guardar el login asociado al usuario

            // Crear y guardar el código de activación
            $activacion = new ActivarCuenta($pdo);
            $codigoActivacion = $activacion->guardarCodigo(); // Guardar el código de activación

            // Confirmar la transacción
            $pdo->commit();

            // Devolver una respuesta de éxito
            echo json_encode(['status' => 'success', 'message' => 'Cuenta creada con éxito']);
            exit; // Asegúrate de salir después de enviar la respuesta
        } catch (Exception $e) {
            // En caso de error, revertir la transacción y devolver el error
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Error al crear la cuenta: ' . $e->getMessage()]);
            exit; // Asegúrate de salir después de enviar la respuesta
        }
    } else {
        // Si faltan datos, devolver un mensaje de error
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
        exit; // Asegúrate de salir después de enviar la respuesta
    }
}

exit(); // Asegúrate de que no se envíe más contenido después de la respuesta JSON
