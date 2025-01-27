<?php
require_once "controllers/digimonsController.php";
require_once "controllers/teamUsersController.php";
$controlador = new DigimonsController();
$teamUsersController = new TeamUsersController();
$myTeam = $teamUsersController->ver($_SESSION["username"]->id);
if ($myTeam == null) {
    header("location: index.php");
    exit();
}
?>
<style>
    #contenido{
        display: flex;
        justify-content: space-around;
    }
    .card{
        width: 18rem;
        text-align: center;
        padding: 5px;
    }

    .card div *{
        margin: 2px 0px;
    }
</style>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Mi Equipo</h1>
    </div>
    <div id="contenido">
        <?php
            foreach ($myTeam as $digimons) {
                $digimon = $controlador->ver($digimons->digimon_id);
                ?>
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
                    <!-- <div class="card" id="<?= $digimon->id?>">
                        <div>
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
                    </div> -->
                <?php
            }
        ?>
    </div>
</main>