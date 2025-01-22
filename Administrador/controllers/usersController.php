<?php
require_once "models/userModel.php";
// require_once "controllers/projectsController.php";

class UsersController { 
    private $model;

    public function __construct(){
        $this->model = new UserModel();
    }

    public function crear (array $arrayUser):void {
        $error = false;
        $errores = [];
        if (isset($_SESSION["errores"])) {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
        }
    
        // ERRORES DE TIPO
        // Formato del nombre
        if (!is_valid_username($arrayUser["username"])){
            $error = true;
            $errores["username"][] = "Solo puedes usar letras y números en el nombre del usuario";
        }

        //campos NO VACIOS
        $arrayNoNulos = ["username", "password", "digievolutions"];
        $nulos = HayNulos($arrayNoNulos, $arrayUser);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} no puede estar vacio";
            }
        }

        //Ya existe
        if ($this->model->exists("username", $arrayUser["username"])) {
            $errores["name"][] = "Este usuario ya está registrado";
            $error = true;
        }
        
        // Digievolución negativa
        if ($arrayUser["digievolutions"] < 0) {
            $error = true;
            $errores["digievolutions"][] = "No puedes tener digievoluciones negativas";
        }

        // Tamaño imagen y formato
        $campos = ["image"];
        $imageError = checkSizeFormat($campos, $_FILES);

        if (count($imageError) > 0) {
            $error = true;
            foreach ($imageError as $campo) {
                $errores[$campo][] = "El campo {$campo} tiene un tamaño de imagen o formato incorrectos (JPG, PNG, GIF - 3MB Máx.).";
            }
        }

        $id = null;
        if (!$error) $id = $this->model->insert ($arrayUser);

        $redireccion="location:index.php?tabla=user&accion=crear";
        if ($id == null) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayUser;
            $redireccion.="&error=true&id={$id}";
        } else {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            $redireccion = "location:index.php?tabla=user&accion=ver&id=".$id;

            // CREAR DIRECTORIO
            if (!mkdir("assets/img/users/".$arrayUser["username"], 0755, true)) {
                die('Fallo al crear las carpetas...');
            }

            // SUBIR FOTOS
            if (isset($arrayUser["image"]) && $arrayUser["image"]["error"] === 0) { 
                // Hay foto subida
                $temporal = $arrayUser["image"]["tmp_name"];
                $destino = "assets/img/users/{$arrayUser['username']}/profile.png";
                if (!move_uploaded_file($temporal, $destino)) {
                    echo "No se pudo mover la imagen.";
                    $redireccion .= "arriba";
                }
            } else {
                // No hay foto
                $defaultImage = "assets/img/users/default.png";
                $destino = "assets/img/users/{$arrayUser['username']}/profile.png";
                if (!copy($defaultImage, $destino)) {
                    echo "No se pudo copiar la imagen.";
                    $redireccion .= "abajo";
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

    public function borrar(int $id, string $username): void {
        $borrado = $this->model->delete($id, $username);
        $redireccion = "location:index.php?accion=listar&tabla=user&evento=borrar&id={$id}&username={$username}";
        //$redireccion = "location:index.php?accion=listar&tabla=user&evento=borrar&id={$id}&nombre={$nombre}&usuario={$usuario}";
        //$redireccion = "location:index.php?accion=listar&tabla=user&evento=borrar&id={$id}";
        if ($borrado == false) $redireccion .=  "&error=true";
        header($redireccion);
        exit();
    }

    public function editar(string $id, array $arrayUser): void {
        $error = false;
        $errores = [];
        if (isset($_SESSION["errores"])) {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
        }
    
        // ERRORES DE TIPO
    
        //campos NO VACIOS
        $arrayNoNulos = ["username", "password", "digievolutions"];
        $nulos = HayNulos($arrayNoNulos, $arrayUser);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} NO puede estar vacio.";
            }
        }
        
        //CAMPOS UNICOS
        $arrayUnicos = [];
        if ($arrayUser["username"] != $arrayUser["usernameOriginal"]) $arrayUnicos[] = "username";
    
        foreach ($arrayUnicos as $CampoUnico) {
            if ($this->model->exists($CampoUnico, $arrayUser[$CampoUnico])) {
                $errores[$CampoUnico][] = "El {$CampoUnico}  {$arrayUser[$CampoUnico]}  ya existe.";
                $error = true;
            }
        }
    
        //todo correcto
        $editado = false;
        if (!$error) $editado = $this->model->edit($id, $arrayUser);
    
        if ($editado == false) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayUser;
            $redireccion = "location:index.php?accion=editar&tabla=user&evento=modificar&id={$id}&error=true";
        } else {
            //vuelvo a limpiar por si acaso
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            //este es el nuevo numpieza
            $id = $arrayUser["id"];
            $redireccion = "location:index.php?accion=editar&tabla=user&evento=modificar&id={$id}";
        }
        header($redireccion);
        exit ();
        //vuelvo a la pagina donde estaba
    }

    public function buscar(string $campo = "usuario", string $metodo = "contains", string $texto = "", bool  $comprobarSiEsBorrable = false): array {
        $users = $this->model->search($texto, $campo, $metodo);

        // if ($comprobarSiEsBorrable) {
        //     foreach ($users as $user) {
        //         $user->esBorrable = $this->esBorrable($user);
        //     }
        // }
        return $users;
    }

    // private function esBorrable(stdClass $user): bool
    // {
    //     $projectController = new ProjectsController();
    //     $borrable = true;
    //     // si ese usuario está en algún proyecto, No se puede borrar.
    //     if (count($projectController->buscar("user_id", "igual", $user->id)) > 0)
    //         $borrable = false;

    //     return $borrable;
    // }
}
