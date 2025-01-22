<?php
require_once "controllers/projectsController.php";
require_once "controllers/tasksController.php";
if (!isset($_REQUEST['id'])) {
    header("location:index.php");
    exit();
    // si no ponemos exit despues de header redirecciona al finalizar la pagina 
    // ejecutando el código que viene a continuación, aunque no llegues a verlo
    // No poner exit puede provocar acciones no esperadas dificiles de depurar
}
$clase = "";
$visibilidad = "hidden";
$mensaje = "";
if (isset ($_REQUEST["evento"]) && $_REQUEST["evento"]=="borrar"){
    $visibilidad="visible";
    $clase="alert alert-success";  
    //Mejorar y poner el nombre/cliente
    $mensaje="La tarea con id: {$_REQUEST['idtask']} ha sido borrada correctamente";
    if (isset($_REQUEST["error"])){
      $clase="alert alert-danger ";
      $mensaje="ERROR!!! No se ha podido borrar la tarea con id: {$_REQUEST['idtask']}";
      $visibilidad="visible";
    }
} elseif (isset ($_REQUEST["evento"]) && $_REQUEST["evento"]=="crear") {
    $visibilidad="visible";
    $clase="alert alert-success";  
    //Mejorar y poner el nombre/cliente
    $mensaje="La tarea con id: {$_REQUEST['idtask']} ha sido creada correctamente";
    if (isset($_REQUEST["error"])){
      $clase="alert alert-danger ";
      $mensaje="ERROR!!! No se ha podido crear la tarea con id: {$_REQUEST['idtask']}";
      $visibilidad="visible";
    }
}
$id = $_REQUEST['id'];
$controlador = new ProjectsController();
$project = $controlador->ver($id);
$controladorT = new TasksController();
$tasks = $controladorT->ver($id);
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Ver Proyecto</h1> <?= ($project->user_id == $_SESSION["usuario"]->id) ? "<a class='btn btn-success' href='index.php?tabla=project&accion=editar&id={$id}'><i class='fas fa-pencil-alt'></i> Editar Proyecto</a>" : ""; ?>
    </div>
    <div id="contenido">
        <div class="<?= $clase ?>" <?= $visibilidad ?> role="alert">
            <?= $mensaje ?>
        </div>
        <h5 class="card-title">ID: <?= $project->id ?> NOMBRE: <?= $project->name ?> </h5>
        <p>
            <b>Descripción:</b> <?= $project->description ?> <br>
            <b>Fecha Límite:</b> <?= date('d-m-Y', strtotime($project->deadline)) ?><br>
            <b>Estado:</b><?= $project->status ?><br>
            <b>Responsable Proyecto:</b><?= " {$project->usuario_user} - {$project->name_user}" ?><br>
            <b>Cliente:</b><?= "{$project->idFiscal_client} - {$project->company_name_client} <b>Persona Contacto:</b>{$project->contact_name_client}"  ?><br>
        </p>
        <p>TAREAS DEL PROYECTO</p></BR>
        <?= ($project->user_id == $_SESSION["usuario"]->id) ? "<a class='btn btn-success' href='index.php?tabla=task&accion=crear&id={$id}'><i class='fas fa-pencil-alt'></i> Añadir Tarea</a>" : ""; ?>

            
            <?php
            if ($tasks == null) :
                echo "No hay Datos a Mostrar";
            else : ?>
                <table class="table table-light table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tarea</th>
                            <th>Desc</th>
                            <th>Fecha Límite</th>
                            <th>Estado</th>
                            <th>Encargado</th>
                            <th>Proyecto Asociado</th>
                            <th>Líder</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $hayTareas = false;
                            foreach ($tasks as $task) :
                            // Compruebo si el usuario que accede tiene tareas asignadas O si es el lider del proyecto
                            if ($task->idUsuarioEncargado == $_SESSION["usuario"]->id || $project->user_id == $_SESSION["usuario"]->id) {
                                $hayTareas = true;
                                ?>
                                <tr>
                                    <td><?=$task->idTarea?></td>
                                    <td><?=$task->name?></td>
                                    <td><?=$task->description?></td>
                                    <td><?=$task->deadline?></td>
                                    <td><?=$task->task_status?></td>
                                    <td><?=$task->idUsuarioEncargado?></td>
                                    <td><?=$task->idProyecto?></td>
                                    <td><?=$task->usuario?></td>
                                    <td colspan="2">
                                        <?= ($project->user_id == $_SESSION["usuario"]->id) ? "<a class='btn btn-danger' href='index.php?tabla=task&accion=borrar&id={$task->idTarea}&idProject={$project->id}'><i class='fas fa-trash'></i> Borrar</a>" : ""; ?>
                                        <?= ($project->user_id == $_SESSION["usuario"]->id) ? "<a class='btn btn-success' href='index.php?tabla=task&accion=editar&id={$task->idTarea}'><i class='fas fa-pencil-alt'></i> Modificar</a>" : ""; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        endforeach;
                        if (!$hayTareas) {
                            echo "<tr><td colspan='10'>No hay Tareas por el momento.</td></tr>";
                        }
                        ?>
                    </tbody>
            </table>
            <?php
            endif;
        ?>
        <!-- <table border="1">
            <tr>
                <td>Tarea 1</td>
                <td> Descripcion Tarea 1 </td>
                <td> Boton Borrar</td>
                <td> Boton Modificar</td>
            </tr>
            <tr>
                <td>Tarea 2</td>
                <td> Descripcion Tarea 1 </td>
                <td> Boton Borrar</td>
                <td> Boton Modificar</td>
            </tr>
        </table> -->
        
    </div>
    <div>
        <center><a href="index.php?accion=buscar&tabla=project&evento=todos" class="btn btn-info" name="Todos" role="button">Volver</a></center>
    </div>