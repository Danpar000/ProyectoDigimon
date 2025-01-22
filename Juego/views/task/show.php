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
$id = $_REQUEST['id'];
$controlador = new ProjectsController();
$project = $controlador->ver($id);
$controladorT = new TasksController();
$taskProject = $controladorT->ver($id);
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Ver Proyecto</h1> <?= ($project->user_id == $_SESSION["usuario"]->id) ? "<a class='btn btn-success' href='index.php?tabla=project&accion=editar&id={$id}'><i class='fas fa-pencil-alt'></i> Editar Proyecto</a>" : ""; ?>
    </div>
    <div id="contenido">
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
            if (count($taskProject) <= 0) :
                echo "No hay Datos a Mostrar";
            else : ?>
                <table class="table table-light table-hover">
                        <?php foreach ($tasks as $task) :
                            $idTask = $task->id;
                        ?>
                            <td>
                            Tarea:
                            <!-- Tarea <?=$task->id?> -->
                            </td>
                            <td>
                            Desc:
                            <!-- Descripcion: <?=$task->description?> -->
                            </td>
                            <td>
                            <?= ($project->user_id == $_SESSION["usuario"]->id) ? "<a class='btn btn-success' href='index.php?tabla=task&accion=borrar&id={$idTask}'><i class='fas fa-pencil-alt'></i> Borrar Tarea</a>" : ""; ?>
                            </td>
                            <td>
                            <?= ($project->user_id == $_SESSION["usuario"]->id) ? "<a class='btn btn-success' href='index.php?tabla=task&accion=editar&id={$idTask}'><i class='fas fa-pencil-alt'></i> Modificar Tarea</a>" : ""; ?>
                            </td>
                        <?php
                        endforeach;
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