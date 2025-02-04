<?php
require_once "controllers/usersController.php";
require_once "controllers/digimonsController.php";
require_once "controllers/teamUsersController.php";
require_once "controllers/digimonsUsersController.php";
if (!isset($_REQUEST['id'])) {
    header("location:index.php");
    exit();
    // si no ponemos exit despues de header redirecciona al finalizar la pagina 
    // ejecutando el código que viene a continuación, aunque no llegues a verlo
    // No poner exit puede provocar acciones no esperadas dificiles de depurar
}
$id = $_REQUEST['id'];
$controlador = new usersController();
$user = $controlador->ver($id);

$digimonController = new DigimonsController;
$teamUsersController = new TeamUsersController;
$digimonUserController = new DigimonsUsersController;
$cantidad = $digimonUserController->buscar("user_id", "equals", $user->id);
?>
<link rel="stylesheet" href="assets/css/user/show.css">
<main>
  <div class="background <?= ($id == $_SESSION['username']->id) ? 'background--self' : 'background--enemy'?>">
    <div class="baseContainer">
      <div class="baseContainer__leftContainer">
        <img class=leftContainer__img src="../Administrador/assets/img/users/<?= $user->username?>/profile.png">
      </div>
      <div class="baseContainer__rightContainer">
        <div class="baseContainer__topRightContainer <?= ($id == $_SESSION['username']->id) ? 'baseContainer__topRightContainer--self' : 'baseContainer__topRightContainer--enemy'?>">
          <h1><?=$user->username?></h1>
          <h1>ID:<?=$user->id?></h1>
        </div>
        <div class="baseContainer__midContainer <?= ($id == $_SESSION['username']->id) ? 'baseContainer__midContainer--self' : 'baseContainer__midContainer--enemy'?>" >
          <div class="midContainer__rowContainer">
            <div class="rowContainer__stats rowContainer__stats--wins">
              <h4>- Partidas Jugadas -</h4>
              <h1> <?=$user->wins+$user->loses?> Partidas </h1>
              <h1> <?=$user->wins?> Victorias </h1>
            </div>
            <div class="rowContainer__stats rowContainer__stats--ownedDigimon">
              <h4>- Digimon obtenidos -</h4>
              <h1><?=count($cantidad)?> Digimon</h1>
            </div>
          </div>
          <div class="midContainer__rowContainer">
            <div class="rowContainer__team <?= ($id == $_SESSION['username']->id) ? 'rowContainer__team--self' : 'rowContainer__team--enemy'?>">
              <b>-- Equipo --</b>
              <div class="team__cardContainer">
                  <?php
                      $digimons = $teamUsersController->ver($user->id);
                      foreach($digimons as $equipped) {
                          $digimon = $digimonController->ver($equipped->digimon_id);
                          ?>
                              <a href="index.php?tabla=digimons&accion=ver&id=<?=$digimon->id?>">
                                  <div class="card__container card__container--level<?=$digimon->level?> card__container--animated">
                                      <div id="card__topArea" class="card__topArea">
                                          <h4 class="card__title"><?= $digimon->name?> | #<?=$digimon->id?></h4>
                                          <img class="card__image" src="../Administrador/assets/img/digimons/<?= $digimon->name?>/base.png"><br>
                                      </div>
                                  </div>
                              </a>
                          <?php
                      }
                  ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>