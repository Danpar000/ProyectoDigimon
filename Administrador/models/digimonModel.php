<?php
if (isset($_REQUEST["funcion"])) {
    require_once "../config/db.php";
    require_once "../assets/php/funciones.php";
} else {
    require_once "config/db.php";
    require_once "assets/php/funciones.php";
}

class DigimonModel {
    private $conexion;

    public function __construct() {
        $this->conexion = db::conexion();
    }
   
    public function insert(array $digimon):?int { //devuelve entero o null
        $sql = "INSERT INTO digimons(name, health, attack, defense, speed, type, level, next_evolution_id)
                VALUES(:name, :health, :attack, :defense, :speed, :type, :level, :next_evolution_id);";
        try {
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos = [
                ":name" => $digimon["name"],
                ":health" => $digimon["health"],
                ":attack" => $digimon["attack"],
                ":defense" => $digimon["defense"],
                ":speed" => $digimon["speed"],
                ":type" => $digimon["type"],
                ":level" => $digimon["level"],
                ":next_evolution_id" => $digimon["next_evolution_id"]
            ];

            $sentencia->execute($arrayDatos);
            return $this->conexion->lastInsertId();
        } catch (Exception $e) {
            echo 'Excepción capturada al insertar: ',  $e->getMessage(), "<br>";
            return null;
        }
    }

    public function read(int $id): ?stdClass {
        try{
            $sentencia = $this->conexion->prepare("SELECT * FROM digimons WHERE id=:id");
            $arrayDatos = [":id" => $id];
            $sentencia->execute($arrayDatos);
            $digimon = $sentencia->fetch(PDO::FETCH_OBJ);
            //fetch duevelve el objeto stardar o false si no hay persona
            return ($digimon == false) ? null : $digimon;
        } catch (Exception $e){
            echo 'Excepción capturada al insertar: ',  $e->getMessage(), "<br>";
            return null;
        }
    }

    public function readAll():array {
        $sentencia = $this->conexion->prepare("SELECT * FROM digimons;");
        $sentencia->execute();
        $digimons = $sentencia->fetchAll(PDO::FETCH_OBJ);      

        return $digimons;
    }

    public function delete (int $id):bool {
        $sql="DELETE FROM digimons WHERE id =:id";
        try {
            $sentencia = $this->conexion->prepare($sql);
            //devuelve true si se borra correctamente
            //false si falla el borrado
            $sentencia->execute([":id" => $id]);
            return ($sentencia->rowCount ()<=0)?false:true;
        }  catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
            return false;
        }
    }

    public function edit (int $idAntiguo, array $arrayDigimon):bool{
        try {
            // No hago el cambio si es la misma

            $sql = "UPDATE digimons SET health = :health, attack = :attack,
                    defense = :defense, speed = :speed, next_evolution_id = :next_evolution_id WHERE id=:id";

            $arrayDatos=[
                ":id"=>$idAntiguo,
                ":health"=>$arrayDigimon["health"],
                ":attack"=>$arrayDigimon["attack"],
                ":defense"=>$arrayDigimon["defense"],
                ":speed"=>$arrayDigimon["speed"],
                ":next_evolution_id"=>$arrayDigimon["next_evolution_id"],
                ];

            $sentencia = $this->conexion->prepare($sql);
            return $sentencia->execute($arrayDatos);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
            return false;
        }
    }

    public function search (string $info, string $campo, string $tipo):array {
        if ($campo == "id" || $campo == "name" || $campo == "attack" || $campo == "defense" || $campo == "type" || $campo == "level") {
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

        $sentencia = $this->conexion->prepare("SELECT * FROM digimons WHERE $campo LIKE :info");
        $arrayDatos=[":info"=>$info];
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado) return [];
        $digimons = $sentencia->fetchAll(PDO::FETCH_OBJ); 
        return $digimons; 
    }

    public function deepSearch (array $info, array $campo):array {
        $query = "SELECT * from digimons
        WHERE $campo[0] = :valor0
        AND $campo[1] = :valor1
        AND id NOT IN (SELECT next_evolution_id FROM digimons WHERE $campo[0] = :valor0 AND $campo[1] = :valor2 AND next_evolution_id IS NOT NULL)";

        $sentencia = $this->conexion->prepare($query);
        $arrayDatos = [":valor0" => $info[0], ":valor1"=>$info[1]+1, ":valor2"=>$info[1]];
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado) return [];
        $digimons = $sentencia->fetchAll(PDO::FETCH_OBJ); 
        return $digimons; 
    }

    

    public function exists(string $campo, string $valor):bool{
        $sentencia = $this->conexion->prepare("SELECT * FROM digimons WHERE $campo=:valor");
        $arrayDatos = [":valor" => $valor];
        $resultado = $sentencia->execute($arrayDatos);
        return (!$resultado || $sentencia->rowCount()<=0)?false:true;
    }
}