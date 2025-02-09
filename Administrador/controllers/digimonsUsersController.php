<?php
require_once "models/digimonUsersModel.php";


class DigimonsUsersController { 
    private $model;

    public function __construct(){
        $this->model = new DigimonUsersModel();
    }

    public function buscar(string $campo = "name", string $metodo = "contains", string $texto = "", bool  $comprobarSiEsBorrable = false): array {
        $list = $this->model->search($texto, $campo, $metodo);
        return $list;
    }
}
