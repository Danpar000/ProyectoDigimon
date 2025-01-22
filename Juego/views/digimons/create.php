<?php
require_once "assets/php/funciones.php";
require_once "controllers/digimonsController.php";

$digimonsController = new DigimonsController();
$digimons = $digimonsController->listar();

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
    <h1 class="h3">Añadir digimon</h1>
  </div>
  <div id="contenido">
    <div id="alerta" class="alert alert-danger <?= $visibilidad ?>"><?= $cadena ?></div>
    <form action="index.php?tabla=digimons&accion=guardar&evento=crear" method="POST" id="miform" name="miform" enctype="multipart/form-data">
      <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" required class="form-control" id="name" name="name" maxlength="50"
          value="<?= $_SESSION["datos"]["name"] ?? "" ?>" aria-describedby="name" placeholder="Nombre">
        <?= isset($errores["name"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "name") . '</div>' : ""; ?>
      </div>

      <div class="form-group">
        <label for="attack">Ataque </label>
        <input type="number" required class="form-control" id="attack" name="attack"
          value="<?= $_SESSION["datos"]["attack"] ?? "" ?>" aria-describedby="attack" placeholder="Ataque del digimon">
        <?= isset($errores["attack"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "attack") . '</div>' : ""; ?>
      </div>


      <div class="form-group">
        <label for="defense">Defensa</label>
        <input type="number" required class="form-control" id="defense" name="defense"
          value="<?= $_SESSION["datos"]["defense"] ?? "" ?>" placeholder="Defensa del digimon">
        <?= isset($errores["defense"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "defense") . '</div>' : ""; ?>
      </div>

      <div class="form-group">
        <label for="type">Tipo </label>
        <select id="type" name="type" class="form-select" aria-label="Selecciona el tipo del digimon">
          <option value="Animal" <?= isset($_SESSION["datos"]["type"]) && $_SESSION["datos"]["type"] == "Animal" ? "selected" : "selected" ?>>Animal</option>
          <option value="Elemental" <?= isset($_SESSION["datos"]["type"]) && $_SESSION["datos"]["type"] == "Elemental" ? "selected" : "" ?>>Elemental</option>
          <option value="Planta" <?= isset($_SESSION["datos"]["type"]) && $_SESSION["datos"]["type"] == "Planta" ? "selected" : "" ?>>Planta</option>
          <option value="Vacuna" <?= isset($_SESSION["datos"]["type"]) && $_SESSION["datos"]["type"] == "Vacuna" ? "selected" : "" ?>>Vacuna</option>
          <option value="Virus" <?= isset($_SESSION["datos"]["type"]) && $_SESSION["datos"]["type"] == "Virus" ? "selected" : "" ?>>Virus</option>
        </select>
        <?= isset($errores["type"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "type") . '</div>' : ""; ?>
      </div>

      <div class="form-group">
        <label for="level">Nivel </label>
        <select id="level" name="level" class="form-select" aria-label="Selecciona el nivel del digimon">
          <option value="1" <?= isset($_SESSION["datos"]["level"]) && $_SESSION["datos"]["level"] == "1" ? "selected" : "selected" ?>>1. Bebe</option>
          <option value="2" <?= isset($_SESSION["datos"]["level"]) && $_SESSION["datos"]["level"] == "2" ? "selected" : "" ?>>
            2. Infantil</option>
          <option value="3" <?= isset($_SESSION["datos"]["level"]) && $_SESSION["datos"]["level"] == "3" ? "selected" : "" ?>>
            3. Adulto</option>
          <option value="4" <?= isset($_SESSION["datos"]["level"]) && $_SESSION["datos"]["level"] == "4" ? "selected" : "" ?>>
            4. Perfecto</option>
        </select>
        <?= isset($errores["level"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "level") . '</div>' : ""; ?>
      </div>

      <div class="form-group">
        <label for="next_evolution_id">Siguiente evolución</label>
        <select disabled id="next_evolution_id" name="next_evolution_id" class="form-select"aria-label="Selecciona su siguiente evolución">
          <option value="">Ninguna</option>
        </select>
        <?= isset($errores["next_evolution_id"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "next_evolution_id") . '</div>' : ""; ?>
      </div>

      <div class="form-group">
        <label for="image">Imagen Principal</label>
        <input type="file" required class="form-control" id="image" name="image" accept="image/png, image/jpeg, image/jpg, image/gif"
          value="<?= $_SESSION["datos"]["image"]["tmp_name"] ?? "" ?>">
        <?= isset($errores["image"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "image") . '</div>' : ""; ?>
      </div>

      <div class="form-group">
        <label for="imageVictory">Imagen Victoria</label>
        <input type="file" required class="form-control" id="imageVictory" name="imageVictory" accept="image/png, image/jpeg, image/jpg, image/gif"
          value="<?= $_SESSION["datos"]["imageVictory"]["tmp_name"] ?? "" ?>">
        <?= isset($errores["imageVictory"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "imageVictory") . '</div>' : ""; ?>
      </div>

      <div class="form-group">
        <label for="imageDefeat">Imagen Derrota</label>
        <input type="file" required class="form-control" id="imageDefeat" name="imageDefeat" accept="image/png, image/jpeg, image/jpg, image/gif"
          value="<?= $_SESSION["datos"]["imageDefeat"]["tmp_name"] ?? "" ?>">
        <?= isset($errores["imageDefeat"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "imageDefeat") . '</div>' : ""; ?>
      </div>

      <button type="submit" class="btn btn-primary">Guardar</button>
      <a class="btn btn-danger" href="index.php">Cancelar</a>
    </form>

    <?php
    //Una vez mostrados los errores, los eliminamos
    unset($_SESSION["datos"]);
    unset($_SESSION["errores"]);
    ?>
  </div>
</main>