<?php
require_once("Unit.php");
require_once("../Response.php");

// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
require '../../database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();
// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$unit = new Unit($data);

$result = new Response;
$result->state = 'error';

if (!empty($data->id)) {
	if ($unit->deleteUnit($conn)) {
		$result->state = 'success';
		$result->message = 'Unit removed successfully';
	}
	else {
		$result->message = "Unit not removed";
	}
}
else {
	$result->message = "Please enter an id to delete";
}
echo json_encode($result);

?>