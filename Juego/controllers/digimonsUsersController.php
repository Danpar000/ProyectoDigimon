<?php
if (isset($_REQUEST["funcion"])) {
    require_once "../models/digimonUsersModel.php";
    require_once "../controllers/usersController.php";
    require_once "../controllers/teamUsersController.php";
} else {
    require_once "models/digimonUsersModel.php";
    require_once "controllers/usersController.php";
    require_once "controllers/teamUsersController.php";
}

class DigimonsUsersController { 
    private $model;

    public function __construct(){
        $this->model = new DigimonUsersModel();
    }


    public function checkDigimonIDs(array $list, stdClass $preEvolution, stdClass $nextEvolution) {
        $owned = false;
        
        // Obtengo todos los digimones y compruebo si los tienes
        foreach ($list as $digimon) {
            if ($digimon->digimon_id == $nextEvolution->id) {
                // Ya tienes el Digimon.
                return false;
            } else {
                // Compruebo si tienes el digimon a mejorar.
                if ($digimon->digimon_id == $preEvolution->id) {
                    $owned = true;
                }
            }
        }

        // Si no tienes el digimon a mejorar
        if ($owned == false) {
            return false;
        }
        // Si no corresponde el NEID enviado con el actual
        if ($preEvolution->next_evolution_id != $nextEvolution->id) {
            return false;
        } else {
            return true;
        }
    }

    public function generarDigimon($user_id){
        require_once "controllers/digimonsController.php";
        require_once "controllers/teamUsersController.php";
        $digimonController = new DigimonsController();
        $teamController = new TeamUsersController();

        $list = $digimonController->buscar("level", "equals", 1);

        $digimonYaObtenidos = [];
        $addDigimon = [];
        while (count($digimonYaObtenidos) < 3) {
            $random = rand(0, count($list) - 1);
            if (!in_array($random, $digimonYaObtenidos)) {
                $digimonYaObtenidos[] = $random;
                $this->model->insert(["user_id" => $user_id, "digimon_id" => $list[$random]->id]);
                $addDigimon[] = ["user_id" => $user_id, "digimon_id" => $list[$random]->id];
            }
        }
        $teamController->crear($addDigimon);
    }


    public function evolveDigimon (int $user_id, stdClass $preEvolution, stdClass $nextEvolution, int $digiEvolutions) {
        $list = $this->model->search($user_id, "user_id", "equals");
        $this->checkDigimonIDs($list, $preEvolution, $nextEvolution);
        if (!$this->checkDigimonIDs($list, $preEvolution, $nextEvolution) || $digiEvolutions <= 0) {
            header("location: index.php");
            exit();
        } else {
            $this->addDigimon($user_id, $nextEvolution->id);
            $this->borrarDigi($user_id, $preEvolution->id);
            $userController = new UsersController();
            $userController->editarDigievolucion($user_id, $digiEvolutions-1);
            $_SESSION["username"]->digievolutions = $_SESSION["username"]->digievolutions-1;

            $teamUserController = new TeamUsersController();
            $teamUserController->editarEquipo($_SESSION["username"]->id, $nextEvolution->id, $preEvolution->id);
            header("location: index.php?tabla=digimons_users&accion=listar");
            exit();
        }
    }

    public function addDigimon (int $user_id,int $digimon_id):void {
        $this->model->insert(["user_id" => $user_id, "digimon_id" => $digimon_id]);
    }


    public function ver(int $user_id): ?stdClass {
        return $this->model->read($user_id);
    }

    public function listar () {
        return $this->model->readAll();
    }

    public function borrarDigi(int $user_id, int $digimon_id): void {
        $this->model->deleteDigi($user_id, $digimon_id);
    }

     public function buscar(string $campo = "name", string $metodo = "contains", string $texto = "", bool  $comprobarSiEsBorrable = false): array
    {
        $list = $this->model->search($texto, $campo, $metodo);
        return $list;
    }

}
