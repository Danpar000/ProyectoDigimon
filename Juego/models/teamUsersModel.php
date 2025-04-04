<?php
if (isset($_REQUEST["funcion"])) {
    require_once "../config/db.php";
    require_once "../assets/php/funciones.php";
} else {
    require_once "config/db.php";
    require_once "assets/php/funciones.php";
}
class TeamUsersModel {
    private $conexion;

    public function __construct() {
        $this->conexion = db::conexion();
    }
   
    public function insert(array $teamUsers):?int { //devuelve entero o null 
        $sql="INSERT INTO team_users(user_id, digimon_id)
        VALUES (:user_id, :digimon_id);";
        try{
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos=[
                ":user_id" => $teamUsers["user_id"],
                ":digimon_id" => $teamUsers["digimon_id"]
        ];
        $sentencia->execute($arrayDatos);
        return $this->conexion->lastInsertId();
        }catch (Exception $e){
            echo 'Excepción capturada al insertar: ',  $e->getMessage(), "<br>";
            return null;
        }
    }

    public function read(int $id) {
        try{
            $sentencia = $this->conexion->prepare("SELECT * FROM team_users WHERE user_id=:user_id");
            $arrayDatos = [":user_id" => $id];
            $sentencia->execute($arrayDatos);
            $team = $sentencia->fetchAll(PDO::FETCH_OBJ);
            return ($team == false) ? null : $team;
        } catch (Exception $e){
            echo 'Excepción capturada al insertar: ',  $e->getMessage(), "<br>";
            return [];
        }
    }

    public function readAll():array {
        $sentencia = $this->conexion->prepare("SELECT * FROM team_users;");
        $sentencia->execute();
        $teamUsers = $sentencia->fetchAll(PDO::FETCH_OBJ);      

        return $teamUsers;
    }

    public function readNotInTeam($id, $digimon_id1, $digimon_id2, $digimon_id3): array {
        $sentencia = $this->conexion->prepare("
            SELECT d.id, d.name, d.type, d.level
            FROM digimons AS d
            WHERE d.id IN (
                SELECT DISTINCT du.digimon_id
                FROM digimons_users AS du
                JOIN team_users AS tu ON tu.user_id = du.user_id
                WHERE tu.user_id = :user_id
                AND du.digimon_id NOT IN (:digimon_id1, :digimon_id2, :digimon_id3)
                );"
            );
        $arrayDatos = [
            ":user_id" => $id,
            ":digimon_id1" => $digimon_id1,
            ":digimon_id2" => $digimon_id2,
            ":digimon_id3" => $digimon_id3
        ];
        $sentencia->execute($arrayDatos);
        $notInTeam = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $notInTeam;
    }

    public function edit (int $user_id, int $newDigimon_id, int $oldDigimon_id):bool{
        try {
            $sql = "UPDATE team_users SET digimon_id = :newDigimon_id WHERE user_id = :user_id AND digimon_id = :oldDigimon_id";
            $arrayDatos=[
                ":user_id" => $user_id,
                ":newDigimon_id" => $newDigimon_id,
                ":oldDigimon_id" => $oldDigimon_id
            ];
            $sentencia = $this->conexion->prepare($sql);
            return $sentencia->execute($arrayDatos); 
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
            return false;
        }
    }

    public function exists(string $campo, string $valor):bool{
        $sentencia = $this->conexion->prepare("SELECT * FROM team_users WHERE $campo=:valor");
        $arrayDatos = [":valor" => $valor];
        $resultado = $sentencia->execute($arrayDatos);
        return (!$resultado || $sentencia->rowCount()<=0)?false:true;
    }
}