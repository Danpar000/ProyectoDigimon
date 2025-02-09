<?php
if (isset($_REQUEST["funcion"])) {
    require_once "../models/teamUsersModel.php";
} else {
    require_once "models/teamUsersModel.php";
}

class TeamUsersController { 
    private $model;

    public function __construct(){
        $this->model = new TeamUsersModel();
    }

    public function crear(array $arrayTeam) {
        foreach ($arrayTeam as $team) {
            $this->model->insert($team);
        }
    }

    public function ver(int $id) {
        return $this->model->read($id);
    }

    public function verEquipoJson(int $id) {
        header('Content-Type: application/json');
        $team = $this->model->read($id);
        echo json_encode($team);
    }

    public function listar () {
        return $this->model->readAll();
    }

    public function editarEquipo(int $user_id, int $newDigimon_id, int $oldDigimon_id) {
        $this->model->edit($user_id, $newDigimon_id, $oldDigimon_id);
    }

    public function listarNoUsadosJson(int $id, int $digimon_id1, int $digimon_id2, int $digimon_id3) {
        header('Content-Type: application/json');
        $available = $this->model->readNotInTeam($id, $digimon_id1, $digimon_id2, $digimon_id3);
        echo json_encode($available);
    }
}
