<?php
require_once("controllers/digimonsUsersController.php");
$digiUserController = new DigimonsUsersController();
$userHasDigimon = $digiUserController->ver($_SESSION["username"]->id);

if ($userHasDigimon == null) {
  header("location: index.php?tabla=digimons_users&accion=evento");
  exit();
}
if (isset($_SESSION['digievolutionReward']) || isset($_SESSION['digimonReward'])) {
  header('location:index.php?tabla=offline&accion=premio');
  exit;
}



?>
<style>
  body {
    background-color: black;
  }
</style>
<!--  main class class="col-md-9 ms-sm-auto col-lg-10 px-md-4"  -->
<main class="">
  <div class="fondoInicio">
    <div id="contenido">
      <div class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
          <h1 class="h2">Bienvenido <?=$_SESSION["username"]->username?></h1>
        </div>
        <div class="fila1">
          <a href="index.php?tabla=digimons_users&accion=listar">
            <button id="misDigimon"><span>Mis Digimon</span></button>
          </a>
          <a href="index.php?tabla=team_users&accion=ver">
            <button id="miEquipo"><span>Mi Equipo</span></button>
          </a>
        </div>
        <div class="fila2">
          <a href="index.php?tabla=offline&accion=buscar">
            <button id="combateOffline"><span>Combate Offline</span></button>
          </a>
          <a href="">
            <button id="combateOnline"><span>Combate Online</span></button>
          </a>
        </div>
      </div>
  </div>
</main>