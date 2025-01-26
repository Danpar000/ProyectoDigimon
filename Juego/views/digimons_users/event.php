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
<style>
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
    <div class="d-flex justify-content-start flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">¡Bienvenido! Toma un regalo por registrarte</h1>
    </div>
    <div id="contenido">
        <h4>Has obtenido los siguientes Digimon</h4>
        <?php
        foreach($userHasDigimon as $digimonOwned) {
            $digimon = $digimonsController->ver($digimonOwned->digimon_id);
            ?>
            <div class="card" style="width: 18rem;">
                <div>
                    <h5 class="card-title">ID: <?= $digimon->id ?> <br>DIGIMON: <?= $digimon->name ?></h5>
                    <p class="card-text">
                        Nivel: <?= $digimon->level?> <br>
                        Tipo: <?= $digimon->type?><br>
                        Vida: <?= $digimon->health?> <br>
                        Ataque: <?= $digimon->attack?> <br>
                        Defensa: <?= $digimon->defense?><br>
                        Velocidad: <?= $digimon->speed?> <br>
                        Siguiente evolución: <?= isset($next_digimon) ? $next_digimon->name : "Ninguna" ?><br>
                    </p>
                    <img src="../Administrador/assets/img/digimons/<?= $digimon->name?>/base.png" width="150"><br>
                    <a href="index.php" class="btn btn-primary">Aceptar</a>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</main>