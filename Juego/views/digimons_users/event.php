<?php
require_once("controllers/digimonsUsersController.php");
require_once("controllers/digimonsController.php");
$digiUserController = new DigimonsUsersController();
$digimonsController = new DigimonsController();
$userHasDigimon = $digiUserController->ver($_SESSION["username"]->id);

// Sacar si ya se tienen digimons (Evento de una sola vez);
if ($userHasDigimon != null) {
    header("location: index.php?tabla=digimons_users&accion=listar");
    exit();
}

// //Genero 3 Digimons
$digiUserController->generarDigimon($_SESSION["username"]->id);
$userHasDigimon = $digiUserController->buscar("user_id", "equals", $_SESSION["username"]->id);
?>
<link href="assets/css/digimons_users/event.css" rel="stylesheet">
<main>
    <div class="d-flex justify-content-center">
        <h1 style="color:white;" class="h3">¡Bienvenido! Toma un regalo por registrarte</h1>
    </div>
    <div class="contenido">
        <?php
        foreach($userHasDigimon as $digimonOwned) {
            $digimon = $digimonsController->ver($digimonOwned->digimon_id);
            ?>
            <div class="baseContainer">
                <h3 style="color: white;">¡Has obtenido un <?= $digimon->name?>!</h3>
                <a href="index.php?tabla=digimons&accion=ver&id=<?=$digimon->id?>">
                    <?php
                    echo ("<div class='card__container card__container--animated card__container--level{$digimon->level}'>")
                    ?>
                        <div class="card__topArea">
                            <h5><?=$digimon->type?></h5>
                            <h4 class="card__title"><?= $digimon->name?> | Lvl <?=$digimon->level?></h4>
                            <img class="card__image" src="../Administrador/assets/img/digimons/<?= $digimon->name?>/base.png"><br>
                        </div>
                    </div>
                </a>
                <a class="btn btn-success" href="index.php">Aceptar</a>
            </div>
            <?php
        }
        ?>
    </div>
</main>