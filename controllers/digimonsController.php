<?php
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
        // Formato del nombre
        if (!is_valid_name($arrayDigimon["name"])) {
            $error = true;
            $errores["name"][] = "Solo puedes usar letras y números en el nombre del Digimon";
        }

        //Ya existe
        if ($this->model->exists("name", $arrayDigimon["name"])) {
            $errores["name"][] = "Este Digimon ya está registrado";
            $error = true;
        }

        //campos NO VACIOS
        $arrayNoNulos = ["name", "attack", "defense", "type", "level", "image", "imageVictory", "imageDefeat"];
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

        // Tamaño imagen y formato
        $campos = ["image", "imageVictory", "imageDefeat"];
        $imageError = checkSizeFormat($campos, $_FILES);

        if (count($imageError) > 0) {
            $error = true;
            foreach ($imageError as $campo) {
                $errores[$campo][] = "El campo {$campo} tiene un tamaño de imagen o formato incorrectos (JPG, PNG, GIF - 3MB Máx.).";
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

            // Creo carpeta
            if(!mkdir("assets/img/digimons/".$arrayDigimon["name"], 0755, true)) {
                die('Fallo al crear las carpetas...');
            }

            // Subo las imágenes
            $temporalBase = $arrayDigimon["image"]["tmp_name"];
            $temporalVictory = $arrayDigimon["imageVictory"]["tmp_name"];
            $temporalDefeat = $arrayDigimon["imageDefeat"]["tmp_name"];
            $destino = "assets/img/digimons/{$arrayDigimon['name']}/";

            (!move_uploaded_file($temporalBase, $destino."base.png")) ? $redireccion .= "&error=true&id={$id}" : "";
            (!move_uploaded_file($temporalVictory, $destino."victory.png")) ? $redireccion .= "&error=true&id={$id}" : "";
            (!move_uploaded_file($temporalDefeat, $destino."defeat.png")) ? $redireccion .= "&error=true&id={$id}" : "";
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
        $name = $this->ver($id);
        $ruta = "assets/img/digimons/{$name->name}";
        $files = "*.png";
        $borrado = $this->model->delete($id);
        //$redireccion = "location:index.php?accion=listar&tabla=digimons&evento=borrar&id={$id}";
        $redireccion = "location:index.php?tabla=digimons&accion=buscar&evento=todos";
        if ($borrado == false) {
            $redireccion .=  "&error=true";
        } else {
            $this->borrarCarpeta($ruta, $files);
        }
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

    private function borrarCarpeta($ruta, $file) {
        if (!is_dir($ruta)) {
            return false;
        } else {
            $files = glob($ruta . DIRECTORY_SEPARATOR . $file);
            foreach ($files as $fileToDelete) {
                if (is_file($fileToDelete)) {
                    unlink($fileToDelete);
                }
            }
            rmdir($ruta);
        }
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
