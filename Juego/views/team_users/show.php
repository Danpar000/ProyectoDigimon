<?php
require_once "controllers/digimonsController.php";
require_once "controllers/teamUsersController.php";
$controlador = new DigimonsController();
$teamUsersController = new TeamUsersController();
$myTeam = $teamUsersController->ver($_SESSION["username"]->id);
?>
<style>
    @keyframes brillo {
        0% {
            box-shadow: 0 0 20px rgba(66, 165, 245, 1); /* Nivel 1 - Azul */
        }
        25% {
            box-shadow: 0 0 20px rgba(76, 175, 80, 0.8); /* Nivel 2 - Verde */
        }
        50% {
            box-shadow: 0 0 20px rgba(255, 87, 34, 0.8); /* Nivel 3 - Naranja */
        }
        75% {
            box-shadow: 0 0 20px rgba(255, 235, 59, 0.8); /* Nivel 4 - Amarillo */
        }
        100% {
            box-shadow: 0 0 20px rgba(66, 165, 245, 1); /* Nivel 1 - Azul (vuelve a empezar) */
        }
    }

    a {
        color: black;
    }

    a:hover {
        color: white;
    }

    body {
        background-color: black;
        background-image: 
            linear-gradient(to right, rgba(255, 255, 255, 0.2) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(255, 255, 255, 0.2) 1px, transparent 1px);
        background-size: 20px 20px;
        height: 200vh;
        width: 100%;
        margin: 0;
    }

    .baseContainer {
        background: rgb(83,83,83);
        background: radial-gradient(circle, rgba(83,83,83,1) 0%, rgba(181,181,181,1) 51%, rgba(46,46,46,1) 100%);
        border: gray 0.2rem solid;
        border-radius: 1rem;
        display: flex;
        justify-content: center;
        margin: 2rem;
    }

    .baseContainer__grid {
        background-color: gray;
        border: 1rem solid darkgray;
        border-radius: 2rem;
        padding: 2rem;
        margin: 4rem 2rem;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: repeat(2, 0.3fr);
        gap: 2rem;
        max-height: 70vh;
        overflow-y: auto;
        justify-items: center;
    }

    .baseContainer__grid::-webkit-scrollbar {
        display: none;
    }

    .baseContainer__optionsContainer {
        background-color: gray;
        border: 0.5rem solid darkgray;
        border-radius: 2rem;
        padding: 2rem;
        margin: 4rem 2rem;
        display: flex;
        flex-direction: column;
        justify-content: start;
    }

    .options__type {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .card__container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        text-align: center;
        padding: 1rem;
        border: 0.2rem solid;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        min-height: 35vh;
        max-height: 35vh;
        min-width: 35vh;
        max-width: 35vh;
        overflow: hidden;
    }

    .card__container--animated {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        transition: box-shadow 0.03s ease-in-out;
        transform-style: preserve-3d;
    }

    .card__image {
        overflow: hidden;
        min-height: 25vh;
        max-height: 25vh;
        min-width: 25vh;
        max-width: 25vh;
        object-fit: cover;
        border: 0.15rem solid;
        border-radius: 8px;
    }

    .card__type{
        display: flex;
        align-content: center;
        justify-content: space-around;
        border-radius: 1rem;
    }

    .card__backwardsArea {
        width: 100%;
        display: flex;
        flex-direction: column;
        border-radius: 0.2rem;
    }

    .card__backwardsArea * p{
        font-size: 1.5em;
        margin: auto;
        width: 100%;
    }

    .card__bottomArea {
        display: flex;
        align-content: center;
        justify-content: center;
        width: 100%;
    }

    #mirar {
        background: none;
        border: 0;
    }

    /* -- NIVELES --*/
    .card__container--level1 {
        background: rgb(66,165,245);
        background: radial-gradient(circle, rgba(66,165,245,1) 0%, rgba(30,136,229,1) 51%);
    }
    .card__backwardsArea--level1{
        background-color: rgb(0, 108, 196);
        border: 0.2rem solid rgb(0, 73, 133);
    }

    .card__container--level2 {
        background: rgb(102,187,106);
        background: radial-gradient(circle, rgba(102,187,106,1) 11%, rgba(56,142,60,1) 51%);
    }
    .card__backwardsArea--level2{
        background-color: rgb(76, 140, 80);
        border: 0.2rem solid rgb(53, 99, 56);
    }

    .card__container--level3 {
        background: rgb(162, 84, 82);
        background: radial-gradient(circle, rgba(214,121,119,1) 0%, rgba(211,47,47,1) 87%);
    }
    .card__backwardsArea--level3{
        background-color: rgb(192, 104, 102);
        border: 0.2rem solid rgb(108, 59, 58);
    }

    .card__container--level4 {
        background: rgb(255,243,141);
        background: radial-gradient(circle, rgba(255,243,141,1) 0%, rgba(255,235,59,1) 88%);
    }
    .card__backwardsArea--level4{
        background-color: rgb(189, 173, 82);
        border: 0.2rem solid rgb(137, 124, 61);
    }

    .card__statImage--reverse {
        scale: -2rem;
    }

    /* -- ANIMACIONES -- */
    .card__container--animated:hover {
        transform: scale(1.5);
    }

    .card__container--level1:hover {
        box-shadow: 0 0 20px rgba(66, 165, 245, 1);
    }

    .card__container--level2:hover {
        box-shadow: 0 0 20px rgba(76, 175, 80, 0.8);
    }

    .card__container--level3:hover {
        box-shadow: 0 0 20px rgba(255, 87, 34, 0.8);
    }

    .card__container--level4:hover {
        box-shadow: 0 0 20px rgba(255, 235, 59, 0.8);
    }

    .card__container--selected {
        transform: scale(1.05);
        animation: brillo 3s infinite alternate;
    }

    /* Diseño responsivo */
    @media (max-width: 768px) {
        .baseContainer__grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>
<main>
    <div class="baseContainer">
        <div class="baseContainer__grid">
            <?php
                foreach ($myTeam as $digimons) {
                    $digimon = $controlador->ver($digimons->digimon_id);
                    echo ("<div class='card__container card__container--level{$digimon->level}' id='{$digimon->id}'>");
                    ?>
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
                <?php
                }
            ?>
        <div>
    </div>
    <div class="baseContainer__selectForm" >
        <div>
            <label id="baseContainer__label" for="baseContainer__formSwapDigimons"><h5>Elige a un Digimon para Cambiarlo</h5></label>
            <select id="baseContainer__formSwapDigimons" name="baseContainer__formSwapDigimons" class="form-select" aria-label="Selecciona un digimon" disabled></select>
            <input type="number" hidden id="oldDigimon_id">
        </div>
    </div>
</main>