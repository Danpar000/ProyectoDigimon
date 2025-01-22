<?php
require_once "controllers/tasksController.php";
//recoger datos
if (!isset ($_REQUEST["name"]) && !isset($_REQUEST["idEncargado"])){
     header('Location:index.php?tabla=task&accion=crear' );
     exit();
}

$arrayTask=[    
                "id"=>"",
                "name"=>$_REQUEST["name"],
                "description"=>$_REQUEST["description"],
                "deadline"=>$_REQUEST["deadline"],
                "task_status"=>$_REQUEST["task_status"],
                "user_id"=>$_REQUEST["user_id"],
                "client_id"=>empty($_REQUEST["client_id"])?null:$_REQUEST["client_id"],
                "project_id"=>$_REQUEST["project_id"],
             ];
             
//pagina invisible
$controlador= new TasksController();

if ($_REQUEST["evento"]=="crear"){
    $arrayTask["id"] = ($_REQUEST["id"])??$_SESSION["datos"]["project_id"];
    $controlador->crear ($arrayTask);
}

if ($_REQUEST["evento"]=="modificar"){
    $arrayTask["id"] = (int)($_REQUEST["id"])??$_SESSION["datos"]["idTarea"];
    $controlador->editar ($arrayTask["id"], $arrayTask);
} elseif ($_REQUEST["evento"]=="editar") {

    $arrayTask=[    
        "id"=>"",
        "task_status"=>$_REQUEST["task_status"],
        "idEncargado"=>$_REQUEST["idEncargado"]
     ];

    $arrayTask["id"] = (int)($_REQUEST["id"])??$_SESSION["datos"]["idTarea"];
    $controlador->editarStatus($arrayTask["id"], $arrayTask);
}

