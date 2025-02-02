<?php
require_once "controllers/digimonsController.php";
require_once "controllers/digimonsUsersController.php";
if (!isset($_REQUEST['id'])) {
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
<style>
    body {
        background-color: darkslategrey;
    }
    /* body {
        background-color: black;
        background-image: url("../Administrador/assets/img/digimons/<?=$digimon->name?>/base.png");
        background-position: center right;
        background-size: contain;
        background-repeat: no-repeat;
        background-attachment: fixed;
        position: relative;
    }

    body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle, rgba(0, 0, 0, 1) 55%, rgba(0, 0, 0, 0) 60%),
            linear-gradient(270deg, rgba(0, 0, 0, 1) 0.5%, rgba(0, 0, 0, 0) 15%),
            linear-gradient(0deg, rgba(0, 0, 0, 1) 5%, rgba(0, 0, 0, 0) 15%),
            linear-gradient(180deg, rgba(0, 0, 0, 1) 15%, rgba(0, 0, 0, 0) 35%);
        z-index: 1;
    } */

    .baseContainer {
        width: 100%;
        margin-top: -4rem;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 2rem;
        flex-direction: column;
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
        transform: rotateY(180deg);
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
    .baseContainer--level1 {
        background: rgb(0,72,179);
        background: radial-gradient(circle, rgba(0,72,179,1) 0%, rgba(0,102,255,1) 74%, rgba(0,66,166,1) 100%);
    }

    .baseContainer--level2 {
        background: rgb(102,187,106);
        background: radial-gradient(circle, rgba(102,187,106,0.7) 0%, rgba(56,142,60,1) 80%);
    }

    .baseContainer--level3 {
        background: rgb(162, 84, 82);
        background: radial-gradient(circle, rgba(162, 84, 82, 0.8) 0%, rgba(211, 47, 47, 1) 80%);
    }

    .baseContainer--level4 {
        background: rgb(255,243,141);
        background: radial-gradient(circle, rgba(255,243,141,0.7) 0%, rgba(255,235,59,1) 80%);
    }

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
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        transition: box-shadow 0.03s ease;
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

    /* Diseño responsivo */
    @media (max-width: 768px) {
        .baseContainer__grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @keyframes rotateCard {
        0% {
            transform: rotateY(0deg);
        }
        100% {
            transform: rotateY(180deg) scale(1.5);
        }
    }

    .card__container--rotate {
        animation: rotateCard 1s ease-in-out forwards;
    }

    .options {
        transition: transform 1s ease-in-out;
        
    }
    


    
</style>
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
    <!-- <div id="contenido">
        <div class="card">
            <div >
                <h5 class="card-title">ID: <?= $digimon->id ?> <br>DIGIMON: <?= $digimon->name ?></h5>
                <p class="card-text">
                    Nivel: <?= $digimon->level?> <br>
                    Tipo: <?= $digimon->type ?><br>
                    Ataque: <?= $digimon->attack ?> <br>
                    Defensa: <?= $digimon->defense ?><br>
                    Siguiente evolución: <?= isset($next_digimon) ? $seeNextDigimon : "Ninguna" ?><br>
                </p>
                <img src="../Administrador/assets/img/digimons/<?= $digimon->name?>/base.png" width="150"><br>
                <a href="index.php?tabla=digimons_users&accion=listar" class="btn btn-primary">Volver</a>
                <?= isset($evolveButton, $next_digimon) ? $evolveButton : "" ?>
            </div>
        </div>
    </div> -->
</main>