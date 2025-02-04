<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

echo"The PHP Backend";

include 'DbConnect.php';
$objDb = new DbConnect;
$conn = $objDb->connect(); //why does this way of syntax

$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
case "POST":
    $user = json_decode( file_get_contents('php://input'));
    $sql = "INSERT INTO users(id, name , email, mobile, created_at) VALUES(null, :name, :email, :mobile, :created_at)";
   $stmt = $conn->prepare($sql);
   $created_at = date('Y-m-d');
   $stmt->bindParam(':name', $user->name);
   $stmt->bindParam(':email', $user->email);
   $stmt->bindParam(':mobile', $user->mobile);
   $stmt->bindParam(':created_at', $created_at);
   if($stmt->execute()){
    $response = ['status' => 1, 'message' => 'record created succsesfully'];
   } else{
    $response = ['status' => 0, 'message' => 'failed to created record'];

   }
   echo json_encode($response);
   break;
}


