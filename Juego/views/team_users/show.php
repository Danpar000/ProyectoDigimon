<?php
require_once "controllers/digimonsController.php";
require_once "controllers/teamUsersController.php";
$controlador = new DigimonsController();
$teamUsersController = new TeamUsersController();
$myTeam = $teamUsersController->ver($_SESSION["username"]->id);
?>
<link rel="stylesheet" href="assets/css/team_users/show.css">
<main>
    <div class="baseContainer">
        <div class="baseContainer__grid">
            <?php
                foreach ($myTeam as $digimons) {
                    $digimon = $controlador->ver($digimons->digimon_id);
                    echo ("<div class='card__container card__container--level{$digimon->level}' id='{$digimon->id}'>");
                    ?>
                        <div id="card__topArea" class="card__topArea">
                            <h4 class="card__title"><?= $digimon->name?> | Lvl <?=$digimon->level?></h4>
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
                <?php
                }
            ?>
        <div>
    </div>
    <div class="baseContainer__selectForm" >
        <label id="baseContainer__label" for="baseContainer__formSwapDigimons"><h5>Elige a un Digimon para Cambiarlo</h5></label>
        <select id="baseContainer__formSwapDigimons" name="baseContainer__formSwapDigimons" class="form-select" aria-label="Selecciona un digimon" disabled></select>
        <input type="number" hidden id="oldDigimon_id">
        <a href="index.php" class="btn btn-primary">Volver</a>
    </div>
</main>