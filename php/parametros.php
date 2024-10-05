<?php

function validarNulo (array $parametros){
    foreach($parametros as $parametro){
        if(strlen(trim($parametro))){
            return true;
        }
    }
    return false;
}

function validarEmail($email){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    }
    return false;
}

function emailExiste($email, $pdo){
    $sql = $pdo->prepare("SELECT id FROM login WHERE email LIKE ? LIMIT 1");
    $sql->execute([$email]);
    if ($sql->fetch_column() > 0){
        return true;
    }
    return false;
}

function mostrarErrores(array $errors) {
    if (count($errors) > 0) {
        $html = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach ($errors as $error) {
            $html .= '<li>' . htmlspecialchars($error) . "</li>";
        }
        $html .= '</ul>';
        $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        return $html; // Asegúrate de devolver la cadena
    }
    return ''; // Devolver una cadena vacía si no hay errores
}

