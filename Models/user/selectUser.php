<?php
require_once('User.php');
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
require '../../database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$new_user = new User($data);

//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';
if (strlen($data->email) > 0 && strlen($data->password) > 0) {
    if (!$new_user->selectUser($conn)) {
        $msg['state'] = 'error';
        $msg['message'] = 'Invalid credentials';
    } else {
        $msg['state'] = 'success';
        $msg['message'] = 'Login successful';
        echo json_encode(array("id" => $new_user->id, "name" => $new_user->name, "firstname" => $new_user->firstname, "email" => $new_user->email));
    }
    // PUSH POST DATA IN OUR $posts_array ARRAY
} else {
    $msg['state'] = 'error';
    $msg['message'] = "Email and password can't be empty";
}
echo json_encode($msg);
// gestion error