<?php
require_once("controllers/digimonsUsersController.php");
require_once "controllers/digimonsController.php";

$mensaje = "";
$clase = "alert alert-success";
$visibilidad = "hidden";
$digimonsController = new DigimonsController();
$digiUserController = new DigimonsUsersController();
$userDigimon = $digiUserController->buscar("user_id", "equals", $_SESSION["username"]->id);
?>

<style>
    main {
        background-image: url("assets/img/evolution.png");
        background-size: cover;
        width: 100%;
    }
</style>
<!-- <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4"> -->
<main class="main-container">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Digimones</h1>
    </div>
    <div id="contenido">
            <?php
        foreach($userDigimon as $digimonOwned) {
            $next_digimon = null;
            $digimon = $digimonsController->ver($digimonOwned->digimon_id);
            isset($digimon->next_evolution_id) ? $next_digimon = $digimonsController->ver($digimon->next_evolution_id) : "";
            ?>
            <div class="card" style="width: 18rem;">
                <a href="index.php?tabla=digimons&accion=ver&id=<?= $digimon->id ?>">
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
                    </div>
                </a>
            </div>
            
            <?php
        }
        ?>
        <a href="index.php" class="btn btn-primary">Volver</a>      
    </div>
</main>