<?php
require_once "controllers/usersController.php";
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
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Ver Usuario</h1>
    </div>
    <div id="contenido">
        <div class="card">
            <div >
                <h5 class="card-title">ID: <?= $user->id ?> <br>USUARIO: <?= $user->username ?></h5>
                <p class="card-text">
                    Usuario: <?= $user->username ?> <br>
                    Digievoluciones: <?= $user->digievolutions ?> <br>
                </p>
                <img src="assets/img/users/<?= $user->username?>/profile.png" width="150"><br>
                <a href="index.php?tabla=user&accion=buscar&evento=todos" class="btn btn-primary">Ir a listar</a>
            </div>
        </div>
</main>