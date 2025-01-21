<?php
require_once "models/clientModel.php";
require_once "controllers/projectsController.php";

class ClientsController { 
    private $model;

    public function __construct(){
        $this->model = new ClientModel();
    }

    public function crear (array $arrayCliente):void {
        $error = false;
        $errores = [];
        if (isset($_SESSION["errores"])) {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
        }
    
        // ERRORES DE TIPO
        if(!is_valid_dni($arrayCliente["idFiscal"]) && !is_valid_cif($arrayCliente["idFiscal"])) {
            $error = true;
            $errores["idFiscal"][] = "El idFiscal es incorrecto";
        }

        if (!is_valid_email($arrayCliente["contact_email"])) {
            $error = true;
            $errores["contact_email"][] = "El email tiene un formato incorrecto";
        }

        // Telefono contacto
        if (!is_valid_phone($arrayCliente["contact_phone_number"])){
            $error = true;
            $errores["contact_phone_number"][] = "El teléfono no tiene un formato correcto (9 carácteres y solo números)";
        }

        // Telefono company
        if (!is_valid_phone($arrayCliente["company_phone_number"])){
            $error = true;
            $errores["company_phone_number"][] = "El teléfono no tiene un formato correcto (9 carácteres y solo números)";
        }
    
        //campos NO VACIOS
        $arrayNoNulos = ["idFiscal", "contact_name", "contact_email", "company_name"];
        $nulos = HayNulos($arrayNoNulos, $arrayCliente);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} NO puede estar vacio ";
            }
        }

        //todo correcto
        $id = null;
        if (!$error) $id = $this->model->insert ($arrayCliente);

        $redireccion="location:index.php?tabla=client&accion=crear";
        if ($id == null) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayCliente;
            $redireccion.="&error=true&id={$id}";
        } else {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            $redireccion = "location:index.php?tabla=client&accion=ver&id=".$id;
        }
        //vuelvo a la pagina donde estaba
        header ($redireccion);
        exit();


        // $id=$this->model->insert ($arrayCliente);
        // ($id==null)?header("location:index.php?tabla=client&accion=crear&error=true&id={$id}"): header("location:index.php?tabla=client&accion=ver&id=".$id);
        // exit ();
    }

    public function ver(int $id): ?stdClass
    {
        return $this->model->read($id);
    }

    public function listar (){
        return $this->model->readAll ();
   }

   public function borrar(int $id, string $nombre, string $idFiscal): void
    {
    $borrado = $this->model->delete($id);
    $redireccion = "location:index.php?accion=listar&tabla=client&evento=borrar&id={$id}&contact_name={$nombre}&idFiscal={$idFiscal}";
    if ($borrado == false) $redireccion .=  "&error=true";
    header($redireccion);
    exit();
    }

    public function editar (int $id, array $arrayCliente):void {
        
        $error = false;
        $errores = [];
        if (isset($_SESSION["errores"])) {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
        }
    
        // ERRORES DE TIPO
        if(!is_valid_dni($arrayCliente["idFiscal"]) && !is_valid_cif($arrayCliente["idFiscal"])) {
            $error = true;
            $errores["idFiscal"][] = "El idFiscal es incorrecto";
        }

        if (!is_valid_email($arrayCliente["contact_email"])) {
            $error = true;
            $errores["contact_email"][] = "El email tiene un formato incorrecto";
        }

        // Telefono contacto
        if (!is_valid_phone($arrayCliente["contact_phone_number"])){
            $error = true;
            $errores["contact_phone_number"][] = "El teléfono no tiene un formato correcto (9 carácteres y solo números)";
        }

        // Telefono company
        if (!is_valid_phone($arrayCliente["company_phone_number"])){
            $error = true;
            $errores["company_phone_number"][] = "El teléfono no tiene un formato correcto (9 carácteres y solo números)";
        }
    
        //campos NO VACIOS
        $arrayNoNulos = ["idFiscal", "contact_name", "contact_email", "company_name"];
        $nulos = HayNulos($arrayNoNulos, $arrayCliente);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} NO puede estar vacio ";
            }
        }

        //CAMPOS UNICOS
        $arrayUnicos = [];
        if ($arrayCliente["idFiscal"] != $arrayCliente["idFiscalOriginal"]) $arrayUnicos[] = "idFiscal";
        if ($arrayCliente["contact_email"] != $arrayCliente["contact_emailOriginal"]) $arrayUnicos[] = "contact_email";
    
        foreach ($arrayUnicos as $CampoUnico) {
            if ($this->model->exists($CampoUnico, $arrayCliente[$CampoUnico])) {
                $errores[$CampoUnico][] = "El {$CampoUnico}  {$arrayCliente[$CampoUnico]}  ya existe. || {$arrayCliente['idFiscalOriginal']}";
                $error = true;
            }
        }
        
        
        if (!$error) $editadoCorrectamente=$this->model->edit ($id, $arrayCliente);

        $redireccion="location:index.php?tabla=client&accion=editar";
        if ($editadoCorrectamente == false) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayCliente;
            $redireccion.="&evento=modificar&id={$id}&error=true";
        } else {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            $redireccion.="&evento=modificar&id={$id}";
        }
        //vuelvo a la pagina donde estaba
        header ($redireccion);
        exit();
    }

    public function buscar(string $campo = "idFiscal", string $metodo = "contains", string $texto = "", bool  $comprobarSiEsBorrable = false): array
    {
        $clients = $this->model->search($texto, $campo, $metodo);

        if ($comprobarSiEsBorrable) {
            foreach ($clients as $client) {
                $client->esBorrable = $this->esBorrable($client);
            }
        }
        return $clients;
    }

    private function esBorrable(stdClass $client): bool
    {
        $projectController = new ProjectsController();
        $borrable = true;
        // si ese usuario está en algún proyecto, No se puede borrar.
        if (count($projectController->buscar("client_id", "equals", $client->id)) > 0)
            $borrable = false;
        return $borrable;
    }
}
