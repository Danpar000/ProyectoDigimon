<?php
if (isset($_REQUEST["funcion"])) {
    require_once "../models/userModel.php";
} else {
    require_once "models/userModel.php";
}

// require_once "controllers/projectsController.php";

class UsersController { 
    private $model;

    public function __construct(){
        $this->model = new UserModel();
    }

    public function ver(int $id): ?stdClass {
        return $this->model->read($id);
    }

    public function listar () {
        return $this->model->readAll();
    }

    public function getSessionUsername() {
        header('Content-Type: application/json');
        echo json_encode($_SESSION["username"]->username);
    }

    public function getSessionID() {
        header('Content-Type: application/json');
        echo json_encode($_SESSION["username"]->id);
    }

    public function editarEstadisticas(string $id, array $arrayUser) {
        $this->model->editStats($id, $arrayUser);
    }

    // public function editar(string $id, array $arrayUser): void {
    //     $error = false;
    //     $errores = [];
    //     if (isset($_SESSION["errores"])) {
    //         unset($_SESSION["errores"]);
    //         unset($_SESSION["datos"]);
    //     }
    
    //     // ERRORES DE TIPO
    
    //     //campos NO VACIOS
    //     $arrayNoNulos = ["username", "password", "digievolutions"];
    //     $nulos = HayNulos($arrayNoNulos, $arrayUser);
    //     if (count($nulos) > 0) {
    //         $error = true;
    //         for ($i = 0; $i < count($nulos); $i++) {
    //             $errores[$nulos[$i]][] = "El campo {$nulos[$i]} NO puede estar vacio.";
    //         }
    //     }
        
    //     //CAMPOS UNICOS
    //     $arrayUnicos = [];
    //     if ($arrayUser["username"] != $arrayUser["usernameOriginal"]) $arrayUnicos[] = "username";
    
    //     foreach ($arrayUnicos as $CampoUnico) {
    //         if ($this->model->exists($CampoUnico, $arrayUser[$CampoUnico])) {
    //             $errores[$CampoUnico][] = "El {$CampoUnico}  {$arrayUser[$CampoUnico]}  ya existe.";
    //             $error = true;
    //         }
    //     }
    
    //     //todo correcto
    //     $editado = false;
    //     if (!$error) $editado = $this->model->edit($id, $arrayUser);
    
    //     if ($editado == false) {
    //         $_SESSION["errores"] = $errores;
    //         $_SESSION["datos"] = $arrayUser;
    //         $redireccion = "location:index.php?accion=editar&tabla=user&evento=modificar&id={$id}&error=true";
    //     } else {
    //         //vuelvo a limpiar por si acaso
    //         unset($_SESSION["errores"]);
    //         unset($_SESSION["datos"]);
    //         //este es el nuevo numpieza
    //         $id = $arrayUser["id"];
    //         $redireccion = "location:index.php?accion=editar&tabla=user&evento=modificar&id={$id}";
    //     }
    //     header($redireccion);
    //     exit ();
    //     //vuelvo a la pagina donde estaba
    // }

    public function buscar(string $campo = "usuario", string $metodo = "contains", string $texto = "", bool  $comprobarSiEsBorrable = false): array {
        $users = $this->model->search($texto, $campo, $metodo);

        // if ($comprobarSiEsBorrable) {
        //     foreach ($users as $user) {
        //         $user->esBorrable = $this->esBorrable($user);
        //     }
        // }
        return $users;
    }

    public function editarDigievolucion($user_id, $digiEvolutions) {
        $this->model->updateDigievolutions($user_id, $digiEvolutions);
    }

    // private function esBorrable(stdClass $user): bool
    // {
    //     $projectController = new ProjectsController();
    //     $borrable = true;
    //     // si ese usuario está en algún proyecto, No se puede borrar.
    //     if (count($projectController->buscar("user_id", "igual", $user->id)) > 0)
    //         $borrable = false;

    //     return $borrable;
    // }
}
