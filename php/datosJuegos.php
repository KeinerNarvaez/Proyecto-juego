<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}


header('Content-Type: application/json');

// Obtén el contenido de la solicitud
$info = file_get_contents("php://input");


$valoresJuego = json_decode($info, true);


if ($valoresJuego === null) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}



$dataOperaciones = [
    "puntajes" => $valoresJuego['puntaje'],
    "minutos" => $valoresJuego['valorMinuto'],
    "segundos" => $valoresJuego['valorSegundos']
];


echo json_encode($dataOperaciones);
?>