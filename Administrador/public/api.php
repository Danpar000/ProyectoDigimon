<?php
require_once "../controllers/digimonsController.php";



if (isset($_REQUEST["funcion"])) {
    $controller = new DigimonsController();
    $funcion = $_REQUEST["funcion"];
    switch ($_REQUEST["funcion"]){
        case "buscarJson":
            if (isset($_REQUEST["type"], $_REQUEST["level"])) {
                call_user_func([$controller, $funcion], $_REQUEST["type"], $_REQUEST["level"]);
            } else {
                echo json_encode(["error" => "Faltan campos por especificar"]);
            }
            break;
        case "verJson":
            if (isset($_REQUEST["id"])) {
                call_user_func([$controller, $funcion], $_REQUEST["id"]);
            } else {
                echo json_encode(["error" => "Faltan campos por especificar"]);
            }
            break;
        default:
            echo json_encode(["error" => "Función no encontrada".$_REQUEST["funcion"]]);
            break;

    }
} else {
    echo json_encode(["error" => "No se especificó ninguna función"]);
}
?>
