<?php
require_once "controllers/usersController.php";
//pagina invisible
if (!isset ($_REQUEST["id"])){
   header('location:index.php' );
   exit();
}
//recoger datos
$id=$_REQUEST["id"];
$usuario=$_REQUEST["usuario"];

$controlador= new usersController();
$controlador->borrar ($id, $usuario);