<?php
require_once "../controllers/digimonsController.php";

if (isset($_REQUEST["funcion"], $_REQUEST["type"], $_REQUEST["level"])) {
    $controller = new DigimonsController();
    $funcion = $_REQUEST["funcion"];
    if (method_exists($controller, $funcion)) {
        call_user_func([$controller, $funcion], $_REQUEST["type"], $_REQUEST["level"]);
    } else {
        echo json_encode(["error" => "Función no encontrada"]);
    }
} else {
    echo json_encode(["error" => "No se especificó ninguna función"]);
}
?>
