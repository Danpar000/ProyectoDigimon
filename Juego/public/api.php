<?php
require_once "../config/sessionControl.php";
require_once "../controllers/digimonsController.php";
require_once "../controllers/usersController.php";
require_once "../controllers/teamUsersController.php";

if (isset($_REQUEST["funcion"])){
    switch ($_REQUEST["funcion"]) {
        case "getSessionUsername":
            $controller = new UsersController();
            call_user_func([$controller, $_REQUEST["funcion"]]);
            break;
        case "getSessionID":
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
        case "listarNoUsadosJson":
            if (isset($_REQUEST["id"], $_REQUEST["digimon_id1"], $_REQUEST["digimon_id2"], $_REQUEST["digimon_id3"])) {
                $controller = new TeamUsersController();
                call_user_func([$controller, $_REQUEST["funcion"]], $_REQUEST["id"], $_REQUEST["digimon_id1"], $_REQUEST["digimon_id2"], $_REQUEST["digimon_id3"]);
            } else {
                echo json_encode(["error" => "Faltan campos por especificar"]);
            }
            break;
        case "verDigimonJson":
            if (isset($_REQUEST["id"])) {
                $controller = new DigimonsController();
                call_user_func([$controller, $_REQUEST["funcion"]], $_REQUEST["id"]);
            } else {
                echo json_encode(["error" => "Faltan campos por especificar"]);
            }
            break;
        case "verEquipoJson":
            if (isset($_REQUEST["id"])) {
                $controller = new TeamUsersController();
                call_user_func([$controller, $_REQUEST["funcion"]], $_REQUEST["id"]);
            } else {
                echo json_encode(["error" => "Faltan campos por especificar"]);
            }
            break;
        case "editarEquipo":
            if (isset($_REQUEST["newDigimon_id"], $_REQUEST["oldDigimon_id"])) {
                $controller = new TeamUsersController();
                call_user_func([$controller, $_REQUEST["funcion"]], $_SESSION["username"]->id, $_REQUEST["newDigimon_id"], $_REQUEST["oldDigimon_id"]);
            } else {
                echo json_encode(["error" => "Faltan campos por especificar"]);
            }
            break;
        case "logout":
            session_destroy();
        default:
            echo json_encode(["error" => "Función no encontrada"]);
    }
} else {
    echo json_encode(["error" => "No se especificó ninguna función"]);
}
?>
