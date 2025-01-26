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

    public function crear (array $arrayDigimon):void {
        $error = false;
        $errores = [];
        if (isset($_SESSION["errores"])) {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
        }
    
        // ERRORES DE TIPO
        //Vida negativa
        if ($arrayDigimon["health"] <= 0) {
            $error = true;
            $errores["health"][] = "El digimon no puede tener menos de 1 de vida";
        }

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
        $arrayNoNulos = ["name", "health", "attack", "defense", "speed", "type", "level"];
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
                $errores[$campo][] = "El campo {$campo} tiene un tamaño de imagen o formato incorrectos (JPG, JPEG, PNG, GIF - 3MB Máx.).";
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

            // SUBIR FOTOS
            if (isset($arrayDigimon["image"]) && $arrayDigimon["image"]["error"] === 0) { 
                // Hay foto subida
                $temporal = $arrayDigimon["image"]["tmp_name"];
                $destino = "assets/img/digimons/{$arrayDigimon['name']}/base.png";
                if (!move_uploaded_file($temporal, $destino)) {
                    echo "No se pudo mover la imagen.";
                    $redireccion .= "&error=true&id={$id}";
                }
            } else {
                // No hay foto
                $defaultImage = "assets/img/digimons/base.png";
                $destino = "assets/img/digimons/{$arrayDigimon['name']}/base.png";
                if (!copy($defaultImage, $destino)) {
                    echo "No se pudo copiar la imagen.";
                    $redireccion .= "&error=true&id={$id}";
                }
            }
            // FOTO VICTORIA
            if (isset($arrayDigimon["imageVictory"]) && $arrayDigimon["imageVictory"]["error"] === 0) { 
                // Hay foto subida
                $temporal = $arrayDigimon["imageVictory"]["tmp_name"];
                $destino = "assets/img/digimons/{$arrayDigimon['name']}/victory.png";
                if (!move_uploaded_file($temporal, $destino)) {
                    echo "No se pudo mover la imagen.";
                    $redireccion .= "&error=true&id={$id}";
                }
            } else {
                // No hay foto
                $defaultImage = "assets/img/digimons/victory.png";
                $destino = "assets/img/digimons/{$arrayDigimon['name']}/victory.png";
                if (!copy($defaultImage, $destino)) {
                    echo "No se pudo copiar la imagen.";
                    $redireccion .= "&error=true&id={$id}";
                }
            }

            // FOTO DERROTA
            if (isset($arrayDigimon["imageDefeat"]) && $arrayDigimon["imageDefeat"]["error"] === 0) { 
                // Hay foto subida
                $temporal = $arrayDigimon["imageDefeat"]["tmp_name"];
                $destino = "assets/img/digimons/{$arrayDigimon['name']}/defeat.png";
                if (!move_uploaded_file($temporal, $destino)) {
                    echo "No se pudo mover la imagen.";
                    $redireccion .= "&error=true&id={$id}";
                }
            } else {
                // No hay foto
                $defaultImage = "assets/img/digimons/defeat.png";
                $destino = "assets/img/digimons/{$arrayDigimon['name']}/defeat.png";
                if (!copy($defaultImage, $destino)) {
                    echo "No se pudo copiar la imagen.";
                    $redireccion .= "&error=true&id={$id}";
                }
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

    public function verJson(int $id) {
        header('Content-Type: application/json');
        $digimon = $this->model->read($id);
        echo json_encode($digimon);
    }

    public function buscarJson($tipo, $nivel){
        header('Content-Type: application/json');
        $digimons = $this->model->deepSearch([$tipo, $nivel], ["type", "level"]);
        echo json_encode($digimons);
    }

    public function borrar(int $id): void {
        $digimon = $this->ver($id);
        $ruta = "assets/img/digimons/{$digimon->name}";
        $files = "*.png";
        $borrado = $this->model->delete($id);
        $redireccion = "location:index.php?tabla=digimons&accion=buscar&evento=borrar&id={$id}&name={$digimon->name}";
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
        //Vida negativa
        if ($arrayDigimon["health"] <= 0) {
            $error = true;
            $errores["health"][] = "El digimon no puede tener menos de 1 de vida";
        }

        // Manipulación de valores
        if ($arrayDigimon["next_evolution_id"] !== null) {
            $digimon = $this->ver($arrayDigimon["id"]);
            $evolucion = $this->ver($arrayDigimon["next_evolution_id"]);
            if ($digimon->level != $evolucion->level-1) {
                $error = true;
                $errores["next_evolution_id"][] = "Esa siguiente evolución no es válida. (Nivel incorrecto) | " + var_dump($evolucion);
            }
            if ($digimon->type != $evolucion->type) {
                $error = true;
                $errores["type"][] = "La siguiente evolución no es válida. (Tipo incorrecto)";
            }
        }


        //campos NO VACIOS
        $arrayNoNulos = ["health", "attack", "defense", "speed"];
        $nulos = HayNulos($arrayNoNulos, $arrayDigimon);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} NO puede estar vacio.";
            }
        }
    
        // Tamaño imagen y formato
        $campos = ["image", "imageVictory", "imageDefeat"];
        $imageError = checkSizeFormat($campos, $_FILES);

        if (count($imageError) > 0) {
            $error = true;
            foreach ($imageError as $campo) {
                $errores[$campo][] = "El campo {$campo} tiene un tamaño de imagen o formato incorrectos (JPG, JPEG, PNG, GIF - 3MB Máx.).";
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
            $id = $arrayDigimon["id"];
            $redireccion = "location:index.php?accion=editar&tabla=digimons&evento=modificar&id={$id}";

            // Subo las imágenes
            $destino = "assets/img/digimons/{$arrayDigimon['name']}/";
            if (!empty($arrayDigimon["image"]["tmp_name"])) {
                unlink ($destino."base.png");
                $temporalBase = $arrayDigimon["image"]["tmp_name"];
                (!move_uploaded_file($temporalBase, $destino."base.png")) ? $redireccion .= "&error=true&id={$id}" : "";
            }
        
            if (!empty($arrayDigimon["imageVictory"]["tmp_name"])) {
                unlink ($destino."victory.png");
                $temporalVictory = $arrayDigimon["imageVictory"]["tmp_name"]; 
                (!move_uploaded_file($temporalVictory, $destino."victory.png")) ? $redireccion .= "&error=true&id={$id}" : "";
            }

            if (!empty($arrayDigimon["imageDefeat"]["tmp_name"])) {
                unlink ($destino."defeat.png");
                $temporalDefeat = $arrayDigimon["imageDefeat"]["tmp_name"];
                (!move_uploaded_file($temporalDefeat, $destino."defeat.png")) ? $redireccion .= "&error=true&id={$id}" : "";
            }
        }
        header($redireccion);
        exit ();
        //vuelvo a la pagina donde estaba
    }


    public function buscar(string $campo = "name", string $metodo = "contains", string $texto = "", bool  $comprobarSiEsBorrable = false): array{
        $digimons = $this->model->search($texto, $campo, $metodo);

        if ($comprobarSiEsBorrable) {
            foreach ($digimons as $digimon) {
                $digimon->esBorrable = $this->esBorrable($digimon);
            }
        }
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

    private function esBorrable(stdClass $digimon): bool
    {
        require_once "controllers/digimonsUsersController.php";
        $DigimonsUserController = new DigimonsUsersController();
        $borrable = true;
        // si ese digimon está en el inventario de un usuario, No se puede borrar. || igual
        if (count($DigimonsUserController->buscar("digimon_id", "equals", $digimon->id)) > 0)
            $borrable = false;

        return $borrable;
    }
}
