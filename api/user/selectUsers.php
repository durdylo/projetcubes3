<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
require '../database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));

//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';
if (isset($data->email) && isset($data->password)) {
    $emailUser = $data->email;
    $select_query = "SELECT * FROM `user` WHERE email='$emailUser'";

    $stmt = $conn->prepare($select_query);
    $stmt->execute();
    $res =  $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($res);

    // PUSH POST DATA IN OUR $posts_array ARRAY
}
// gestion error