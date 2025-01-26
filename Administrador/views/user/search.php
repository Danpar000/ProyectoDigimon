<?php
require_once "controllers/usersController.php";

$mensaje = "";
$clase = "alert alert-success";
$visibilidad = "hidden";
$mostrarDatos = false;
$controlador = new usersController();
$info = "";
if (!isset($_REQUEST['campo'], $_REQUEST['tipo'])) {
    $campo = 'username';
    $tipo = 'startswith';
} else {
    $campo = $_REQUEST['campo'];
    $tipo = $_REQUEST['tipo'];
}

if (isset($_REQUEST["evento"])) {
    $mostrarDatos = true;
    switch ($_REQUEST["evento"]) {
        case "todos":
            $users = $controlador->listar();
            // $users = $controlador->buscar(comprobarSiEsBorrable: true);
            $mostrarDatos = true;
            break;
        case "filtrar":
            $campo=($_REQUEST["campo"])??"username";
            $metodo=($_REQUEST["tipo"])??"contiene";
            $texto=($_REQUEST["busqueda"])??"";
            //es borrable Parametro con nombre
            $users = $controlador->buscar($campo, $metodo, $texto,comprobarSiEsBorrable: true);//solo añadimos esto
            break;
        case "borrar":
            $visibilidad = "visibility";
            $mostrarDatos = true;
            $clase = "alert alert-success";
            //Mejorar y poner el nombre/usuario
            $mensaje = "El usuario con id: {$_REQUEST['id']} Borrado correctamente";
            if (isset($_REQUEST["error"])) {
                $clase = "alert alert-danger ";
                $mensaje = "ERROR!!! No se ha podido borrar el usuario con id: {$_REQUEST['id']}";
            }
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
        <h1 class="h3">Buscar Usuario</h1>
    </div>
    <div id="contenido">
        <div class="<?= $clase ?>" <?= $visibilidad ?> role="alert">
            <?= $mensaje ?>
        </div>
        <div>
        <form action="index.php?tabla=user&accion=buscar&evento=filtrar" method="POST">
            <div class="form-group">
                <label for="campo">Buscar por</label>
                <select class="form-select" name="campo" id="campo">
                    <option value="username" <?= $campo == "username" ? "selected" : ""?>>Nombre de usuario</option>
                </select>
                <label for="campo">Tipo de búsqueda</label>
                <select class="form-select" name="tipo" id="tipo">
                    <option value="startswith" <?= $tipo == "startswith" ? "selected" : ""?> >Empieza por</option>
                    <option value="endswith" <?= $tipo == "endswith" ? "selected" : ""?>>Acaba en</option>
                    <option value="contains" <?= $tipo == "contains" ? "selected" : ""?>>Contiene</option>
                    <option value="equals" <?= $tipo == "equals" ? "selected" : ""?>>Igual a</option>
                </select>

                <label for="busqueda">Buscar</label>
                <input type="text" required class="form-control" id="busqueda" name="busqueda" value="<?= $info ?>" placeholder="...">
            </div>
            <button type="submit" class="btn btn-success" name="Filtrar"><i class="fas fa-search"></i> Buscar</button>
        </form>
        <!-- Este formulario es para ver todos los datos    -->
        <form action="index.php?tabla=user&accion=buscar&evento=todos" method="POST">
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
                        <th scope="col">Icono</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Digievoluciones</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) :
                        $id = $user->id;
                    ?>
                        <tr>
                            <th scope="row"><?=$user->id?></th>
                            <td class="image"><img src="assets/img/users/<?= $user->username?>/profile.png" width="75"></td>
                            <td><?=$user->username?></td>
                            <td><?=$user->digievolutions?></td>
                            
                            <td><a class="btn btn-success" href="index.php?tabla=user&accion=editar&id=<?=$id?>"><i class="fas fa-pencil-alt"></i> Editar</a></td>
                            <td><a class="btn btn-warning" href="index.php?tabla=user&accion=ver&id=<?= $id ?>"><i class="fas fa-eye"> Ver</i></td>
                            <td>
                                <?php
                                $disable="";$ruta="index.php?tabla=user&accion=borrar&id={$id}&usuario=<?= $user->username?>";
                                if (isset($user->esBorrable) && $user->esBorrable==false){
                                $disable="disabled"; $ruta="#";
                                }
                                ?>
                                <a class="btn btn-danger <?= $disable?>" href="<?=$ruta?>"><i class="fa fa-trash"></i> Borrar</a>
                            </td>
                                
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