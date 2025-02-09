<?php
require_once('config/db.php');
require_once('assets/php/funciones.php');
class DigimonUsersModel {
    private $conexion;

    public function __construct() {
        $this->conexion = db::conexion();
    }

    public function read(int $id): ?stdClass {
        try{
            $sentencia = $this->conexion->prepare("SELECT * FROM digimons_users WHERE id=:id");
            $arrayDatos = [":id" => $id];
            $sentencia->execute($arrayDatos);
            $user = $sentencia->fetch(PDO::FETCH_OBJ);
            //fetch duevelve el objeto stardar o false si no hay persona
            return ($user == false) ? null : $user;
        } catch (Exception $e){
            echo 'Excepción capturada al insertar: ',  $e->getMessage(), "<br>";
            return null;
        }
    }

    public function readAll():array {
        $sentencia = $this->conexion->prepare("SELECT * FROM digimons_users;");
        $sentencia->execute();
        $digimonsUsers = $sentencia->fetchAll(PDO::FETCH_OBJ);      

        return $digimonsUsers;
    }

    public function search (string $info, string $campo, string $tipo):array {
        if ($campo == "id" || $campo == "user_id" || $campo == "digimon_id") {
            switch ($tipo){
                case 'startswith':
                    $info="$info%";
                    break;
                case 'endswith':
                    $info="%$info";
                    break;
                case 'contains':
                    $info="%$info%";
                    break;
                case 'equals':
                    $info="$info";
                    break;
            }
        } else {
            header("location: index.php");
            exit();
        }

        $sentencia = $this->conexion->prepare("SELECT * FROM digimons_users WHERE $campo LIKE :info");
        
        $arrayDatos=[":info"=>$info];
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado) return [];
        $digimonsUsers = $sentencia->fetchAll(PDO::FETCH_OBJ); 
        return $digimonsUsers; 
    }
}