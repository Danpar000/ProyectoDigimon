<?php
require_once "controllers/digimonsController.php";
//pagina invisible
if (!isset ($_REQUEST["id"])){
   header('location:index.php' );
   exit();
}
//recoger datos
$id=$_REQUEST["id"];

$controlador= new DigimonsController();
$controlador->borrar ($id);