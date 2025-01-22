<?php
require_once "controllers/tasksController.php";
require_once "controllers/clientsController.php";
require_once "controllers/usersController.php";
require_once "controllers/projectsController.php";


$errores = [];
$datos = [];
const STATUS = ['Abierto', 'En Progreso', 'Cancelado', 'Completado'];
if (!isset($_REQUEST["id"])) {
    header('location:index.php?accion=buscar&tabla=project&evento=todos');
    unset($_SESSION["datos"]);
    unset($_SESSION["errores"]);
    exit();
}

$id = $_REQUEST["id"];

$contlUsers = new UsersController();
$users = $contlUsers->listar();
$contlClientes= new ClientsController();
$clients = $contlClientes->listar();
$contlProyectos = new ProjectsController();
$proyectos = $contlProyectos->listar();
$contlTareas = new TasksController();
$tareas = $contlTareas->verId((int)$id);
$projectClient=$contlProyectos->ver($tareas->idProyecto);


$visibilidad = "hidden";
$mensaje = "";
$clase = "alert alert-success";
$mostrarForm = true;

$esJefe = false;
$esEncargado = false;
// Compruebo si es el encargado
if(isset($_REQUEST["idEncargado"]) && $_SESSION["usuario"]->id == $_REQUEST["idEncargado"] || $tareas->idUsuarioEncargado == $_SESSION["usuario"]->id) {
    $esEncargado = true;
}

// Compruebo si es el dueño
foreach ($proyectos as $proyecto):
    if ($proyecto->id == $tareas->idProyecto && $proyecto->user_id == $_SESSION["usuario"]->id):
        $esEncargado = false;
        $esJefe = true;
    endif;
endforeach;


if ($tareas == null || $esEncargado == false && $esJefe == false) {
    $visibilidad = "visible";
    $mensaje = "La tarea con id: {$id} no la tienes asignada. Por favor vuelva a la pagina anterior";
    $clase = "alert alert-danger";
    $mostrarForm = false;
    unset($_SESSION["datos"]);
    unset($_SESSION["errores"]);
} else if (isset($_REQUEST["evento"]) && $_REQUEST["evento"] == "modificar") {
    $visibilidad = "visible";
    if (isset($_REQUEST["error"])) {
        $mensaje = "No se ha podido modificar la tarea con id {$id}";
        $clase = "alert alert-danger";
    } else {
        $mensaje = "El proyecto {$tareas->name} con id {$id} - Modificado con éxito";
        $errores = ($_SESSION["errores"]) ?? [];
        $datos = ($_SESSION["datos"]) ?? [];
    }
}

$nametareatext = "";
$idtext = "";
// Cambiar valores texto
isset($tareas->idTarea)? $idtext = $tareas->idTarea:"";
isset($tareas->name)? $nametareatext = $tareas->name: "";

