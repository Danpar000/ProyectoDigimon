<?php
require_once "controllers/tasksController.php";
//pagina invisible
if (!isset ($_REQUEST["id"]) || !isset($_REQUEST["idProject"])){
   header('location:index.php' );
   exit();
}
//recoger datos
$id=$_REQUEST["id"];
$idProject = $_REQUEST["idProject"];

$controlador = new tasksController();
$borrado=$controlador->borrar ($idProject, $id);