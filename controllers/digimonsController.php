<?php
// $ruta=(!isset($_REQUEST["json"]))?"":"../";
// require_once $ruta . "models/digimonModel.php";
if (isset($_REQUEST["funcion"])) {
    require_once "../models/digimonModel.php";
} else {
    require_once "models/digimonModel.php";
}
// require_once "models/digimonModel.php";

class DigimonsController { 
    private $model;

    public function __construct(){
        $this->model = new DigimonModel();
    }

    public function crear (array $arrayDigimon):void {
        $error = false;
        $errores = [];
        if (isset($_SESSION["errores"])) {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
        }
    
        // ERRORES DE TIPO
    

        //Ya existe
        if ($this->model->exists("name", $arrayDigimon["name"])) {
            $errores["name"][] = "Este Digimon ya está registrado";
            $error = true;
        }

        //campos NO VACIOS
        $arrayNoNulos = ["name", "attack", "defense", "type", "level"];
        $nulos = HayNulos($arrayNoNulos, $arrayDigimon);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} NO puede estar vacio";
            }
        }
        
        // Manipulación de valores
        if ($arrayDigimon["next_evolution_id"] !== null) {
            $evolucion = $this->ver($arrayDigimon["next_evolution_id"]);
            if ($arrayDigimon["level"] != $evolucion->level-1) {
                $error = true;
                $errores["next_evolution_id"][] = "Esa siguiente evolución no es válida. (Nivel incorrecto)";
            }
            if ($arrayDigimon["type"] != $evolucion->type) {
                $error = true;
                $errores["type"][] = "La siguiente evolución no es válida. (Tipo incorrecto)";
            }
        }

        $id = null;
        if (!$error) $id = $this->model->insert ($arrayDigimon);

        $redireccion="location:index.php?tabla=digimons&accion=crear";
        if ($id == null) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayDigimon;
            $redireccion.="&error=true&id={$id}";
        } else {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            $redireccion = "location:index.php?tabla=digimons&accion=ver&id=".$id;

            if(!mkdir("assets/img/digimons/".$arrayDigimon["name"], 0755, true)) {
                die('Fallo al crear las carpetas...');
            }

            //$temporal = $_FILES["image"]["tmp_name"];
            $temporal = $arrayDigimon["image"]["tmp_name"];
            $destino = "assets/img/digimons/{$arrayDigimon['name']}/base.jpg";

            if (move_uploaded_file($temporal, $destino)) {
                echo "Se subio bien";
            } else {
                $redireccion = "qweqwe";
            }
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

    public function buscarJson($tipo, $nivel){
        header('Content-Type: application/json');
        $digimons = $this->model->deepSearch([$tipo, $nivel], ["type", "level"]);
        echo json_encode($digimons);
    }

    public function borrar(int $id): void {
        $borrado = $this->model->delete($id);
        //$redireccion = "location:index.php?accion=listar&tabla=digimons&evento=borrar&id={$id}";
        $redireccion = "location:index.php?tabla=digimons&accion=buscar&evento=todos";
        if ($borrado == false) $redireccion .=  "&error=true";
        header($redireccion);
        exit();
    }

    public function editar(string $id, array $arrayDigimon): void {
        $error = false;
        $errores = [];
        if (isset($_SESSION["errores"])) {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
        }
    
        // ERRORES DE TIPO
    
        //campos NO VACIOS
        $arrayNoNulos = ["name", "attack", "defense", "type", "level"];
        $nulos = HayNulos($arrayNoNulos, $arrayDigimon);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} NO puede estar vacio.";
            }
        }
        
        //CAMPOS UNICOS
        $arrayUnicos = [];
        if ($arrayDigimon["name"] != $arrayDigimon["nameOriginal"]) $arrayUnicos[] = "name";
    
        foreach ($arrayUnicos as $CampoUnico) {
            if ($this->model->exists($CampoUnico, $arrayDigimon[$CampoUnico])) {
                $errores[$CampoUnico][] = "El {$CampoUnico}  {$arrayDigimon[$CampoUnico]}  ya existe.";
                $error = true;
            }
        }
    
        //todo correcto
        $editado = false;
        if (!$error) $editado = $this->model->edit($id, $arrayDigimon);
    
        if ($editado == false) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayDigimon;
            $redireccion = "location:index.php?accion=editar&tabla=digimons&evento=modificar&id={$id}&error=true";
        } else {
            //vuelvo a limpiar por si acaso
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            //este es el nuevo numpieza
            $id = $arrayDigimon["id"];
            $redireccion = "location:index.php?accion=editar&tabla=digimons&evento=modificar&id={$id}";
        }
        header($redireccion);
        exit ();
        //vuelvo a la pagina donde estaba
    }

    public function buscar(string $campo = "name", string $metodo = "contains", string $texto = "", bool  $comprobarSiEsBorrable = false): array{
        $digimons = $this->model->search($texto, $campo, $metodo);

        // if ($comprobarSiEsBorrable) {
        //     foreach ($digimons as $digimon) {
        //         $digimon->esBorrable = $this->esBorrable($digimon);
        //     }
        // }
        return $digimons;
    }

    // private function esBorrable(stdClass $digimon): bool
    // {
    //     $teamsUserController = new ProjectsController();
    //     $borrable = true;
    //     // si ese digimon está en algún equipo, No se puede borrar. || igual
    //     if (count($teamsUserController->buscar("id", "equals", $digimon->id)) > 0)
    //         $borrable = false;

    //     return $borrable;
    // }
}
