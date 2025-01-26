<?php
require_once "controllers/digimonsController.php";
require_once "controllers/digimonsUsersController.php";
if (!isset($_REQUEST['digimon_id'], $_REQUEST['next_evolution_id'])) {
    header("location:index.php");
    exit();
}

$digimon_id = $_REQUEST['digimon_id'];
$next_evolution_id = $_REQUEST['next_evolution_id'];
$controlador = new DigimonsController();
$digiUserController = new DigimonsUsersController();
$digimon = $controlador->ver($digimon_id);
$digievolucionado = $controlador->ver($next_evolution_id);

$digiUserController->borrarDigi($_SESSION["username"]->id,$digimon_id);
$digiUserController->addDigimon($_SESSION["username"]->id,$next_evolution_id);


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
    </div>
    <div id="contenido">
        <div class="card">
            <div >
                <h5 class="card-title">DIGIMON: <?= $digimon->name ?></h5>
                <p class="card-text">
                    Nivel: <?= $digimon->level?> <br>
                    Tipo: <?= $digimon->type ?><br>
                    Ataque: <?= $digimon->attack ?> <br>
                    Defensa: <?= $digimon->defense ?><br>
                </p>
                <img src="../Administrador/assets/img/digimons/<?= $digimon->name?>/base.png" width="150"><br>
            </div>
        </div>
        
        <div class="confirmarEvo">
            <h2>Digimon evolucionado</h2>
            <span>&#8594;</span><br>
            <!-- <a href="index.php?tabla=digimons_users&accion=digievolucionar&user_id=<?= $_SESSION["username"]->id ?>&digimon_id=<?= $digimon->id ?>&next_evolution_id=<?= $digimon->next_evolution_id ?>" class="btn btn-success">Evolucionar</a> -->
            <a href="index.php?tabla=digimons_users&accion=listar" class="btn btn-success">Aceptar</a>
        </div>
        <div class="card">
            <div >
                <h5 class="card-title">DIGIMON: <?= $digievolucionado->name ?></h5>
                <p class="card-text">
                    Nivel: <?= $digievolucionado->level?> <br>
                    Tipo: <?= $digievolucionado->type ?><br>
                    Ataque: <?= $digievolucionado->attack ?> <br>
                    Defensa: <?= $digievolucionado->defense ?><br>
                </p>
                <img src="../Administrador/assets/img/digimons/<?= $digievolucionado->name?>/base.png" width="150"><br>
            </div>
        </div>
    </div>
</main>