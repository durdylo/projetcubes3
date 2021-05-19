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
if (isset($data->email) && isset($data->password) && strlen($data->email) > 0 && strlen($data->password) > 0) {
    $emailUser = $data->email;
    $encodedpass = hash('sha256', $data->password);
    $select_query = "SELECT * FROM `user` WHERE email='$emailUser' AND password='$encodedpass';";

    $stmt = $conn->prepare($select_query);
    $stmt->execute();
    $res =  $stmt->fetch(PDO::FETCH_ASSOC);
    if ($res == 0) {
        $msg['state'] = 'error';
        $msg['message'] = 'Invalid credentials';
    }
    else {
        $msg['state'] = 'success';
        $msg['message'] = 'Login successful';
        echo json_encode($res);
    }
    // PUSH POST DATA IN OUR $posts_array ARRAY
}
else {
    $msg['state'] = 'error';
    $msg['message'] = "Email and password can't be empty";
}
echo json_encode($msg);
// gestion error