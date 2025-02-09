<?php
require_once "controllers/digimonsController.php";
//recoger datos
if (!isset($_REQUEST["id"]) || is_nan($_REQUEST["id"])) {
    header('location:index.php?accion=listar');
    unset($_SESSION["datos"]);
    unset($_SESSION["errores"]);
    exit();
}
$id = $_REQUEST["id"];
$controlador = new DigimonsController();
if ($id != null) {
    $digi = $controlador->ver($id);
}

$datos = [];
$visibilidad = "hidden";
$mensaje = "";
$clase = "alert alert-success";
$mostrarForm = true;
if ($digi == null) {
    $visibilidad = "visible";
    $mensaje = "El digimon con id: {$id} no existe. Por favor vuelva a la pagina anterior";
    $clase = "alert alert-danger";
    $mostrarForm = false;
    unset($_SESSION["datos"]);
    unset($_SESSION["errores"]);
} else if (isset($_REQUEST["evento"]) && $_REQUEST["evento"] == "modificar") {
    $visibilidad = "visible";
    $mensaje = "Has cambiado a {$digi->name} con éxito";
    if (isset($_REQUEST["error"])) {
        $mensaje = "No se ha podido modificar a {$digi->name}";
        $clase = "alert alert-danger";
        $errores = ($_SESSION["errores"]) ?? [];
        $datos = ($_SESSION["datos"]) ?? [];
    }
}
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Estás editando a <?= $digi->name ?></h1>
    </div>
    <div id="contenido">
        <div id="msg" name="msg" class="<?= $clase ?>" <?= $visibilidad ?> > <?= $mensaje ?> </div>
        <?php
        if ($mostrarForm) {
        $errores=$_SESSION["errores"]??[];
        ?>
            <form action="index.php?tabla=digimons&accion=guardar&evento=modificar" method="POST" enctype="multipart/form-data" id="miform">
                <input type="hidden" id="id" name="id" value="<?= $digi->id ?>">
                <input type="hidden" id="name" name="name" value="<?= $digi->name ?>">

                <div class="form-group">
                    <label for="health">Vida </label>
                    <input type="number" required class="form-control" id="health" name="health"
                    value="<?= $_SESSION["datos"]["health"] ?? $digi->health ?>" aria-describedby="health" placeholder="Puntos de vida del digimon">
                    <?= isset($errores["health"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "health") . '</div>' : ""; ?>
                </div>


                <div class="form-group">
                    <label for="attack">Ataque </label>
                    <input type="number" required class="form-control" id="attack" name="attack"
                    value="<?= $_SESSION["datos"]["attack"] ?? $digi->attack ?>" aria-describedby="attack" placeholder="Ataque del digimon">
                    <?= isset($errores["attack"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "attack") . '</div>' : ""; ?>
                </div>


                <div class="form-group">
                    <label for="defense">Defensa</label>
                    <input type="number" required class="form-control" id="defense" name="defense"
                    value="<?= $_SESSION["datos"]["defense"] ?? $digi->defense ?>" placeholder="Defensa del digimon">
                    <?= isset($errores["defense"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "defense") . '</div>' : ""; ?>
                </div>

                <div class="form-group">
                    <label for="speed">Velocidad </label>
                    <input type="number" required class="form-control" id="speed" name="speed"
                    value="<?= $_SESSION["datos"]["speed"] ?? $digi->speed ?>" aria-describedby="speed" placeholder="Velocidad del digimon">
                    <?= isset($errores["speed"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "speed") . '</div>' : ""; ?>
                </div>

                <div class="form-group">
                    <label for="next_evolution_id">Siguiente evolución</label>
                    <select disabled id="next_evolution_id" name="next_evolution_id" class="form-select"aria-label="Selecciona su siguiente evolución">
                    <option value="">Ninguna</option>
                    </select>
                    <?= isset($errores["next_evolution_id"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "next_evolution_id") . '</div>' : ""; ?>
                </div>
        

                <div class="form-group">
                    <label for="image">Imagen Principal (Opcional)</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg, image/jpg, image/gif"
                    value="<?= $_SESSION["datos"]["image"]["tmp_name"] ?? "" ?>">
                    <?= isset($errores["image"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "image") . '</div>' : ""; ?>
                </div>

                <div class="form-group">
                    <label for="imageVictory">Imagen Victoria (Opcional)</label>
                    <input type="file" class="form-control" id="imageVictory" name="imageVictory" accept="image/png, image/jpeg, image/jpg, image/gif"
                    value="<?= $_SESSION["datos"]["imageVictory"]["tmp_name"] ?? "" ?>">
                    <?= isset($errores["imageVictory"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "imageVictory") . '</div>' : ""; ?>
                </div>

                <div class="form-group">
                    <label for="imageDefeat">Imagen Derrota (Opcional)</label>
                    <input type="file" class="form-control" id="imageDefeat" name="imageDefeat" accept="image/png, image/jpeg, image/jpg, image/gif"
                    value="<?= $_SESSION["datos"]["imageDefeat"]["tmp_name"] ?? "" ?>">
                    <?= isset($errores["imageDefeat"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "imageDefeat") . '</div>' : ""; ?>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
                <a class="btn btn-danger" href="index.php?tabla=digimons&accion=buscar&evento=todos">Cancelar</a>
            </form>
        <?php
        } else {
        ?>
            <a href="index.php" class="btn btn-primary">Volver a Inicio</a>
        <?php
        }
        //Una vez mostrados los errores, los eliminamos
        unset($_SESSION["datos"]);
        unset($_SESSION["errores"]);
        ?>
    </div>
</main>