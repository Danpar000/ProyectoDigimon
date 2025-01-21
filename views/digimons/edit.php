<?php
require_once "controllers/digimonsController.php";
//recoger datos
if (!isset($_REQUEST["id"])) {
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
    $mensaje = "Digimon con id {$id}, ha cambiado su idFiscal a {$client->idFiscal} y el nombre a {$client->contact_name} con éxito";
    if (isset($_REQUEST["error"])) {
        $mensaje = "No se ha podido modificar el digimon con id {$id}";
        $clase = "alert alert-danger";
        $errores = ($_SESSION["errores"]) ?? [];
        $datos = ($_SESSION["datos"]) ?? [];
    }
}
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Editar Digimon con Id: <?= $id ?></h1>
    </div>
    <div id="contenido">
        <div id="msg" name="msg" class="<?= $clase ?>" <?= $visibilidad ?> > <?= $mensaje ?> </div>
        <?php
        if ($mostrarForm) {
        $errores=$_SESSION["errores"]??[];
        ?>
            <form action="index.php?tabla=digimons&accion=guardar&evento=modificar" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="id" name="id" value="<?= $client->id ?>">
                <!-- Por si lo cambiamos -->
                <!-- <div class="form-group">
                    <label for="idFiscal">ID Fiscal</label>
                    <input type="text" required class="form-control" id="idFiscal" name="idFiscal" value="<?= $_SESSION["datos"]["idFiscal"] ?? $client->idFiscal ?>" placeholder="ID Fiscal">
                    <input type="hidden" id="idFiscalOriginal" name="idFiscalOriginal" value="<?= $client->idFiscal ?>">
                    <?= isset($errores["idFiscal"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "idFiscal") . '</div>' : ""; ?>
                </div> -->
        

                <div class="form-group">
                    <label for="image">Imagen</label>
                    <input type="file" required class="form-control" id="image" name="image" accept="image/png, image/jpeg, image/jpg, image/gif"
                    value="<?= $_SESSION["datos"]["image"]["tmp_name"] ?? "" ?>">
                    <?= isset($errores["image"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "image") . '</div>' : ""; ?>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
                <a class="btn btn-danger" href="index.php?tabla=digimons&accion=listar">Cancelar</a>
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