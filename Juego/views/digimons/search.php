<?php
require_once "controllers/digimonsController.php";

$mensaje = "";
$clase = "alert alert-success";
$visibilidad = "hidden";
$mostrarDatos = false;
$controlador = new DigimonsController();
$texto = "";
if (!isset($_REQUEST['campo'], $_REQUEST['tipo'])) {
    $campo = 'name';
    $tipo = 'startswith';
} else {
    $campo = $_REQUEST['campo'];
    $tipo = $_REQUEST['tipo'];
}

if (isset($_REQUEST["evento"])) {
    $mostrarDatos = true;
    switch ($_REQUEST["evento"]) {
        case "todos":
            $digimons = $controlador->listar();
            $digimons = $controlador->buscar(comprobarSiEsBorrable: true);
            $mostrarDatos = true;
            break;
        case "filtrar":
            $campo=($_REQUEST["campo"])??"name";
            $metodo=($_REQUEST["tipo"])??"contains";
            $texto=($_REQUEST["busqueda"])??"";
            $digimons = $controlador->buscar($campo, $metodo, $texto,comprobarSiEsBorrable: true);
            break;
    }
} ?>
<style>
    tr td, th{
        text-align: center;
        align-content: center;
    }
</style>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Buscar digimon</h1>
    </div>
    <div id="contenido">
        <div class="<?= $clase ?>" <?= $visibilidad ?> role="alert"><?= $mensaje ?></div>
        <div>
        <form action="index.php?tabla=digimons&accion=buscar&evento=filtrar" method="POST">
            <div class="form-group">
                <label for="campo">Buscar por</label>
                <select class="form-select" name="campo" id="campo">
                    <option value="name" <?= $campo == "name" ? "selected" : ""?>>Nombre</option>
                    <option value="id" <?= $campo == "id" ? "selected" : ""?>>ID</option>
                    <option value="type" <?= $campo == "type" ? "selected" : ""?>>Tipo</option>
                    <option value="level" <?= $campo == "level" ? "selected" : ""?>>Nivel</option>                    
                </select>
                <label for="campo">Tipo de búsqueda</label>
                <select class="form-select" name="tipo" id="tipo">
                    <option value="startswith" <?= $tipo == "startswith" ? "selected" : ""?> >Empieza por</option>
                    <option value="endswith" <?= $tipo == "endswith" ? "selected" : ""?>>Acaba en</option>
                    <option value="contains" <?= $tipo == "contains" ? "selected" : ""?>>Contiene</option>
                    <option value="equals" <?= $tipo == "equals" ? "selected" : ""?>>Igual a</option>
                </select>

                <label for="busqueda">Buscar</label>
                <input type="text" required class="form-control" id="busqueda" name="busqueda" value="<?= $texto ?>" placeholder="...">
            </div>
            <button type="submit" class="btn btn-success" name="Filtrar"><i class="fas fa-search"></i> Buscar</button>
        </form>
        <form action="index.php?tabla=digimons&accion=buscar&evento=todos" method="POST">
            <button type="submit" class="btn btn-info" name="Todos"><i class="fas fa-list"></i> Listar</button>
        </form>
        </div>
        <?php
        if ($mostrarDatos) {
        ?>
            <table class="table table-light table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col"></th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Vida</th>
                        <th scope="col">Ataque</th>
                        <th scope="col">Defensa</th>
                        <th scope="col">Velocidad</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Nivel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($digimons as $digimon) :
                        $id = $digimon->id;
                    ?>
                        <tr>
                            <th scope="row"><?= $digimon->id ?></th>
                            <td class="image"><img src="assets/img/digimons/<?= $digimon->name?>/base.png" width="75"></td>
                            <td><?= $digimon->name ?></td>
                            <td><?= $digimon->health ?></td>
                            <td><?= $digimon->attack ?></td>
                            <td><?= $digimon->defense?></td>
                            <td><?= $digimon->speed ?></td>
                            <td><?= $digimon->type?></td>
                            <td><?= $digimon->level?></td>
                            
                            
            
                            <!-- <td><a class="btn btn-warning" href="index.php?tabla=digimons&accion=ver&id=<?= $id ?>"><i class="fas fa-eye"></i> Ver</td> -->
                            <td>                                
                            </tr>
                    <?php
                    endforeach;

                    ?>
                </tbody>
            </table>
        <?php
        }
        ?>
    </div>
</main>