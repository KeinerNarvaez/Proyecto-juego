<?php
// Incluir la conexión y las clases
include_once '../app/config/connection.php';
include_once 'usuario.php';
include_once 'login.php';
include_once 'activar_cuenta.php';
include_once 'parametros.php';

$errors = [];

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

        // validar que no haya valores nulos en el formulario
        if (validarNulo([$nombreUsuario, $apellidoUsuario, $emailUsuario, $contraseñaUsuario])) {
            $errors[] = "Llenar todos los campos";
        }

        // validar que sea estrictamente email en el formulario
        if (!validarEmail($emailUsuario)) {
            $errors[] = "La dirección de correo no es válida";
        }

        // validar que no exista el mismo email
        if (emailExiste($emailUsuario, $pdo)) {
            $errors[] = "El correo electrónico $emailUsuario ya existe";
        }

        try {
            // Iniciar la transacción
            $pdo->beginTransaction();

            if (count($errors) === 0) {
                // Crear el objeto Usuario
                $usuario = new Usuario($nombreUsuario, $apellidoUsuario, $pdo);
                $userId = $usuario->guardarUsuario(); // Guardar usuario y obtener su ID

                // Crear el objeto Login hasheando la contraseña
                $contraseñaHasheada = password_hash($contraseñaUsuario, PASSWORD_DEFAULT);
                $login = new Login($emailUsuario, $contraseñaHasheada, $userId, $pdo);
                $login->guardarLogin(); // Guardar el login asociado al usuario

                // Crear y guardar el código de activación
                $activacion = new ActivarCuenta($pdo);
                $codigoActivacion = $activacion->guardarCodigo(); // Guardar el código de activación

                // Confirmar la transacción
                $pdo->commit();

                // Responder con un mensaje de éxito
                echo json_encode(['status' => 'success', 'message' => 'Cuenta creada exitosamente']);
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
?>
