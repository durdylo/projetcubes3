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

if (!empty($data->text)) {
	if (unit_already_exists($conn, $unit->text)) {
		$result->message = 'Unit already exists';
	}
	else if ($unit->insertUnit($conn)) {
		$result->state = 'success';
		$result->message = 'Unit inserted successfully';
	}
	else {
		$result->message = "Unit not inserted";
	}
}
else {
	$result->message = "Please enter a text for your unit";
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