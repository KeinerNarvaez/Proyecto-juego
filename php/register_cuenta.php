<?php
// Incluir la conexión y las clases necesarias
include_once '../app/config/connection.php';
include_once 'activar_cuenta.php';
include_once 'login.php';
include_once 'usuario.php';

// Funciones adicionales (sin eliminarlas)
function emailExiste($email, $pdo){
    $sql = $pdo->prepare("SELECT userID FROM login WHERE email LIKE ? LIMIT 1");
    $sql->execute([$email]);
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function validarEmail($email){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    }
    return false;
}

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

            // Crear el usuario y obtener su ID
            $usuario = new Usuario($nombreUsuario, $apellidoUsuario, $pdo);
            $userId = $usuario->guardarUsuario();

            // Guardar los datos de login
            $login = new Login($emailUsuario, $contraseñaUsuario, $userId, $pdo);
            $login->guardarLogin();

            // Crear y guardar el código de activación
            $activacion = new ActivarCuenta($pdo);
            $codigoActivacion = $activacion->guardarCodigo();

            // Confirmar la transacción
            $pdo->commit();

            // Enviar respuesta de éxito en formato JSON
            echo json_encode(['status' => 'success', 'message' => 'Cuenta creada con éxito. Código de activación enviado.', 'codigo' => $codigoActivacion]);
            exit;
        } catch (Exception $e) {
            // En caso de error, revertir la transacción y devolver el error
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Error al crear la cuenta: ' . $e->getMessage()]);
            exit;
        }
    } else {
        // Si faltan datos, devolver un mensaje de error
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
        exit;
    }
}
exit();