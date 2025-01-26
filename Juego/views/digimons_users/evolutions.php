<?php
require_once "controllers/digimonsController.php";
require_once "controllers/digimonsUsersController.php";
if (!isset($_REQUEST['digimon_id'], $_REQUEST['next_evolution_id'])) {
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

<style>
    body {
        background-image: url("assets/img/evolution.png");
        background-size: cover;
    }

    #contenido {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }

    #inner-contenido {
        text-align: center;
        z-index: 1;
        background-color: #363636;
        border-radius: 2rem;
        padding: 3rem;
    }

    #digimonCards{
        display: flex;
        justify-content: space-around;
        background-color: red;
        background: conic-gradient(from 180deg at 50% 70%,hsla(0,0%,98%,1) 0deg,#eec32d 72.0000010728836deg,#ec4b4b 144.0000021457672deg,#709ab9 216.00000858306885deg,#4dffbf 288.0000042915344deg,hsla(0,0%,98%,1) 1turn);
        mask:
            radial-gradient(circle at 50% 50%, white 2px, transparent 2.5px) 50% 50% / var(--size) var(--size),
            url("https://assets.codepen.io/605876/noise-mask.png") 256px 50% / 256px 256px;
        mask-composite: intersect;
        /* animation: flicker 20s infinite linear; */
    }

    #confirm-prompt {
        border: red 1px solid;
        border-radius: 2rem;
        margin-top: 2rem;
        padding: 2rem;
        color: white;
    }

    #confirm-prompt h5{
        color: red;
    }

    .card-title {
    text-align: center;
    }

    .top-card {
        display: flex;
        justify-content: space-between;
    }

    .top-card .top-left-card{
        display: flex;
        justify-content: space-between;
    }

    /* Estilos generales de cartas */
    .card {
        display: flex;
        border: 5px solid black;
        border-radius: 0.5rem;
        padding: 5px;
        transition: box-shadow 0.3s ease-in-out;
        transform-style: preserve-3d;
        
    }

    .card .digimonImage {
        border: 1px solid black;
        border-radius: 0.5rem;
        object-fit: cover;
    }

    .card .middle-card {
        display: flex;
        justify-content: space-evenly;
    }

    .card .middle-card button {
        border: none;
        background: none;
    }

    .card .bottom-card {
        border-radius: 0.5rem;
        border: 1px solid black;
        padding: 5px;
    }

    /* Estilos específicos de cada nivel */
    .card-level1 {
        background-color: #42A5F5;
    }

    .card-level1 .bottom-card {
        background-color: #1E88E5;
    }

    .card-level2 {
        background-color: #66BB6A;
    }

    .card-level2 .bottom-card {
        background-color: #388E3C;
    }

    .card-level3 {
        background-color: #EF5350;
    }

    .card-level3 .bottom-card {
        background-color: #D32F2F;
    }

    .card-level4 {
        background-color: #FFEB3B;
    }

    .card-level4 .bottom-card {
        background-color: #FBC02D;
    }

    /* Animaciones */
    .card:hover {
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
    }

    .card-level1:hover {
        box-shadow: 0 0 20px rgba(66, 165, 245, 0.8);
    }

    .card-level2:hover {
        box-shadow: 0 0 20px rgba(76, 175, 80, 0.8);
    }

    .card-level3:hover {
        box-shadow: 0 0 20px rgba(255, 87, 34, 0.8);
    }

    .card-level4:hover {
        box-shadow: 0 0 20px rgba(255, 235, 59, 0.8);
    }
</style>
<!-- <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4"> -->
<main class="">
    <div id="contenido" class="b">
        <div id="inner-contenido" class="b">
            <div id="digimonCards">
                <div class="card card-level<?=$digimon->level?>">
                    <div class="top-card">
                        <div class="top-left-card">
                            <img src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="20px">
                            <h1 class="card-level-corner"> Lv.1 <h1>
                        </div>
                        <div class="top-right-card">
                            <h5 class="card-id-corner">#<?=$digimon->id?><h5>
                        </div>
                    </div>
                    <img class="digimonImage" src="../Administrador/assets/img/digimons/<?= $digimon->name?>/base.png" width="150">
                    <div class="middle-card">
                        <h3 class="card-title"><?=$digimon->name?></h3>
                        <button>
                        <svg id="mirar" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"></path>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"></path>
                        </svg>
                        </button>
                    </div>
                    <div class="bottom-card" hidden>
                        <p class="card-type">
                        <img src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">Tipo: <?=$digimon->type?>
                        </p>
                        <p class="card-health">
                        <img src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">Vida: <?=$digimon->health?>
                        </p>
                        <p class="card-attack">
                        <img src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">Ataque: <?=$digimon->attack?>
                        </p>
                        <p class="card-defense">
                        <img src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">Defensa: <?=$digimon->defense?>
                        </p>
                        <p class="card-speed">
                        <img src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">Velocidad: <?=$digimon->speed?>
                        </p>
                        <br>
                    </div>
                </div>

                <div class="card card-level<?=$digievolucionado->level?>">
                    <div class="top-card">
                        <div top-left-card>
                        <h1 class="card-level-corner">
                            <img src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="20px"> Lv.1 <h1>
                        </div>
                        <div top-right-card>
                        <h5 class="card-id-corner">#<?=$digievolucionado->id?><h5>
                        </div>
                    </div>
                    <img class="digimonImage" src="../Administrador/assets/img/digimons/<?= $digievolucionado->name?>/base.png" width="150">
                    <div class="middle-card">
                        <h3 class="card-title"><?=$digievolucionado->name?></h3>
                        <button>
                        <svg id="mirar" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"></path>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"></path>
                        </svg>
                        </button>
                    </div>
                    <div class="bottom-card" hidden>
                        <p class="card-type">
                        <img src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">Tipo: <?=$digievolucionado->type?>
                        </p>
                        <p class="card-health">
                        <img src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">Vida: <?=$digievolucionado->health?>
                        </p>
                        <p class="card-attack">
                        <img src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">Ataque: <?=$digievolucionado->attack?>
                        </p>
                        <p class="card-defense">
                        <img src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">Defensa: <?=$digievolucionado->defense?>
                        </p>
                        <p class="card-speed">
                        <img src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">Velocidad: <?=$digievolucionado->speed?>
                        </p>
                        <br>
                    </div>
                </div>

            </div>
            <div id="confirm-prompt">
                <h4>Estás a punto de evolucionar a tu <?=$digimon->name?> en un <?=$digievolucionado->name?>, esto consumirá una de tus Digievoluciones</h4>
                <h5 id="alert">*Esta acción no se puede deshacer*</h5>
                <a href="index.php"><button class="btn btn-danger">Volver</button></a>
                <a href="index.php?tabla=digimons_users&accion=digievolucionar&digimon_id=<?=$digimon->id?>&next_evolution_id=<?=$digievolucionado->id?>&status=ok"><button id="evolve" class="btn btn-success border-green">Evolucionar (<img src="assets/img/evolutions.png" width="15px"> -1)</button></a>
            </div>
        </div>
    </div>
</main>