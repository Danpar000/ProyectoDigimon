<?php
require_once "models/taskModel.php";
require_once "assets/php/funciones.php";


class TasksController
{
    private $model;

    public function __construct()
    {
        $this->model = new TaskModel();
    }
 
    public function verId(int $id): ?stdClass {
        return $this->model->read($id);
    }

    // Mira todas las coincidencias por id
    public function ver(int $id): ?array
    {
        return $this->model->readAllPerId($id);
    }

    public function listar(bool $comprobarSiEsBorrable=false)
    {
        $tasks= $this->model->readAll();
        return $tasks;
    }

    public function buscar(string $campo = "id", string $metodo = "contiene", string $texto = "", bool  $comprobarSiEsBorrable = false): array
    {
        $tasks = $this->model->search($campo, $metodo, $texto);
        return $tasks;
    }


    public function crear(array $arrayTask): void
    {
        $error = false;
        $errores = [];

        //vaciamos los posibles errores
        $_SESSION["errores"] = [];
        $_SESSION["datos"] = [];

        // ERRORES DE TIPO

        //campos NO VACIOS
        $arrayNoNulos = ["name", "task_status", "user_id", "client_id"];
        $nulos = HayNulos($arrayNoNulos, $arrayTask);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} es nulo";
            }
        }

        //CAMPOS UNICOS NINGUNO

        $id = null;
        if (!$error) $id = $this->model->insert($arrayTask);
            if ($id == null) {
                $_SESSION["errores"] = $errores;
                $_SESSION["datos"] = $arrayTask;
                header("location:index.php?accion=crear&tabla=task&error=true&id={$arrayTask["project_id"]}");
                exit();
            } else {
                unset($_SESSION["errores"]);
                unset($_SESSION["datos"]);
                header("location:index.php?accion=ver&tabla=project&id={$arrayTask["project_id"]}");
                exit();
            }
        }

        public function editar(int $id, array $arrayTask): void
        {
        $error = false;
        $errores = [];

        //vaciamos los posibles errores
        $_SESSION["errores"] = [];
        $_SESSION["datos"] = [];

        // ERRORES DE TIPO

        //campos NO VACIOS
        $arrayNoNulos = ["name", "task_status", "user_id", "client_id"];
        $nulos = HayNulos($arrayNoNulos, $arrayTask);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} es nulo";
            }
        }

        //CAMPOS UNICOS NINGUNO

        $editado = false;
        if (!$error) $editado = $this->model->edit($id, $arrayTask);
            if ($editado == null) {
                $_SESSION["errores"] = $errores;
                $_SESSION["datos"] = $arrayTask;
                header("location:index.php?accion=editar&tabla=task&evento=modificar&error=true&id={$arrayTask["id"]}");
                exit();
            } else {
                unset($_SESSION["errores"]);
                unset($_SESSION["datos"]);
                header("location:index.php?accion=ver&tabla=project&id={$arrayTask["project_id"]}");
                exit();
            }
        }

        public function editarStatus(int $id, array $arrayTask): void
        {
        $error = false;
        $errores = [];

        //vaciamos los posibles errores
        $_SESSION["errores"] = [];
        $_SESSION["datos"] = [];

        // ERRORES DE TIPO

        //campos NO VACIOS
        $arrayNoNulos = ["id", "task_status", "idEncargado"];
        $nulos = HayNulos($arrayNoNulos, $arrayTask);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} es nulo";
            }
        }

        //CAMPOS UNICOS NINGUNO

        $editado = false;
        if (!$error) $editado = $this->model->editStatus($id, $arrayTask);
            if ($editado == null) {
                $_SESSION["errores"] = $errores;
                $_SESSION["datos"] = $arrayTask;
                header("location:index.php?accion=editar&tabla=task&evento=modificar&error=true&id={$arrayTask["id"]}&idEncargado={$arrayTask["idEncargado"]}");
                exit();
            } else {
                unset($_SESSION["errores"]);
                unset($_SESSION["datos"]);
                header("location:index.php?accion=buscar&tabla=task&evento=todos");
                exit();
            }
        }

    public function borrar(int $idProject, int $idTarea): void
    {
    $borrado = $this->model->delete($idTarea);
    $redireccion = "location:index.php?tabla=project&evento=borrar&accion=ver&id={$idProject}&idtask={$idTarea}";
    if ($borrado == false) $redireccion .=  "&error=true";
    header($redireccion);
    exit();
    }

    
}
