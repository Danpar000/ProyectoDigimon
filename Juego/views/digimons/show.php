<?php
require_once "controllers/digimonsController.php";
require_once "controllers/digimonsUsersController.php";
if (!isset($_REQUEST['id']) || filter_var($_REQUEST["id"], FILTER_VALIDATE_INT) == false) {
    header("location:index.php");
    exit();
}

$id = $_REQUEST['id'];
$controlador = new DigimonsController();
$digimonUserController = new DigimonsUsersController();
$digimon = $controlador->ver($id);

if ($digimon == null) {
    header("location: index.php?tabla=digimons_users&accion=listar");
    exit();
}


$owned = false;
$alreadyEvolved = false;
if (isset($digimon->next_evolution_id)) {
    $next_digimon = $controlador->ver($digimon->next_evolution_id);
    $seeNextDigimon = "<a href='index.php?tabla=digimons&accion=ver&id={$next_digimon->id}'>{$next_digimon->name}</a>";

    $list = $digimonUserController->buscar("user_id", "equals", $_SESSION["username"]->id);
    foreach ($list as $digimonOwned) {
        // Comprueba si tienes al digimon a evolucionar
        if ($digimon->id == $digimonOwned->digimon_id) {
            $owned = true;
        } else if($digimonOwned->digimon_id == $next_digimon->id) {
            // Comprueba si la siguiente evolución ya la tienes
            $alreadyEvolved = true;
        }
    }
}

// Crear botón evoluciones
if ($_SESSION["username"]->digievolutions <= 0) {
    $evolveButton = "<button class='btn btn-warning' disabled>Faltan Digievoluciones</button>" ;
} elseif ($alreadyEvolved) {
    $evolveButton = "<button class='btn btn-secondary' disabled>Ya has evolucionado</button>" ;
} elseif ($owned) {
    $evolveButton = "<a href='index.php?tabla=digimons_users&accion=digievolucionar&digimon_id={$digimon->id}&next_evolution_id={$digimon->next_evolution_id}' class='btn btn-success'>Evolucionar</a>";
} else {
    $evolveButton = "";
}

?>
<link rel="stylesheet" href="assets/css/digimons/show.css">
<main class="">
    <div class="baseContainer baseContainer--level<?=$digimon->level?>">
        <div class="card__container card__container--level<?=$digimon->level?> card__container--animated">
            <div id="card__topArea" class="card__topArea">
                <h4 class="card__title"><?= $digimon->name?> | #<?=$digimon->id?></h4>
                <img class="card__image" src="../Administrador/assets/img/digimons/<?= $digimon->name?>/base.png"><br>
            </div>
            <div id="card__backwardsArea" class='card__backwardsArea card__backwardsArea--level<?= $digimon->level?>' hidden>
                <div class="card__type">
                    <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                    <p>Tipo: <?=$digimon->type?></p>
                    <img class="card__statImg card__statImg--reverse"src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                </div>
                <div class="card__type card__type-health">
                    <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                    <p>Vida: <?=$digimon->health?></p>
                    <img class="card__statImg card__statImg--reverse"src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                </div>
                <div class="card__type card__type-attack">
                    <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                    <p>Ataque: <?=$digimon->attack?></p>
                    <img class="card__statImg card__statImg--reverse"src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                </div>
                <div class="card__type card__type-attack">
                    <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                    <p>Defensa: <?=$digimon->defense?></p>
                    <img class="card__statImg card__statImg--reverse"src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                </div>
                <div class="card__type card__type-speed">
                    <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                    <p>Velocidad: <?=$digimon->speed?></p>
                    <img class="card__statImg card__statImg--reverse"src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                </div>
                <?php
                if (isset($next_digimon)) {
                    ?>
                    <div class="card__type card__type-neid">
                        <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                        <p>Evolución: <?=$seeNextDigimon?></p>
                        <img class="card__statImg card__statImg--reverse"src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="options">
            <a href="index.php?tabla=digimons_users&accion=listar" class="btn btn-primary">Volver</a>
            <button class="btn btn-success"id="rotar">Rotar</button>
            <?= isset($evolveButton, $next_digimon) ? $evolveButton : "" ?>
        </div>
    </div>
</main>