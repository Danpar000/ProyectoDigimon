<?php
require_once "controllers/digimonsUsersController.php";
require_once "controllers/digimonsController.php";
require_once("controllers/usersController.php");

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
}

$userController=new UsersController();
$digimonsController = new DigimonsController();
$digiUserController = new DigimonsUsersController();

if (isset($_SESSION['digimonReward'])){
    unset($_SESSION['digimonReward']);
    unset($_SESSION["currentVictories"]);
    $userDigimon = $digiUserController->buscar("user_id", "equals", $_SESSION["username"]->id);
    $digimonsLVL1 = $digimonsController->buscar("level","equals","1");
    $allOwnDigiLVL1 = [];
    foreach ($userDigimon as $digiOwn) {
        $digimon=$digimonsController->ver($digiOwn->digimon_id);
        if ($digimon->level==1) {
            array_push($allOwnDigiLVL1, $digimon);
        }
    }
    if (count($allOwnDigiLVL1)==count($digimonsLVL1)) {
        ?>
        <div class="baseContainer">
            <h3 style="color: white;">¡Has obtenido una Digievolución por tener Todos los Digimons!</h3>
            <a href="index.php">
                <?php
                echo ("<div class='card__container card__container--animated card__container--levelspecial card__container--selected'>")
                ?>
                    <div class="card__topArea">
                        <h5>S P E C I A L</h5>
                        <h4 class="card__title">Digievolución</h4>
                        <img class="card__image" src="../Juego/assets/img/evolutions.png"><br>
                    </div>
                </div>
            </a>
            <a class="btn btn-success" href="index.php">Aceptar</a>
        </div>
        <?php
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
        ?>
            
        <div class="baseContainer">
            <h3 style="color: white;">¡Has obtenido un <?= $digiReward->name?>!</h3>
            <a href="index.php?tabla=digimons&accion=ver&id=<?=$digiReward->id?>">
                <?php
                echo ("<div class='card__container card__container--animated card__container--level{$digiReward->level}'>")
                ?>
                    <div class="card__topArea">
                        <h5><?=$digiReward->type?></h5>
                        <h4 class="card__title"><?= $digiReward->name?> | Lvl <?=$digiReward->level?></h4>
                        <img class="card__image" src="../Administrador/assets/img/digimons/<?= $digiReward->name?>/base.png"><br>
                    </div>
                </div>
            </a>
            <a class="btn btn-success" href="index.php">Aceptar</a>
        </div>
        <?php
        $digiUserController->addDigimon($_SESSION["username"]->id, $digiReward->id);
    }
} else {
    if (isset($_SESSION['digievolutionReward'])){
        $userController->editarDigievolucion($_SESSION['username']->id,$_SESSION['username']->digievolutions+1);
        $_SESSION['username']->digievolutions=$_SESSION['username']->digievolutions+1;
        ?>

        <div class="baseContainer">
            <h3 style="color: white;">¡Has obtenido una Digievolución!</h3>
            <a href="index.php">
                <?php
                echo ("<div class='card__container card__container--animated card__container--levelspecial card__container--selected'>")
                ?>
                    <div class="card__topArea">
                        <h5>S P E C I A L</h5>
                        <h4 class="card__title">Digievolución</h4>
                        <img class="card__image" src="../Juego/assets/img/evolutions.png"><br>
                    </div>
                </div>
            </a>
            <a class="btn btn-success" href="index.php">Aceptar</a>
        </div>

        <?php
        
        unset($_SESSION['digievolutionReward']);
    } else {
        header("location: index.php");
        exit();
    }
}
?>
<link rel="stylesheet" href="assets/css/offline/prize.css">
<a href='index.php'>Aceptar</a>