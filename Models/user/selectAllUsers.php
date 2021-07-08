<?php
require_once('User.php');
require_once('../Response.php');
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

$result = new Response;
//CREATE MESSAGE ARRAY AND SET EMPTY
$result->state = 'error';

if (($res = User::selectAllUsers($conn)) === false) {
	$result->message = 'failed';
}
else {
	$result->state = 'success';
	$result->message = 'success';
	$result->data = $res;
}

echo json_encode($result);
// gestion error
