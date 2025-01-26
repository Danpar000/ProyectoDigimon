<?php
require_once "controllers/digimonsController.php";
//recoger datos
if (!isset ($_REQUEST["id"]) && $_REQUEST["evento"] == "modificar"){
   header('Location:index.php?tabla=digimons&accion=crear' );
   exit();
}

$id= ($_REQUEST["id"])??"";//el id me servirá en editar

$arrayDigimon=[    
                "id"=>$id,
                "name"=>$_REQUEST["name"],
                "nameOriginal"=>($_REQUEST["nameOriginal"])??"",
                "health"=>$_REQUEST["health"],
                "attack"=>$_REQUEST["attack"],
                "defense"=>$_REQUEST["defense"],
                "speed"=>$_REQUEST["speed"],
                "type"=>($_REQUEST["type"])??"",
                "level"=>$_REQUEST["level"],
                "next_evolution_id" => ($_REQUEST["next_evolution_id"] !== "" && $_REQUEST["next_evolution_id"] !== "null") ? $_REQUEST["next_evolution_id"] : null,
                "image" => isset($_FILES["image"]) && $_FILES["image"]["error"] === 0 ? $_FILES["image"] : null,
                "imageVictory" => isset($_FILES["imageVictory"]) && $_FILES["imageVictory"]["error"] === 0 ? $_FILES["imageVictory"] : null,
                "imageDefeat" => isset($_FILES["imageDefeat"]) && $_FILES["imageDefeat"]["error"] === 0 ? $_FILES["imageDefeat"] : null
                ];

//pagina invisible
$controlador= new DigimonsController();

if ($_REQUEST["evento"]=="crear"){
    $controlador->crear ($arrayDigimon);
}

if ($_REQUEST["evento"]=="modificar"){
    $controlador->editar ($id, $arrayDigimon);
}