<?php
if (isset($_REQUEST["funcion"])) {
    require_once "../models/digimonModel.php";
} else {
    require_once "models/digimonModel.php";
}
class DigimonsController { 
    private $model;

    public function __construct(){
        $this->model = new DigimonModel();
    }

    public function ver(int $id): ?stdClass {
        return $this->model->read($id);
    }

    public function verDigimonJson(int $id) {
        header('Content-Type: application/json');
        $digimon = $this->model->read($id);
        echo json_encode($digimon);
    }

    public function listar () {
        return $this->model->readAll();
    }

    public function buscarJson($tipo, $nivel){
        header('Content-Type: application/json');
        $digimons = $this->model->deepSearch([$tipo, $nivel], ["type", "level"]);
        echo json_encode($digimons);
    }

    public function buscar(string $campo = "name", string $metodo = "contains", string $texto = "", bool  $comprobarSiEsBorrable = false): array{
        $digimons = $this->model->search($texto, $campo, $metodo);
        return $digimons;
    }
}
