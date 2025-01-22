<?php
require_once('config/db.php');
require_once('assets/php/funciones.php');
class ClientModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }
   
    public function insert(array $client):?int //devuelve entero o null
    {
        $sql="INSERT INTO clients(idFiscal, contact_name, contact_email, contact_phone_number, company_name, company_address, company_phone_number)  VALUES (:idFiscal, :contact_name, :contact_email, :contact_phone_number, :company_name, :company_address, :company_phone_number);";
        try{
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos=[
                ":idFiscal" => $client["idFiscal"],
                ":contact_name" => $client["contact_name"],
                ":contact_email" => $client["contact_email"],
                ":contact_phone_number" => $client["contact_phone_number"],
                ":company_name" => $client["company_name"],
                ":company_address" => $client["company_address"],
                ":company_phone_number" => $client["company_phone_number"]
        ];
        $resultado = $sentencia->execute($arrayDatos);
        return $this->conexion->lastInsertId();
        }catch (Exception $e){
            echo 'Excepción capturada al insertar: ',  $e->getMessage(), "<bR>";
            return null;
        }
    }

    public function read(int $id): ?stdClass
    {
    try {
    $sentencia = $this->conexion->prepare("SELECT * FROM clients WHERE id=:id");
    $arrayDatos = [":id" => $id];
    $resultado = $sentencia->execute($arrayDatos);
    // ojo devuelve true si la consulta se ejecuta correctamente
    // eso no quiere decir que hayan resultados
    //como sólo va a devolver un resultado uso fetch
    // DE Paso probamos el FETCH_OBJ
    $client = $sentencia->fetch(PDO::FETCH_OBJ);
    //fetch duevelve el objeto stardar o false si no hay persona
    return ($client == false) ? null : $client;
    } catch (Exception $e){
        return null;
    }
    }

    public function readAll():array 
    {
    $sentencia = $this->conexion->prepare("SELECT * FROM clients;");
    $sentencia->execute();
    $clients = $sentencia->fetchAll(PDO::FETCH_OBJ);    
    
    // for ($i=0; $i < count($usuarios); $i++) {}
    // $usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);    

    return $clients;
    }

    public function delete (int $id):bool
    {
    $sql="DELETE FROM clients WHERE id =:id";
    try {
        $sentencia = $this->conexion->prepare($sql);
        //devuelve true si se borra correctamente
        //false si falla el borrado
        $resultado= $sentencia->execute([":id" => $id]);
        return ($sentencia->rowCount ()<=0)?false:true;
    }  catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
        return false;
    }
    }

    public function edit (int $idAntiguo, array $arrayCliente):bool{
        try {
            $sql="UPDATE clients SET idFiscal = :idFiscal, contact_name = :contact_name, ";
            $sql.= "contact_email = :contact_email, contact_phone_number= :contact_phone_number, ";
            $sql.= "company_name = :company_name, company_address= :company_address, company_phone_number= :company_phone_number";
            $sql.= " WHERE id = :id;";
            $arrayDatos=[
                    ":id"=>$idAntiguo,
                    ":idFiscal"=>$arrayCliente["idFiscal"],
                    ":contact_name"=>$arrayCliente["contact_name"],
                    ":contact_email"=>$arrayCliente["contact_email"],
                    ":contact_phone_number"=>$arrayCliente["contact_phone_number"],
                    ":company_name"=>$arrayCliente["company_name"],
                    ":company_address"=>$arrayCliente["company_address"],
                    ":company_phone_number"=>$arrayCliente["company_phone_number"]
                    ];
            $sentencia = $this->conexion->prepare($sql);
            return $sentencia->execute($arrayDatos); 
        } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
                return false;
        }
    }

    public function search (string $info, string $campo, string $tipo):array{
        if ($campo == "id" || $campo == "idFiscal" || $campo == "contact_name" || $campo == "contact_email" || $campo == "contact_phone_number" || $campo == "company_name" || $campo == "company_address" || $campo == "company_phone_number") {
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

        // $sentencia = $this->conexion->prepare("SELECT * FROM users WHERE usuario LIKE :usuario");
        //$sentencia = $this->conexion->prepare("SELECT * FROM users WHERE :campo LIKE :info");
        $sentencia = $this->conexion->prepare("SELECT * FROM clients WHERE $campo LIKE :info");
        //ojo el si ponemos % siempre en comillas dobles "
        // $arrayDatos=[":usuario"=>"%$usuario%" ];
        $arrayDatos=[":info"=>$info];
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado) return [];
        $users = $sentencia->fetchAll(PDO::FETCH_OBJ); 
        return $users; 
    }

    public function exists(string $campo, string $valor):bool{
        $sentencia = $this->conexion->prepare("SELECT * FROM clients WHERE $campo=:valor");
        $arrayDatos = [":valor" => $valor];
        $resultado = $sentencia->execute($arrayDatos);
        return (!$resultado || $sentencia->rowCount()<=0)?false:true;
        }
}