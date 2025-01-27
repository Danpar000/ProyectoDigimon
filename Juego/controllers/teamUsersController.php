<?php
require_once "models/teamUsersModel.php";

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

    public function ver(int $id): array {
        return $this->model->read($id);
    }

    public function listar () {
        return $this->model->readAll();
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
}