?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3"><?=$esEncargado?"Editar estado de la tarea {$nametareatext}":"Modificar tarea {$nametareatext} con ID {$idtext}"?></h1>
    </div>
    <div id="contenido">
        <div id="msg" name="msg" class="<?= $clase ?>" <?= $visibilidad ?>> <?= $mensaje ?> </div>
        <?php
        if ($mostrarForm) {
		    $errores=$_SESSION["errores"]??[];
            if ($esEncargado == true) { // Encargado de la tarea, solo cambia estado
                ?>
                <form action="index.php?tabla=task&accion=guardar&evento=editar" method="POST">
                <input type="hidden" id="id" name="id" value="<?= isset($tareas->idTarea) ? $tareas->idTarea : $_SESSION["datos"]["idTarea"] ?>">
                <input type="hidden" name="project_id" id="project_id" value="<?= isset($tareas->idProyecto) ? $tareas->idProyecto : $_SESSION["datos"]["idProyecto"] ?>">
                <input type="hidden" name="idEncargado" id="idEncargado" value="<?= isset($tareas->idUsuarioEncargado) ? $tareas->idUsuarioEncargado : $_SESSION["datos"]["idUsuarioEncargado"] ?>">

                <!-- ESTADO -->
                <div class="form-group">
                    <label for="task_status">Estado </label>
                    <select id="task_status" name="task_status" class="form-select" aria-label="Default select example">
                    <?php
                    foreach (STATUS as $estado) :
                        $selected = "";
                        if (isset($_SESSION["datos"]["task_status"])) {
                            $selected = $_SESSION["datos"]["task_status"]==$estado ? "selected" : "";
                        } else {
                            $selected = $tareas->task_status==$estado ? "selected" : "";
                        }
                        echo "<option {$selected}>{$estado}</option>";
                    endforeach;
                    ?>
                    </select>
                    <?= isset($errores["status"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "status") . '</div>' : ""; ?>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
                <a class="btn btn-danger" href="index.php?tabla=project&accion=buscar&tabla=project&evento=todos">Cancelar</a>
            </form>
            <?php
            }
            else {
            ?>
            <form action="index.php?tabla=task&accion=guardar&evento=modificar" method="POST">
                <div class="form-group">
                    <label for="name">Nombre Tarea </label>
                    <input type="hidden" id="id" name="id" value="<?= isset($tareas->idTarea) ? $tareas->idTarea : $_SESSION["datos"]["idTarea"] ?>">
                    <input type="hidden" name="project_id" id="project_id" value="<?= isset($tareas->idProyecto) ? $tareas->idProyecto : $_SESSION["datos"]["project_id"] ?>">
                    <input type="text" required class="form-control" id="name" name="name" aria-describedby="nombre" value="<?= $_SESSION["datos"]["name"] ?? $tareas->name ?>">
                    <?= isset($errores["name"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "name") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea class="form-control" id="description" name="description" value="<?= $_SESSION["datos"]["description"] ?? $tareas->description ?>"></textarea>
                    <?= isset($errores["description"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "description") . '</div>' : ""; ?>
                </div>
                <div class="form-group">
                    <label for="deadline">Fecha Finalización </label>
                    <input type="date" class="form-control" id="deadline" name="deadline" value="<?= $_SESSION["datos"]["deadline"] ?? $tareas->deadline ?>">
                    <?= isset($errores["deadline"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "deadline") . '</div>' : ""; ?>
                </div>

                <!-- ESTADO -->
                <div class="form-group">
                    <label for="task_status">Estado </label>
                    <select id="task_status" name="task_status" class="form-select" aria-label="Default select example">
                    <?php
                    foreach (STATUS as $estado) :
                        $selected = "";
                        if (isset($_SESSION["datos"]["task_status"])) {
                            $selected = $_SESSION["datos"]["task_status"]==$estado ? "selected" : "";
                        } else {
                            $selected = $tareas->task_status==$estado ? "selected" : "";
                        }
                        echo "<option {$selected}>{$estado}</option>";
                    endforeach;
                    ?>
                    </select>
                    <?= isset($errores["status"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "status") . '</div>' : ""; ?>
                </div>

                <!-- JEFE PROYECTO -->
                <div class="form-group">
                    <label for="user_id">Asignar tarea a</label>
                    <select id="user_id" name="user_id" class="form-select" aria-label="Selecciona Jefe Proyecto">
                    <?php
                    foreach ($users as $user) :
                        $selected = "";
                        if (isset($_SESSION["datos"]["user_id"])) {
                            $selected = ($_SESSION["datos"]["user_id"] == $user->id) ? "selected" : "";
                        } elseif ($tareas->user_id == $user->id) {
                            $selected = "selected";
                        }
                        echo "<option value='{$user->id}' {$selected}>{$user->id} | {$user->usuario} | {$user->name}</option>";
                    endforeach;
                    ?>
                    </select>
                    <?= isset($errores["user_id"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "user_id") . '</div>' : ""; ?>
                </div>
                
                <!-- CLIENTE -->
                <div class="form-group">
                    <label for="client_id">Cliente </label>
                    <select id="client_id" name="client_id" class="form-select" aria-label="Selecciona Cliente Proyecto">

                    <?php
                    foreach ($clients as $client) :
                    
                        $selected= $projectClient->id_client==$client->id?"selected":"";
                        if ($projectClient->id_client==$client->id) {
                        echo "<option value='{$client->id}' {$selected}>{$client->id} - {$client->idFiscal} - {$client->company_name} - {$client->contact_name}</option>";
                        }
            
                    endforeach;
                    ?>
                    </select>
                    <?= isset($errores["client_id"]) ? '<div class="alert alert-danger" role="alert">' . DibujarErrores($errores, "client_id") . '</div>' : ""; ?>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a class="btn btn-danger" href="index.php?tabla=project&accion=buscar&tabla=project&evento=todos">Cancelar</a>
            </form>
        <?php
            }
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
