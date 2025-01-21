<?php
// require_once "controllers/tasksController.php";
require_once "controllers/tasksController.php";

$mensaje = "";
$clase = "alert alert-success";
$visibilidad = "hidden";
$mostrarDatos = false;
// $controlador = new tasksController();
$controlador = new TasksController();
$campo = "tasks.name";
$metodo = "contiene";
$texto = "";

if (isset($_REQUEST["evento"])) {
    $mostrarDatos = true;
    switch ($_REQUEST["evento"]) {
        case "todos":
            $tasks = $controlador->listar();
            $mostrarDatos = true;
            break;
        //Modificamos el filtrar    
        case "filtrar":
            $campo = ($_REQUEST["campo"]) ?? "task.name";
            $metodo = ($_REQUEST["metodoBusqueda"]) ?? "contiene";
            $texto = ($_REQUEST["busqueda"]) ?? "";
            $tasks = $controlador->buscar($campo, $metodo, $texto);
        // case "borrar":
        //     $visibilidad = "visibility";
        //     $mostrarDatos = true;
        //     $clase = "alert alert-success";
        //     //Mejorar y poner el nombre/usuario
        //     $mensaje = "El proyecto o {$_REQUEST['id']} -  {$_REQUEST['name']} Borrado correctamente";
        //     if (isset($_REQUEST["error"])) {
        //         $clase = "alert alert-danger ";
        //         $mensaje = "ERROR!!! No se ha podido borrar el proyecto {$_REQUEST['id']} -  {$_REQUEST['name']}";
        //     }
        //     $tasks = $controlador->listar(comprobarSiEsBorrable: true);
        //     break;
    }
}
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Buscar Tarea</h1>
    </div>
    <div id="contenido">
        <div class="<?= $clase ?>" <?= $visibilidad ?> role="alert"> <?= $mensaje ?>
        </div>
        <div>
            <form action="index.php?accion=buscar&tabla=task&evento=filtrar" method="POST">
                <div class="form-group">
                    <select class="form-select" name="campo" id="campo">
                        <option value="tasks.id" <?= $campo == "tasks.id" ? "selected" : "" ?>>ID</option>
                        <option value="tasks.name" <?= $campo == "tasks.name" ? "selected" : "" ?>>Nombre Tarea
                        </option>
                        <option value="tasks.description" <?= $campo == "tasks.description" ? "selected" : "" ?>>Descripcion </option>
                        <option value="tasks.task_status" <?= $campo == "tasks.task_status" ? "selected" : "" ?>>Estado</option>
                        <option value="projects.name" <?= $campo == "projects.name" ? "selected" : "" ?>>Nombre Proyecto Asociado
                        <option value="clients.contact_name" <?= $campo == "clients.contact_name" ? "selected" : "" ?>>
                            Nombre Contacto Cliente</option>
                        <option value="clients.idFiscal" <?= $campo == "clients.idFiscal" ? "selected" : "" ?>> Id Fiscal
                            de Cliente de Usuario </option>
                        <option value="clients.company_name" <?= $campo == "clients.company_name" ? "selected" : "" ?>>
                            Nombre Empresa Cliente </option>
                    </select>
                    <select class="form-select" name="metodoBusqueda" id="metodoBusqueda">
                        <option value="empieza" <?= $metodo == "empieza" ? "selected" : "" ?>>Empieza Por</option>
                        <option value="acaba" <?= $metodo == "acaba" ? "selected" : "" ?>>Acaba En </option>
                        <option value="contiene" <?= $metodo == "contiene" ? "selected" : "" ?>>Contiene </option>
                        <option value="igual" <?= $metodo == "igual" ? "selected" : "" ?>>Es Igual A</option>

                    </select>
                    <input type="text" class="form-control" id="busqueda" name="busqueda" value="<?= $texto ?>"
                        placeholder="texto a Buscar">
                    <DIV>
                        <button type="submit" class="btn btn-success" name="Filtrar">Buscar</button>
                        <a href="index.php?accion=buscar&tabla=task&evento=todos" class="btn btn-info" name="Todos"
                            role="button">Ver todos</a>
            </form>

        </div>
        <?php
        if ($mostrarDatos) {
            if (count($tasks) <= 0):
                echo "No hay Datos a Mostrar";
            else:
                ?>
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
                            <th>Cliente</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $hayTareas = false;
                            foreach ($tasks as $task) :
                            // Compruebo si el usuario que accede tiene tareas asignadas O si es el lider del proyecto
                            if ($task->user_id == $_SESSION["usuario"]->id) {
                                $hayTareas = true;
                                ?>
                                <tr>
                                    <td><?=$task->id?></td>
                                    <td><?=$task->name?></td>
                                    <td><?=$task->description?></td>
                                    <td><?=$task->deadline?></td>
                                    <td><?=$task->task_status?></td>
                                    <td><?="{$task->user_id} - {$task->name_user} - {$task->usuario_user}"?></td>
                                    <td><?="{$task->project_id} - {$task->name_project}"?></td>
                                    <td><?=$task->leader_project?></td>
                                    <td><?= "$task->client_id - {$task->contact_name_client} {$task->company_name_client} " ?></td>
                                    <td colspan="2">
                                        <a class='btn btn-success' href='index.php?tabla=task&accion=editar&id=<?=$task->id?>&idEncargado=<?=$task->user_id?>'><i class='fas fa-pencil-alt'></i> Modificar</a>
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
        }
        ?>
    </div>
</main>