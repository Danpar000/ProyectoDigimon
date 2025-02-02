<?php
require_once "controllers/digimonsUsersController.php";
require_once "controllers/digimonsController.php";

if (!isset($_SESSION['username'],$_REQUEST['finalizado'])) {
    header("location:index.php");
    exit();
}
$digimonsController = new DigimonsController();
$digiUserController = new DigimonsUsersController();
$userDigimon = $digiUserController->buscar("user_id", "equals", $_SESSION["username"]->id);
$digimonsLVL1 = $digimonsController->buscar("level","equals","1");
foreach ($userDigimon as $digimon) {
    if ($digimon->type==1) {
        $allOwnDigiLVL1 .= [];
    }
}
if (count($allOwnDigiLVL1)==count($digimonsLVL1)) {
    echo "Ya tienes todos los digimones de nivel 1";
    echo "<a href='index.php'>Aceptar</a>";
}else{
    $obtenido=true;
    while($obtenido){
        $digiReward=$digimonsLVL1[rand(0,count($digimonsLVL1)-1)];
        $obtenido=false;
        for ($i=0; $i < count($allOwnDigiLVL1); $i++) { 
            if ($digiReward==$allOwnDigiLVL1[$i]) {
                $obtenido=true;
                $i=count($allOwnDigiLVL1);
            }
        }
    }
    echo "Conseguiste un: ".$digiReward->name.", id: ".$digiReward->id;
}
?>