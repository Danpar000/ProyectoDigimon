<?php
require_once "../config/sessionControl.php";
require_once "../controllers/digimonsController.php";
require_once "../controllers/usersController.php";

if (isset($_REQUEST["funcion"])){
    switch ($_REQUEST["funcion"]) {
        case "getSessionUsername":
            $controller = new UsersController();
            call_user_func([$controller, $_REQUEST["funcion"]]);
            break;
        case "buscarJson":
            if (isset($_REQUEST["type"], $_REQUEST["level"])) {
                $controller = new DigimonsController();
                call_user_func([$controller, $_REQUEST["funcion"]], $_REQUEST["type"], $_REQUEST["level"]);
            } else {
                echo json_encode(["error" => "Faltan campos por especificar"]);
            }
            break;
        default:
            echo json_encode(["error" => "Función no encontrada"]);
    }
} else {
    echo json_encode(["error" => "No se especificó ninguna función"]);
}
?>
