<?php
require_once "controllers/usersController.php";
//recoger datos
if (!isset ($_REQUEST["username"])){
   header('Location:index.php?tabla=user&accion=crear' );
   exit();
}

$id= ($_REQUEST["id"])??"";//el id me servirá en editar

$arrayUser=[    
    "id"=>$id,
    "username"=>$_REQUEST["username"],
    "usernameOriginal"=>($_REQUEST["usernameOriginal"])??"",
    "password"=>$_REQUEST["password"],
    "passwordOriginal"=>($_REQUEST["passwordOriginal"])??"",
    "digievolutions"=>$_REQUEST["digievolutions"]
 ];

//pagina invisible
$controlador= new UsersController();

if ($_REQUEST["evento"]=="crear"){
    $controlador->crear ($arrayUser);
}

if ($_REQUEST["evento"]=="modificar"){
    $controlador->editar ($id, $arrayUser);
}