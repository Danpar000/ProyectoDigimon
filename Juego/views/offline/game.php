<?php
require_once("controllers/teamUsersController.php");
require_once("controllers/digimonsController.php");
require_once("controllers/usersController.php");
if(!isset($_REQUEST['oponente']) || filter_var($_REQUEST["oponente"], FILTER_VALIDATE_INT) == false){
    header("location:index.php");
    exit();
} else if (isset($_SESSION["digievolutionReward"]) || isset($_SESSION["digimonReward"])){
    header('location:index.php?tabla=offline&accion=premio');
    exit();
}

// Compruebo si no hiciste F5
$userController=new UsersController();
$userStats=$userController->ver($_SESSION['username']->id);
$digimonController=new DigimonsController();
$teamController=new TeamUsersController();
$rivalTeam=$teamController->ver($_REQUEST['oponente']);
if (empty($rivalTeam)) {
    header("Location: index.php");
    exit();
}
$ownTeam=$teamController->ver($_SESSION['username']->id);
$rivalPoints=0;
$ownPoints=0;

function typeStats($type1,$type2){
    switch ($type1) {
        case 'Vacuna':
            if ($type2=="Virus") {
                return 10;
            }else if ($type2=="Animal") {
                return 5;
            }else if ($type2=="Planta") {
                return -5;
            }else if ($type2=="Elemental") {
                return -10;
            }
            break;
        case 'Virus':
            if ($type2=="Animal") {
                return 10;
            }else if ($type2=="Planta") {
                return 5;
            }else if ($type2=="Elemental") {
                return -5;
            }else if ($type2=="Vacuna") {
                return -10;
            }
            break;
        case 'Animal':
            if ($type2=="Planta") {
                return 10;
            }else if ($type2=="Elemental") {
                return 5;
            }else if ($type2=="Vacuna") {
                return -5;
            }else if ($type2=="Virus") {
                return -10;
            }
            break;
        case 'Planta':
            if ($type2=="Elemental") {
                return 10;
            }else if ($type2=="Vacuna") {
                return 5;
            }else if ($type2=="Virus") {
                return -5;
            }else if ($type2=="Animal") {
                return -10;
            }
            break;
        case 'Elemental':
            if ($type2=="Vacuna") {
                return 10;
            }else if ($type2=="Virus") {
                return 5;
            }else if ($type2=="Animal") {
                return -5;
            }else if ($type2=="Planta") {
                return -10;
            }
            break;
    }
}
?>
<link rel="stylesheet" href="assets/css/offline/game.css">
<main class="">
    <div class="combateContainer">
        <div class="combateContainer__results">
            <?php
            //resCombate = Ganado o Perdido, CSS clases combate__Ganado o combate__Perdido
            $resCombate="";
            for ($turno=0; $turno < 3; $turno++) { 
                $ownDigi=$digimonController->ver($ownTeam[$turno]->digimon_id);
                $rivalDigi=$digimonController->ver($rivalTeam[$turno]->digimon_id);
                $ataqueOwnDigi=$ownDigi->attack+$ownDigi->defense+typeStats($ownDigi->type,$rivalDigi->type)+rand(1,50);
                $ataqueRivalDigi=$rivalDigi->attack+$rivalDigi->defense+typeStats($rivalDigi->type,$ownDigi->type)+rand(1,50);
                if ($ataqueOwnDigi>$ataqueRivalDigi) {
                    $ownPoints++;
                    $resCombate="Ganado";
            ?> 
                <div class="results__<?=$turno+1?> results__<?=$turno+1?>--<?=$resCombate?>">
                    <p>Ganador - <?=$_SESSION['username']->username?>'s <?=$ownDigi->name?></p>
                    <div class="results__<?=$turno+1?>__data">
                        <img src="../Administrador/assets/img/digimons/<?= $ownDigi->name?>/victory.png" width="150"><br>
                        <p><?=$ataqueOwnDigi?>AP VS <?=$ataqueRivalDigi?>AP</p>
                        <img src="../Administrador/assets/img/digimons/<?= $rivalDigi->name?>/defeat.png" width="150"><br>
                    </div>
                </div>
            <?php
                }else if($ataqueOwnDigi<$ataqueRivalDigi){
                    $rivalPoints++;
                    $resCombate="Perdido";
            ?>
            <div class="results__<?=$turno+1?> results__<?=$turno+1?>--<?=$resCombate?>">
                <p>Ganador - Rival's <?=$rivalDigi->name?></p><br>
                <div class="results__<?=$turno+1?>__data">
                    <img src="../Administrador/assets/img/digimons/<?= $ownDigi->name?>/defeat.png" width="150"><br>
                    <p><?=$ataqueOwnDigi?>AP VS <?=$ataqueRivalDigi?>AP</p>
                    <img src="../Administrador/assets/img/digimons/<?= $rivalDigi->name?>/victory.png" width="150"><br>
                </div>
            </div>
            <?php
                }else{
                    $turno = $turno-1;        
                }
            }
            ?>
        <?php
            $resultado="";
            if ($ownPoints>$rivalPoints) {
                $_SESSION['username']->wins=$_SESSION['username']->wins+1;
                $userController->editarEstadisticas($_SESSION['username']->id, [ "wins" => $_SESSION['username']->wins, "loses" => $_SESSION['username']->loses]);
                $resultado="Victoria";
                if (isset($_SESSION["currentVictories"])) {
                    unset($_SESSION["currentVictories"]);
                }
            }elseif ($rivalPoints>$ownPoints) {
                $_SESSION['username']->loses=$_SESSION['username']->loses+1;
                $userController->editarEstadisticas($_SESSION['username']->id, ["wins" => $_SESSION['username']->wins, "loses" =>$_SESSION['username']->loses]);
                $resultado="Derrota";
            }
            $userStats=$userController->ver($_SESSION['username']->id);
            if($userStats->wins%10==0 && !isset($_SESSION["currentVictories"]) && $userStats->wins!=0){
                $_SESSION['digievolutionReward']=true;
                $_SESSION["currentVictories"] = true;
            }
            if(($userStats->wins+$userStats->loses)%10==0){
                $_SESSION['digimonReward']=true;
            }
            ?>
            <!-- CSS $resultado es igual a Victoria o Derrota -->
            <div class="combateContainer__results__bottom">
                <?= ($resultado == "Derrota") ? "<a class='btn btn-danger' href='index.php'>Volver a Inicio</a>" : "<a class='btn btn-success' href='index.php'>Volver a Inicio</a>"?>
            </div>
        </div>
    </div>
</main> 