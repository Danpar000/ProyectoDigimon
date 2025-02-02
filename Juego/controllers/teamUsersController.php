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

    // public function editar(string $id, array $arrayTeam): void {
    //     $error = false;
    //     $errores = [];
    //     if (isset($_SESSION["errores"])) {
    //         unset($_SESSION["errores"]);
    //         unset($_SESSION["datos"]);
    //     }
    
    //     // ERRORES DE TIPO
    
    //     //campos NO VACIOS
    //     $arrayNoNulos = ["user_id", "digimon_id"];
    //     $nulos = HayNulos($arrayNoNulos, $arrayTeam);
    //     if (count($nulos) > 0) {
    //         $error = true;
    //         for ($i = 0; $i < count($nulos); $i++) {
    //             $errores[$nulos[$i]][] = "El campo {$nulos[$i]} NO puede estar vacio.";
    //         }
    //     }
    
    //     //todo correcto
    //     $editado = false;
    //     if (!$error) $editado = $this->model->edit($id, $arrayTeam);
    
    //     if ($editado == false) {
    //         $_SESSION["errores"] = $errores;
    //         $_SESSION["datos"] = $arrayTeam;
    //         $redireccion = "location:index.php?accion=editar&tabla=equipos&evento=modificar&id={$id}&user_id={$arrayTeam['user_id']}&digimon_id={$arrayTeam['digimon_id']}&error=true";
    //     } else {
    //         //vuelvo a limpiar por si acaso
    //         unset($_SESSION["errores"]);
    //         unset($_SESSION["datos"]);
    //         //este es el nuevo numpieza
    //         $id = $arrayTeam["id"];
    //         $redireccion = "location:index.php?accion=editar&tabla=equipos&evento=modificar&id={$id}&user_id={$arrayTeam['user_id']}&digimon_id={$arrayTeam['digimon_id']}";
    //     }
    //     header($redireccion);
    //     exit ();
    //     //vuelvo a la pagina donde estaba
    // }

    public function listarNoUsadosJson(int $id, int $digimon_id1, int $digimon_id2, int $digimon_id3) {
        header('Content-Type: application/json');
        $available = $this->model->readNotInTeam($id, $digimon_id1, $digimon_id2, $digimon_id3);
        echo json_encode($available);
    }
}
