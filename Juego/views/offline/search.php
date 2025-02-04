<?php
require_once("controllers/usersController.php");
require_once("controllers/digimonsController.php");
require_once("controllers/teamUsersController.php");

$teamController = new TeamUsersController(); 
$controllerUser = new UsersController();
$digimonController=new DigimonsController();
$users=$controllerUser->listar();


?>
<link rel="stylesheet" href="assets/css/offline/search.css">
<main class="">
    <div class="baseContent">
        <div class="table__Container">
            <!-- <div class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h3>Salas |</h3>
                <form action="" method="POST" class="d-flex">
                    <div class="input-group">
                        <input type="text" required class="form-control" id="busqueda" name="busqueda" placeholder="Buscar ID">
                        <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Buscar</button>
                    </div>
                </form>
            </div> -->
            <!-- <h1>daojdaso</h1> -->
            <table class="">
                <thead class="table-dark">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Oponente</th>
                        <th scope="col">Dificultad</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
            <?php
            foreach ($users as $user) {
                $rivalTeam = $teamController->ver($user->id);
                if ($rivalTeam != null) {
                    ?>
                        <tr>
                            <td>
                                <a href="index.php?tabla=user&accion=ver&id=<?=$user->id?>"><img class="row__userImage" src="../Administrador/assets/img/users/<?= $user->username?>/profile.png" width="50"></a>
                            </td>
                            <td>
                                <a href="index.php?tabla=user&accion=ver&id=<?=$user->id?>"><?= $user->username == $_SESSION['username']->username ? 'Doppelganger ('.$user->username.')' : $user->username?></a>
                            </td>
                            <td>
                            <?php
                            $rivalTeam = $teamController->ver($user->id);
                            foreach ($rivalTeam as $rivalDigimon) {
                            ?>
                            Nivel <?=$digimonController->ver($rivalDigimon->digimon_id)->level?><br>
                            <?php
                            }
                            ?>
                            </td>
                            <td><a href="index.php?tabla=offline&accion=partida&oponente=<?=$user->id?>" class="btn btn-danger">Combatir</a></td>
                        </tr>
                    <?php
                }
            }
            ?>
                </tbody>
            </table>
        </div>
    </div>
</main>