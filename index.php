<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Allowed methods
header("Access-Control-Allow-Credentials: true"); // Allow credentials (if needed)

include 'DbConnect.php';

class UserAPI{

    private $conn;

    public function __construct(){
        //this contruckts connection to database
        $objDb = new DbConnect;
        $this->conn = $objDb->connect();

    }

    public function ProcessRequest(){

        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                $this->getUsers();
                break;
            case 'POST':
                $this->createUser();
                break;
            case 'PUT':
                $this->updateUser();
                break;
                case'DELETE':
                    $this->deleteUser();
                    break;
            default:
            echo "No method chosen";
                break;
        }
    }

    private function getUsers(){

        $sql = "SELECT * FROM users";
        //explode index´s each string after / and index nr 3 is id from useparam(react)
        $path = explode('/', $_SERVER["REQUEST_URI"]);
        
        if (isset($path[3]) && is_numeric([3])) {
            $sql .= "WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $path[3]);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }; 
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    }

    private function createUser(){
        $user = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO users(id, name , email, mobile, created_at) VALUES(null, :name, :email, :mobile, :created_at)";
        $stmt = $this->conn->prepare($sql);
        $created_at = date('Y-m-d');
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':mobile', $user->mobile);
        $stmt->bindParam(':created_at', $created_at);
        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'record created succsesfully'];
        } else {
            $response = ['status' => 0, 'message' => 'failed to created record'];
        }
        echo json_encode($response); 
    }

    public function updateUser(){
        $user = json_decode(file_get_contents('php://input'));
        $sql = "UPDATE users SET name =:name, email =:email, mobile =:mobile, updated_at =:updated_at WHERE id =:id";
        $stmt = $this->conn->prepare($sql);
        $updated_at = date('Y-m-d');
        $stmt->bindParam(':id', $user->id);
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':mobile', $user->mobile);
        $stmt->bindParam(':updated_at', $updated_at);
        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'record updated succsesfully'];
        } else {
            $response = ['status' => 0, 'message' => 'failed to update record'];
        }
        echo json_encode($response);
    }

    public function deleteUser(){
        $sql = "DELETE FROM users WHERE id = :id";
        $path = explode('/', $_SERVER["REQUEST_URI"]);

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $path[3]);
        $stmt->execute();

        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'record updated succsesfully'];
        } else {
            $response = ['status' => 0, 'message' => 'failed to update record'];
        }
        echo json_encode($response);

    }

}

// $method = $_SERVER['REQUEST_METHOD'];
// switch ($method) {
//     case "GET":
//         $sql = "SELECT * FROM users";
//         $path = explode('/', $_SERVER["REQUEST_URI"]);
//         if (isset($path[3]) && is_numeric([3])) {
//             $sql .= "WHERE id = :id";
//             $stmt = $conn->prepare($sql);
//             $stmt->bindParam(':id', $path[3]);
//             $stmt->execute();
//             $users = $stmt->fetch(PDO::FETCH_ASSOC);
//         };
//         $stmt = $conn->prepare($sql);
//         $stmt->execute();
//         $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         echo json_encode($users);
//         break;

//     case "POST":
//         $user = json_decode(file_get_contents('php://input'));
//         $sql = "INSERT INTO users(id, name , email, mobile, created_at) VALUES(null, :name, :email, :mobile, :created_at)";
//         $stmt = $conn->prepare($sql);
//         $created_at = date('Y-m-d');
//         $stmt->bindParam(':name', $user->name);
//         $stmt->bindParam(':email', $user->email);
//         $stmt->bindParam(':mobile', $user->mobile);
//         $stmt->bindParam(':created_at', $created_at);
//         if ($stmt->execute()) {
//             $response = ['status' => 1, 'message' => 'record created succsesfully'];
//         } else {
//             $response = ['status' => 0, 'message' => 'failed to created record'];
//         }
//         echo json_encode($response);
//         break;
//     case "PUT":
//         $user = json_decode(file_get_contents('php://input'));
//         $sql = "UPDATE users SET name =:name, email =:email, mobile =:mobile, updated_at =:updated_at WHERE id =:id";
//         $stmt = $conn->prepare($sql);
//         $updated_at = date('Y-m-d');
//         $stmt->bindParam(':id', $user->id);
//         $stmt->bindParam(':name', $user->name);
//         $stmt->bindParam(':email', $user->email);
//         $stmt->bindParam(':mobile', $user->mobile);
//         $stmt->bindParam(':updated_at', $updated_at);
//         if ($stmt->execute()) {
//             $response = ['status' => 1, 'message' => 'record updated succsesfully'];
//         } else {
//             $response = ['status' => 0, 'message' => 'failed to update record'];
//         }
//         echo json_encode($response);
//         break;
//     case "DELETE":
//         $sql = "DELETE FROM users WHERE id = :id";
//         $path = explode('/', $_SERVER["REQUEST_URI"]);

//         $stmt = $conn->prepare($sql);
//         $stmt->bindParam(':id', $path[3]);
//         $stmt->execute();

//         if ($stmt->execute()) {
//             $response = ['status' => 1, 'message' => 'record updated succsesfully'];
//         } else {
//             $response = ['status' => 0, 'message' => 'failed to update record'];
//         }
//         echo json_encode($response);

//         break;
// };

$api = new userApi();
$api = $api->ProcessRequest();

