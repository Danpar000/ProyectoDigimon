<?php
require_once('config/db.php');
require_once('assets/php/funciones.php');
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

    public function read(int $id):array {
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

    // public function delete (int $id):bool {
    //     $sql="DELETE FROM team_users WHERE digimon_id =:digimon_id";
    //     try {
    //         $sentencia = $this->conexion->prepare($sql);
    //         //devuelve true si se borra correctamente
    //         //false si falla el borrado
    //         $sentencia->execute([":digimon_id" => $id]);
    //         return ($sentencia->rowCount ()<=0)?false:true;
    //     }  catch (Exception $e) {
    //         echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
    //         return false;
    //     }
    // }

    public function edit (int $idAntiguo, array $arrayDigimonUser):bool{
        try {
            $sql = "UPDATE digimons_users SET user_id = :user_id,
                    digimon_id = :digimon_id WHERE id = :id";

            $arrayDatos=[
                ":id" => $idAntiguo,
                ":user_id" => $arrayDigimonUser["user_id"],
                ":digimon_id" => $arrayDigimonUser["digimon_id"]
            ];

            $sentencia = $this->conexion->prepare($sql);
            return $sentencia->execute($arrayDatos); 
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
            return false;
        }
    }

    // public function search (string $info, string $campo, string $tipo):array {
    //     if ($campo == "id" || $campo == "user_id" || $campo == "digimon_id") {
    //         switch ($tipo){
    //             case 'startswith':
    //                 $info="$info%";
    //                 break;
    //             case 'endswith':
    //                 $info="%$info";
    //                 break;
    //             case 'contains':
    //                 $info="%$info%";
    //                 break;
    //             case 'equals':
    //                 $info="$info";
    //                 break;
    //         }
    //     } else {
    //         header("location: index.php");
    //         exit();
    //     }

    //     $sentencia = $this->conexion->prepare("SELECT * FROM digimons_users WHERE $campo LIKE :info");
        
    //     // Que es esto?
    //     //ojo el si ponemos % siempre en comillas dobles "
    //     // $arrayDatos=[":usuario"=>"%$usuario%" ];


    //     $arrayDatos=[":info"=>$info];
    //     $resultado = $sentencia->execute($arrayDatos);
    //     if (!$resultado) return [];
    //     $digimonsUsers = $sentencia->fetchAll(PDO::FETCH_OBJ); 
    //     return $digimonsUsers; 
    // }

    public function exists(string $campo, string $valor):bool{
        $sentencia = $this->conexion->prepare("SELECT * FROM team_users WHERE $campo=:valor");
        $arrayDatos = [":valor" => $valor];
        $resultado = $sentencia->execute($arrayDatos);
        return (!$resultado || $sentencia->rowCount()<=0)?false:true;
    }
}