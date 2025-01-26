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
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Ver Usuario</h1>
    </div>
    <div id="contenido">
        <div class="card"  style="width: 18rem;">
            <div >
                <h5 class="card-title">ID: <?= $user->id ?> <br>USUARIO: <?= $user->username ?></h5>
                <p class="card-text">
                    Usuario: <?= $user->username ?> <br>
                    Victorias: <?= $user->wins ?> <br>
                    Derrotas: <?= $user->loses ?> <br>
                </p>
                <img src="../Administrador/assets/img/users/<?= $user->username?>/profile.png" width="150"><br>
                <a href="index.php" class="btn btn-primary">Volver a Inicio</a>
            </div>
        </div>
</main>