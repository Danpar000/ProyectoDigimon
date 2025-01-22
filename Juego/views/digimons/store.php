<?php
require_once "controllers/DigimonsController.php";
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
                "attack"=>$_REQUEST["attack"],
                "defense"=>$_REQUEST["defense"],
                "type"=>($_REQUEST["type"])??"",
                "level"=>$_REQUEST["level"],
                "next_evolution_id" => ($_REQUEST["next_evolution_id"] !== "" && $_REQUEST["next_evolution_id"] !== "null") ? $_REQUEST["next_evolution_id"] : null,
                "image"=>$_FILES["image"],
                "imageVictory"=>$_FILES["imageVictory"],
                "imageDefeat"=>$_FILES["imageDefeat"]
                ];

//pagina invisible
$controlador= new DigimonsController();

if ($_REQUEST["evento"]=="crear"){
    $controlador->crear ($arrayDigimon);
}

if ($_REQUEST["evento"]=="modificar"){
    $controlador->editar ($id, $arrayDigimon);
}