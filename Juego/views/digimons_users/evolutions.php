<?php
require_once "controllers/digimonsController.php";
require_once "controllers/digimonsUsersController.php";
if (!isset($_REQUEST['digimon_id'], $_REQUEST['next_evolution_id']) || filter_var($_REQUEST["digimon_id"], FILTER_VALIDATE_INT) == false || filter_var($_REQUEST["next_evolution_id"], FILTER_VALIDATE_INT) == false) {
    header("location:index.php");
    exit();
} else {
    if ($_REQUEST['digimon_id'] == "" || $_REQUEST['next_evolution_id'] == ""){
        header("location:index.php");
        exit();
    }
    if (isset($_REQUEST['digimon_id'], $_REQUEST['next_evolution_id'], $_REQUEST['status'])) {
        // Controllers
        $controlador = new DigimonsController();
        $digiUserController = new DigimonsUsersController();
        // IDs "enviados"
        $digimon_id = $_REQUEST['digimon_id'];
        $next_evolution_id = $_REQUEST['next_evolution_id'];
        // IDs reales
        $digimon = $controlador->ver($digimon_id);
        $digievolucionado = $controlador->ver($next_evolution_id);

        // Comprobar que los IDs pasados son reales
        $digiUserController->evolveDigimon($_SESSION["username"]->id, $digimon, $digievolucionado, $_SESSION["username"]->digievolutions);
    }
}

// Controllers
$controlador = new DigimonsController();
$digiUserController = new DigimonsUsersController();

// IDs "enviados"
$digimon_id = $_REQUEST['digimon_id'];
$next_evolution_id = $_REQUEST['next_evolution_id'];

// IDs reales
$digimon = $controlador->ver($digimon_id);
$digievolucionado = $controlador->ver($next_evolution_id);
$list = $digiUserController->buscar("user_id", "equals", $_SESSION["username"]->id);
if (!$digiUserController->checkDigimonIDs($list, $digimon, $digievolucionado)) {
    header("location:index.php?tabla=digimons_users&accion=listar");
    exit();
}
?>


<link href="assets/css/digimons_users/evolutions.css" rel="stylesheet">
<main class="">
    <div id="contenido" class="b">
        <div id="inner-contenido" class="b">
            <div id="digimonCards">
                <div class="card__container card__container--level<?=$digimon->level?>">
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
                    </div>
                    <div class="card__bottomArea">
                        <button id="mirar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"></path>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="card__container card__container--level<?=$digievolucionado->level?>">
                    <div id="card__topArea" class="card__topArea">
                        <h4 class="card__title"><?= $digievolucionado->name?> | #<?=$digievolucionado->id?></h4>
                        <img class="card__image" src="../Administrador/assets/img/digimons/<?= $digievolucionado->name?>/base.png"><br>
                    </div>
                    <div id="card__backwardsArea" class='card__backwardsArea card__backwardsArea--level<?= $digievolucionado->level?>' hidden>
                        <div class="card__type">
                            <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                            <p>Tipo: <?=$digievolucionado->type?></p>
                            <img class="card__statImg card__statImg--reverse"src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                        </div>
                        <div class="card__type card__type-health">
                            <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                            <p>Vida: <?=$digievolucionado->health?></p>
                            <img class="card__statImg card__statImg--reverse"src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                        </div>
                        <div class="card__type card__type-attack">
                            <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                            <p>Ataque: <?=$digievolucionado->attack?></p>
                            <img class="card__statImg card__statImg--reverse"src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                        </div>
                        <div class="card__type card__type-attack">
                            <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                            <p>Defensa: <?=$digievolucionado->defense?></p>
                            <img class="card__statImg card__statImg--reverse"src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                        </div>
                        <div class="card__type card__type-speed">
                            <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                            <p>Velocidad: <?=$digievolucionado->speed?></p>
                            <img class="card__statImg card__statImg--reverse"src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                        </div>
                    </div>
                    <div class="card__bottomArea">
                        <button id="mirar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"></path>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"></path>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
            <div id="confirm-prompt">
                <h4>Estás a punto de evolucionar a tu <?=$digimon->name?> en un <?=$digievolucionado->name?>, esto consumirá una de tus Digievoluciones</h4>
                <h5 id="alert"><b>*Esta acción no se puede deshacer*</b></h5>
                <a href="index.php?tabla=digimons_users&accion=listar"><button class="btn btn-danger">Volver</button></a>
                <a href="index.php?tabla=digimons_users&accion=digievolucionar&digimon_id=<?=$digimon->id?>&next_evolution_id=<?=$digievolucionado->id?>&status=ok"><button id="evolve" class="btn btn-success border-green">Evolucionar (<img src="assets/img/evolutions.png" width="15px"> -1)</button></a>
            </div>
        </div>
    </div>
</main>