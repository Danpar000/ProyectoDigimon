<?php
require_once "assets/php/funciones.php";
$cadenaErrores = "";
$cadena = "";
$errores = [];
$datos = [];
$visibilidad = "invisible";
if (isset($_REQUEST["error"])) {
  $errores = ($_SESSION["errores"]) ?? [];
  $datos = ($_SESSION["datos"]) ?? [];
  $cadena = "Atención Se han producido Errores";
  $visibilidad = "visible";
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h3">Añadir usuario</h1>
  </div>
  <div id="contenido">
    <?php
    // $cadena=(isset($_REQUEST["error"]))?"Error, ha fallado la inserción":"";
    // $visibilidad=(isset($_REQUEST["error"]))?"visible":"invisible";
    ?>
    <div id="alerta" class="alert alert-danger <?=$visibilidad?>" ><?=$cadena?></div>
    <form action="index.php?tabla=user&accion=guardar&evento=crear" method="POST" id="miform" name="miform" enctype="multipart/form-data">
      <div class="form-group">
        <label for="username">Usuario</label>
        <input type="text" required class="form-control" id="username" name="username" aria-describedby="username" placeholder="Introduce Usuario" value="<?= $_SESSION["datos"]["username"] ?? "" ?>" maxlength="50">
        <small id="username" class="form-text text-muted">Compartir tu usuario lo hace menos seguro.</small>
        <?= isset($errores["username"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "username") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" required class="form-control" id="password" name="password" placeholder="Password" value="<?= $_SESSION["datos"]["password"] ?? "" ?>" maxlength="255">
        <?= isset($errores["password"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "password") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
        <label for="digievolutions">Digievoluciones</label>
        <input type="number" required class="form-control" id="digievolutions" name="digievolutions" value="<?= $_SESSION["datos"]["digievolutions"] ?? "" ?>">
        <?= isset($errores["digievolutions"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "digievolutions") . '</div>' : ""; ?>
      </div>
      <div class="form-group">
        <label for="image">Foto de Perfil (Opcional)</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg, image/jpg, image/gif" value="<?= $_SESSION["datos"]["image"]["tmp_name"] ?? "" ?>">
        <?= isset($errores["image"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "image") . '</div>' : ""; ?>
      </div>
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a class="btn btn-danger" href="index.php">Cancelar</a>
    </form>

    <?php
    unset($_SESSION["datos"]);
    unset($_SESSION["errores"]);
    ?>
  </div>
</main>