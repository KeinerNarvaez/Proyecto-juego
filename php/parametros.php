<?php


// Función para verificar si el email ya existe en la base de datos (funciona)
function emailExiste($email, $pdo){
    $sql = $pdo->prepare("SELECT userID FROM login WHERE email LIKE ? LIMIT 1");
    $sql->execute([$email]);
    // Usar fetchColumn() con "C" mayúscula
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

// Función para verificar si el correo sea de formato email (funciona)
function validarEmail($email){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    }
    return false;
}