<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Allowed methods
header("Access-Control-Allow-Credentials: true"); // Allow credentials (if needed)

include 'DbConnect.php';

class LoginAPI {

    private $conn;

    public function __construct(){
        //this constructs connection to database
        $objDb = new DbConnect;
        $this->conn = $objDb->connect();
    }
    
    public function loginUser() {
        $user = json_decode(file_get_contents('php://input'));
    
        if (!$user || empty($user->username) || empty($user->password)) {
            echo json_encode(['status' => 0, 'message' => 'Username and password required']);
            return;
        }
    
        $sql = "SELECT username, password FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $user->username);
        $stmt->execute();
        $storedUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($storedUser && password_verify($user->password, $storedUser['password'])) {
            echo json_encode(['status' => 1, 'message' => 'Login successful', 'user' => ['username' => $storedUser['username']]]);
        } else {
            echo json_encode(['status' => 0, 'message' => 'Invalid username or password']);
        }
    }
    
};