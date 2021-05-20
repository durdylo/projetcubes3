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
$user = new User($data);

//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';

// CHECK IF RECEIVED DATA FROM THE REQUEST
if (!empty($user->id)) {
	if ($user->selectUserById($conn)) {
		if ($user->deleteUser($conn)) {
			$msg['state'] = 'success';
			$msg['message'] = 'Data Removed Successfully';
		} else {
			$msg['message'] = 'Data not Removed';
			$msg['state'] = 'error';
		}
	} else {
		$msg['message'] = 'Data not Removed';
		$msg['state'] = 'error';
	}
} else {
    $msg['state'] = 'error';
    $msg['message'] = 'Please fill all the fields';
}
//ECHO DATA IN JSON FORMAT
echo json_encode($msg);
?>