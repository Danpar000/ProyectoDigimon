<?php
if (isset($_REQUEST["funcion"])) {
    require_once "../config/db.php";
    require_once "../assets/php/funciones.php";
} else {
    require_once "config/db.php";
    require_once "assets/php/funciones.php";
}
class DigimonUsersModel {
    private $conexion;

    public function __construct() {
        $this->conexion = db::conexion();
    }
   
    public function insert(array $digimonUser):?int { //devuelve entero o null 
        $sql="INSERT INTO digimons_users(user_id, digimon_id)
        VALUES (:user_id, :digimon_id);";
        try{
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos=[
                ":user_id" => $digimonUser["user_id"],
                ":digimon_id" => $digimonUser["digimon_id"]
        ];
        $sentencia->execute(params: $arrayDatos);
        return $this->conexion->lastInsertId();
        }catch (Exception $e){
            echo 'Excepción capturada al insertar: ',  $e->getMessage(), "<br>";
            return null;
        }
    }

    public function read(int $user_id): ?stdClass {
        try{
            $sentencia = $this->conexion->prepare("SELECT * FROM digimons_users WHERE user_id=:user_id ORDER BY digimon_id");
            $arrayDatos = [":user_id" => $user_id];
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

    public function delete (int $id):bool {
        $sql="DELETE FROM digimons_users WHERE id =:id";
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

    public function deleteDigi (int $user_id, int $digimon_id):bool {
        $sql="DELETE FROM digimons_users WHERE user_id =:user_id AND digimon_id=:digimon_id";
        try {
            $sentencia = $this->conexion->prepare($sql);
            //devuelve true si se borra correctamente
            //false si falla el borrado
            $sentencia->execute([":user_id" => $user_id,":digimon_id" => $digimon_id]);
            return ($sentencia->rowCount ()<=0)?false:true;
        }  catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
            return false;
        }
    }

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

        $sentencia = $this->conexion->prepare("SELECT * FROM digimons_users WHERE $campo LIKE :info ORDER BY digimon_id");
        $arrayDatos=[":info"=>$info];
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado) return [];
        $digimonsUsers = $sentencia->fetchAll(PDO::FETCH_OBJ); 
        return $digimonsUsers; 
    }

    public function exists(string $campo, string $valor):bool{
        $sentencia = $this->conexion->prepare("SELECT * FROM digimons_users WHERE $campo=:valor");
        $arrayDatos = [":valor" => $valor];
        $resultado = $sentencia->execute($arrayDatos);
        return (!$resultado || $sentencia->rowCount()<=0)?false:true;
    }
}