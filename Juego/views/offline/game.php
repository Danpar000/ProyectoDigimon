<?php
require_once("controllers/teamUsersController.php");
require_once("controllers/digimonsController.php");
require_once("controllers/usersController.php");
if(!isset($_REQUEST['oponente'])){
    header("location:index.php");
    exit();
}

$userController=new UsersController();
$digimonController=new DigimonsController();
$teamController=new TeamUsersController();
// echo "Oponente: ".$_REQUEST['oponente'];
$rivalTeam=$teamController->ver($_REQUEST['oponente']);
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

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <?php
    for ($turno=0; $turno < 3; $turno++) { 
        $ownDigi=$digimonController->ver($ownTeam[$turno]->digimon_id);
        $rivalDigi=$digimonController->ver($rivalTeam[$turno]->digimon_id);
        $ataqueOwnDigi=$ownDigi->attack+$ownDigi->defense+typeStats($ownDigi->type,$rivalDigi->type)+rand(1,50);
        $ataqueRivalDigi=$rivalDigi->attack+$rivalDigi->defense+typeStats($rivalDigi->type,$ownDigi->type)+rand(1,50);
        echo "Ataque de tu digimon: ".$ataqueOwnDigi;
        echo "Ataque del digimon rival: ".$ataqueRivalDigi;
        if ($ataqueOwnDigi>$ataqueRivalDigi) {
            $ownPoints++;
    ?>        
    <p>Ganador de la ronda <?=$turno?>: YOU <?=$ownDigi->name?></p><br>
    <img src="../Administrador/assets/img/digimons/<?= $ownDigi->name?>/victory.png" width="150"><br>
    <p>VS</p>
    <img src="../Administrador/assets/img/digimons/<?= $rivalDigi->name?>/defeat.png" width="150"><br>
    <?php
        }else if($ataqueOwnDigi<$ataqueRivalDigi){
            $rivalPoints++;
    ?>
    <p>Ganador de la ronda <?=$turno?>: RIVAL <?=$rivalDigi->name?></p><br>
    <img src="../Administrador/assets/img/digimons/<?= $ownDigi->name?>/defeat.png" width="150"><br>
    <p>VS</p>
    <img src="../Administrador/assets/img/digimons/<?= $rivalDigi->name?>/victory.png" width="150"><br>
    <?php
        }else{
            $turno-1;        
        }
    }
    if ($ownPoints>$rivalPoints) {
        $_SESSION['username']->wins=$_SESSION['username']->wins+1;
        $controllerUser->editar($_SESSION['username']->id,
        [$_SESSION['username']->username,
        $_SESSION['username']->password,
        $_SESSION['username']->wins+1,
        $_SESSION['username']->loses]);
    ?>
    <p>Victoria</p>
    <?php
    }elseif ($rivalPoints>$ownPoints) {
        $_SESSION['username']->loses=$_SESSION['username']->loses+1;
        $controllerUser->editar($_SESSION['username']->id,
        [$_SESSION['username']->username,
        $_SESSION['username']->password,
        $_SESSION['username']->wins,
        $_SESSION['username']->loses+1]);
    ?>
    <p>Derrota</p>
    <?php
    }
    $userStats=$userController->ver($_SESSION['username']->id);
    if($userStats->wins%10==0){
        $userController->editarDigievolucion($_SESSION['username']->id,$_SESSION['username']->$digievolutions+1);
        $_SESSION['username']->$digievolutions=$_SESSION['username']->$digievolutions+1;
    }
    if(($userStats->wins+$userStats->loses)%10==0){
        echo "<a href='index.php?tabla=offline&accion=premio&finalizado=true'>Aceptar</a>";
    }else{
        echo "<a href='index.php'>Aceptar</a>";
    }
    ?>
</main 