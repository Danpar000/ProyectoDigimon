<?php
require_once('config/db.php');

class TaskModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }

    public function read(int $id): ?stdClass
    {
        $sql = "SELECT tasks.id as idTarea, tasks.name, tasks.description, tasks.deadline,";
        $sql .="task_status, tasks.user_id as idUsuarioEncargado, projects.client_id as idCliente,";
        $sql .="projects.id as idProyecto, projects.user_id as liderProyecto ";
        $sql .="FROM tasks JOIN projects ON projects.id = project_id ";
        $sql .="WHERE tasks.id = :id;";
        $sentencia = $this->conexion->prepare($sql);
        $arrayDatos = [":id" => $id];
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado) return null;
        $task = $sentencia->fetch(PDO::FETCH_OBJ);
        return ($task == false) ? null : $task;
    }


    public function readAllPerId(int $id): array {
        $sql = "SELECT tasks.id as idTarea, tasks.name, tasks.description, tasks.deadline, ";
        $sql .="task_status, tasks.user_id as idUsuarioEncargado, projects.client_id as idCliente, ";
        $sql .="projects.id as idProyecto, projects.user_id as liderProyecto, users.usuario ";
        $sql .="FROM tasks JOIN projects ON projects.id = project_id JOIN users ON projects.user_id = users.id ";
        $sql .="WHERE project_id = :id;";
        $sentencia = $this->conexion->prepare($sql);
        $arrayDatos = [":id" => $id];
        $resultado = $sentencia->execute($arrayDatos);
        $tasks = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return ($tasks === false) ? null : $tasks;
    }

    public function readAll(): array {
        //$sentencia = $this->conexion->query("SELECT * FROM projects;");
        // $sql = "SELECT tasks.id as idTarea, tasks.name, tasks.description, tasks.deadline,";
        // $sql .="task_status, tasks.user_id as idUsuarioEncargado, projects.client_id as idCliente,";
        // $sql .="projects.id as idProyecto, projects.user_id as liderProyecto ";
        // $sql .="FROM tasks JOIN projects ON projects.id = project_id";
        $sql = "SELECT 
                    tasks.*,  
                    users.name AS name_user, 
                    users.usuario AS usuario_user, 
                    projects.name AS name_project, 
                    projects.user_id AS leader_project, 
                    clients.contact_name AS contact_name_client, 
                    clients.idFiscal AS idFiscal_client, 
                    clients.company_name AS company_name_client 
                FROM 
                    tasks 
                LEFT JOIN clients ON tasks.client_id = clients.id 
                INNER JOIN users ON tasks.user_id = users.id 
                INNER JOIN projects ON tasks.project_id = projects.id"; 

        $sentencia = $this->conexion->query($sql);
        $tareas = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $tareas;
    }

    public function insert(array $nuevaTarea): ?int //devuelve entero o null
    {

        try {
            $sql = "INSERT INTO tasks (name, description,deadline, task_status, user_id, client_id, project_id)  ";
            $sql.=" VALUES (:name, :description,:deadline, :status, :user_id, :client_id, :project_id);";
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos = [
                ":name" => $nuevaTarea["name"],
                ":description" => $nuevaTarea["description"],
                ":deadline" => $nuevaTarea["deadline"],
                ":status" => $nuevaTarea["task_status"],
                ":user_id" => $nuevaTarea["user_id"],
                ":client_id" => $nuevaTarea["client_id"],
                ":project_id" => $nuevaTarea["project_id"],
            ];
            var_dump($arrayDatos);
            $resultado = $sentencia->execute($arrayDatos);
            return ($resultado == true) ? $this->conexion->lastInsertId() : null;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return null;
        }
    }



    public function search(string $campo = "id", string $metodo = "contains", string $dato = ""): array
    {

        // $sql= "select tasks.*,  users.name as name_user,users.usuario as usuario_user, ";
        // $sql.="projects.name as name_project, project.user_id as leader_project, ";
        // $sql.="clients.contact_name as contact_name_client, clients.idFiscal as idFiscal_client, ";
        // $sql.="clients.company_name as company_name_client ";
        // $sql.="from projects ";
        // $sql.="left join clients  on  (tasks.client_id=clients.id) "; 
        // $sql.="inner join users  on  (tasks.user_id=users.id) ";
        // $sql.="inner join projects  on  (tasks.project_id=projects.id) ";
        // $sql.= " WHERE $campo LIKE :dato ;";
        // $sentencia = $this->conexion->prepare($sql);


        $sql = "SELECT 
                    tasks.*,  
                    users.name AS name_user, 
                    users.usuario AS usuario_user, 
                    projects.name AS name_project, 
                    projects.user_id AS leader_project, 
                    clients.contact_name AS contact_name_client, 
                    clients.idFiscal AS idFiscal_client, 
                    clients.company_name AS company_name_client 
                FROM 
                    tasks 
                LEFT JOIN clients ON tasks.client_id = clients.id 
                INNER JOIN users ON tasks.user_id = users.id 
                INNER JOIN projects ON tasks.project_id = projects.id 
                WHERE 
                    $campo LIKE :dato;";
        //Antes
        // $sentencia = $this->conexion->prepare("SELECT * FROM tasks WHERE $campo LIKE :dato");
        $sentencia = $this->conexion->prepare($sql);
        //ojo el si ponemos % siempre en comillas dobles "
        switch ($metodo) {
            case "contiene":
                $arrayDatos = [":dato" => "%$dato%"];
                break;
            case "empieza":
                $arrayDatos = [":dato" => "$dato%"];
                break;
            case "acaba":
                $arrayDatos = [":dato" => "%$dato"];
                break;
            case "igual":
                $arrayDatos = [":dato" => "$dato"];
                break;
            default:
                $arrayDatos = [":dato" => "%$dato%"];
                break;
        }
        
        $resultado = $sentencia->execute($arrayDatos);
        // if (!$resultado) return [];
        // $tasks = $sentencia->fetchAll(PDO::FETCH_OBJ);
        // return $tasks;
        // lo anterior se puede sustituir sólo por 
        return $resultado?$sentencia->fetchAll(PDO::FETCH_OBJ):[];
    }

    public function delete (int $id):bool
    {
    $sql="DELETE FROM tasks WHERE id =:id";
    try {
        $sentencia = $this->conexion->prepare($sql);
        $resultado= $sentencia->execute([":id" => $id]);
        return ($sentencia->rowCount ()<=0)?false:true;
    }  catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
        return false;
    }
    }

    public function edit(int $idAntiguo, array $arrayTasks): bool
    {
        try {
            $sql = "UPDATE tasks SET name = :name, description=:description, ";
            $sql .= "deadline = :deadline, task_status= :status, user_id=:user_id,client_id=:client_id, project_id = :project_id";
            $sql .= " WHERE id = :id;";
            $arrayDatos = [
                ":id" => $idAntiguo,
                ":name" => $arrayTasks["name"],
                ":description" => $arrayTasks["description"],
                ":deadline" => $arrayTasks["deadline"],
                ":status" => $arrayTasks["task_status"],
                ":user_id" => $arrayTasks["user_id"],
                ":client_id" => $arrayTasks["client_id"],
                ":project_id"=> $arrayTasks["project_id"]
            ];
            $sentencia = $this->conexion->prepare($sql);
            return $sentencia->execute($arrayDatos);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return false;
        }
    }

    public function editStatus(int $idAntiguo, array $arrayTask): bool
    {
        if ($idAntiguo == $arrayTask["id"] && $arrayTask["idEncargado"] == $_SESSION["usuario"]->id){
            try {
                $sql = "UPDATE tasks set task_status = :task_status WHERE id = :id";
                $arrayDatos = [
                    ":id" => $idAntiguo,
                    ":task_status" => $arrayTask["task_status"]
                ];
                $sentencia = $this->conexion->prepare($sql);
                return $sentencia->execute($arrayDatos);
            } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
                return false;
            }
        }
        return false;
    }
}