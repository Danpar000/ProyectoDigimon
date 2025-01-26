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
        <h1 class="h3">Ver Digimon</h1>
    </div>
    <div id="contenido">
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
    </div>
</main>