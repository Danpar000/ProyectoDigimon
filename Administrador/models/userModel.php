<?php
require_once('config/db.php');
require_once('assets/php/funciones.php');
class UserModel
{
    private $conexion;

    public function __construct() {
        $this->conexion = db::conexion();
    }
   
    public function insert(array $user):?int { //devuelve entero o null 
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        $sql="INSERT INTO users(username, password, digievolutions)  VALUES (:username, :password, :digievolutions);";
        try{
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos=[
                ":username" => $user["username"],
                ":password" => $user["password"],
                ":digievolutions" => $user["digievolutions"]
        ];
        $sentencia->execute($arrayDatos);
        return $this->conexion->lastInsertId();
        }catch (Exception $e){
            echo 'Excepción capturada al insertar: ',  $e->getMessage(), "<br>";
            return null;
        }
    }

    public function read(int $id): ?stdClass {
        try{
            $sentencia = $this->conexion->prepare("SELECT * FROM users WHERE id=:id");
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
        $sentencia = $this->conexion->prepare("SELECT * FROM users;");
        $sentencia->execute();
        $usuarios = $sentencia->fetchAll(PDO::FETCH_OBJ);      

        return $usuarios;
    }

    public function delete (int $id, string $username):bool {
        $sql="DELETE FROM users WHERE id =:id && username = :username";
        try {
            $sentencia = $this->conexion->prepare($sql);
            //devuelve true si se borra correctamente
            //false si falla el borrado
            $arrayDatos = [
                ":id"=>$id,
                ":username"=>$username
            ];
            $sentencia->execute($arrayDatos);
            return ($sentencia->rowCount ()<=0)?false:true;
        }  catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
            return false;
        }
    }

    public function edit (int $idAntiguo, array $arrayUsuario):bool{
        try {
            // No hago el cambio si es la misma
            if ($arrayUsuario["passwordOriginal"] != $arrayUsuario["password"]) {
                $arrayUsuario["password"] = password_hash($arrayUsuario["password"], PASSWORD_DEFAULT);
            }

            $sql = "UPDATE users SET username = :username, password = :password,
                    wins = :wins, loses = :loses, digievolutions = :digievolutions
                    WHERE id = :id;";

            $arrayDatos=[
                    ":id"=>$idAntiguo,
                    ":username"=>$arrayUsuario["username"],
                    ":password"=>$arrayUsuario["password"],
                    ":wins"=>$arrayUsuario["wins"],
                    ":loses"=>$arrayUsuario["loses"]
                    ];
            $sentencia = $this->conexion->prepare($sql);
            return $sentencia->execute($arrayDatos); 
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
            return false;
        }
    }

    public function search (string $info, string $campo, string $tipo):array {
        if ($campo == "id" || $campo == "username" || $campo == "wins" || $campo == "loses" || $campo == "digievolutions") {
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

        $sentencia = $this->conexion->prepare("SELECT * FROM users WHERE $campo LIKE :info");

        $arrayDatos=[":info"=>$info];
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado) return [];
        $users = $sentencia->fetchAll(PDO::FETCH_OBJ); 
        return $users; 
    }

    public function login(string $usuario,string $password): ?stdClass {
        $sentencia = $this->conexion->prepare("SELECT * FROM users WHERE username=:username");
        $arrayDatos = [
            ":username" => $usuario,
        ];
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado) return null;
        $user = $sentencia->fetch(PDO::FETCH_OBJ);
        //fetch duevelve el objeto stardar o false si no hay persona

        return ($user == false || !password_verify($password,$user->password)) ? null : $user;

        // return $user;
    }
    public function exists(string $campo, string $valor):bool{
        $sentencia = $this->conexion->prepare("SELECT * FROM users WHERE $campo=:valor");
        $arrayDatos = [":valor" => $valor];
        $resultado = $sentencia->execute($arrayDatos);
        return (!$resultado || $sentencia->rowCount()<=0)?false:true;
    }
}