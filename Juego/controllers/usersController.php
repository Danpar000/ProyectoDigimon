<?php
if (isset($_REQUEST["funcion"])) {
    require_once "../models/userModel.php";
} else {
    require_once "models/userModel.php";
}

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

    public function buscar(string $campo = "usuario", string $metodo = "contains", string $texto = "", bool  $comprobarSiEsBorrable = false): array {
        $users = $this->model->search($texto, $campo, $metodo);
        return $users;
    }

    public function editarDigievolucion($user_id, $digiEvolutions) {
        $this->model->updateDigievolutions($user_id, $digiEvolutions);
    }
}
