<?php
require_once "models/userModel.php";
require_once "controllers/projectsController.php";

class TeamUsersController { 
    private $model;

    public function __construct(){
        $this->model = new TeamUsersModel();
    }

    public function crear (array $arrayTeam):void {
        $error = false;
        $errores = [];
        if (isset($_SESSION["errores"])) {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
        }
    
        // ERRORES DE TIPO
    
        //campos NO VACIOS
        $arrayNoNulos = ["user_id", "digimon_id"];
        $nulos = HayNulos($arrayNoNulos, $arrayTeam);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} NO puede estar vacio";
            }
        }
        
        $id = null;
        if (!$error) $id = $this->model->insert ($arrayTeam);

        $redireccion="location:index.php?tabla=equipos&accion=crear";
        if ($id == null) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayTeam;
            $redireccion.="&error=true&id={$id}";
        } else {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            $redireccion = "location:index.php?tabla=equipos&accion=ver&id=".$id;
        }
        
        header ($redireccion);
        exit();
    }

    public function ver(int $id): ?stdClass {
        return $this->model->read($id);
    }

    public function listar () {
        return $this->model->readAll();
    }

    public function borrar(int $id, int $user_id, int $digimon_id): void {
        $borrado = $this->model->delete($id, $user_id, $digimon_id);
        $redireccion = "location:index.php?accion=listar&tabla=equipos&evento=borrar&id={$id}&user_id={$user_id}&digimon_id={$digimon_id}";
        //$redireccion = "location:index.php?accion=listar&tabla=user&evento=borrar&id={$id}&nombre={$nombre}&usuario={$usuario}";
        //$redireccion = "location:index.php?accion=listar&tabla=user&evento=borrar&id={$id}";
        if ($borrado == false) $redireccion .=  "&error=true";
        header($redireccion);
        exit();
    }

    public function editar(string $id, array $arrayTeam): void {
        $error = false;
        $errores = [];
        if (isset($_SESSION["errores"])) {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
        }
    
        // ERRORES DE TIPO
    
        //campos NO VACIOS
        $arrayNoNulos = ["user_id", "digimon_id"];
        $nulos = HayNulos($arrayNoNulos, $arrayTeam);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} NO puede estar vacio.";
            }
        }
    
        //todo correcto
        $editado = false;
        if (!$error) $editado = $this->model->edit($id, $arrayTeam);
    
        if ($editado == false) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayTeam;
            $redireccion = "location:index.php?accion=editar&tabla=equipos&evento=modificar&id={$id}&user_id={$arrayTeam['user_id']}&digimon_id={$arrayTeam['digimon_id']}&error=true";
        } else {
            //vuelvo a limpiar por si acaso
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            //este es el nuevo numpieza
            $id = $arrayTeam["id"];
            $redireccion = "location:index.php?accion=editar&tabla=equipos&evento=modificar&id={$id}&user_id={$arrayTeam['user_id']}&digimon_id={$arrayTeam['digimon_id']}";
        }
        header($redireccion);
        exit ();
        //vuelvo a la pagina donde estaba
    }

    // public function buscar(string $campo = "name", string $metodo = "contains", string $texto = "", bool  $comprobarSiEsBorrable = false): array {
    //     $users = $this->model->search($texto, $campo, $metodo);

    //     if ($comprobarSiEsBorrable) {
    //         foreach ($users as $user) {
    //             $user->esBorrable = $this->esBorrable($user);
    //         }
    //     }
    //     return $users;
    // }

    // private function esBorrable(stdClass $user): bool
    // {
    //     $projectController = new ProjectsController();
    //     $borrable = true;
    //     // si ese usuario está en algún proyecto, No se puede borrar. || igual
    //     if (count($projectController->buscar("id", "equals", $user->id)) > 0)
    //         $borrable = false;

    //     return $borrable;
    // }
}
