<?php
require_once("controllers/usersController.php");
require_once("controllers/digimonsController.php");
require_once("controllers/teamUsersController.php");

$teamController = new TeamUsersController(); 
$controllerUser = new UsersController();
$digimonController=new DigimonsController();
$users=$controllerUser->listar();


?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-start flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Salas |</h1>
        <button class="btn btn-primary mx-2">Crear sala</button>
        <h1 class="h3"> | </h1>
        <form action="index.php?tabla=digimons&accion=buscar&evento=filtrar" method="POST" class="d-flex">
            <div class="input-group">
                <input type="text" required class="form-control" id="busqueda" name="busqueda" placeholder="Buscar ID">
                <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Buscar</button>
            </div>
        </form>
    </div>
    <div id="contenido">
        <table class="table table-light table-hover">
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
                        <td><img class="digimonImage" src="../Administrador/assets/img/users/<?= $user->username?>/profile.png" width="50"></td>
                        <td><?= $user->username == $_SESSION['username']->username ? 'Doppelganger ('.$user->username.')' : $user->username?></td>
                        <td>
                        <?php
                        $rivalTeam = $teamController->ver($user->id);
                        foreach ($rivalTeam as $rivalDigimon) {
                        ?>
                        Nivel <?=$digimonController->ver($rivalDigimon->digimon_id)->level?>
                        <?php
                        }
                        ?>
                        </td>
                        <td><a href="index.php?tabla=offline&accion=partida&oponente=<?=$user->id?>"><button>Combatir</button></a></td>
                    </tr>
                <?php
            }
        }
        ?>
            </tbody>
        </table>  
        <!-- <table class="table table-light table-hover">
            <thead class="table-dark">
            <tr>
                <th scope="col">ID Sala</th>
                <th scope="col">Integrantes</th>
                <th scope="col">Estado</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
                 ACA LUEGO VA LA PARTE DE GENERAR EL JV CON LAS SALAS 
                 <tr>
                    <td>Aca va el id</td>
                    <td>
                        <p>(1) 12312312301231231230123123123012312312301231231230</p>
                        <p>(2) 12312312301231231230123123123012312312301231231230</p>
                    </td>
                    <td>En juego</td>
                    <td><a href="index.php?tabla=rooms&accion=join"><button>Unirse</button></a></td>    
                 </tr>
            </tbody>
        </table> -->
    </div>
</main>