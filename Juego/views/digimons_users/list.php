<?php
require_once "controllers/digimonsUsersController.php";
require_once "controllers/digimonsController.php";

$mensaje = "";
$clase = "alert alert-success";
$visibilidad = "hidden";
$digimonsController = new DigimonsController();
$digiUserController = new DigimonsUsersController();
$userDigimon = $digiUserController->buscar("user_id", "equals", $_SESSION["username"]->id);
?>
<link rel="stylesheet" href="assets/css/digimons_users/list.css">
<main>
    <div class="baseContainer">
        <a href="index.php" class="btn btn-secondary">Volver</a>
        <div class="baseContainer__grid">
            <?php
                foreach ($userDigimon as $digimonOwned) {
                    $digimon = $digimonsController->ver($digimonOwned->digimon_id);
                    ?>
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
                    <?php
                }
            ?>
        </div>
    </div>
</main>