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
require_once('../../database.php');
$db_connection = new Database();
$conn = $db_connection->dbConnection();
// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$unit = new Unit($data);

$result = new Response;
$result->state = 'error';

if (empty($data->id)) {
	$result->message = "Please enter an id to be updated";
}
else if (empty($data->text)) {
	$result->message = "Please enter a new text for your unit";
}
else {
	if (unit_already_exists($conn, $unit->text)) {
		$result->message = 'Unit already exists';
	}
	else if ($unit->updateUnit($conn)) {
		$result->state = 'success';
		$result->message = 'Unit successfully updated';
	}
	else {
		$result->message = "Unit not updated";
	}
}
echo json_encode($result);

function    unit_already_exists($conn, $text) {
	$select_query = "SELECT * FROM `unit` WHERE text=:text";
	$stmt = $conn->prepare($select_query);
	$stmt->bindValue(':text', htmlspecialchars(strip_tags($text)), PDO::PARAM_STR);
	$stmt->execute();
	$res =  $stmt->fetch(PDO::FETCH_ASSOC);
	return ($res > 0);
}
?>