<?php
require_once("Category.php");
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
$category = new Category($data);

$result = new Response;
$result->state = 'error';

if (!empty($data->id)) {
	if ($category->selectCategory($conn)) {
		$result->data = array("id" => $category->id, "name" => $category->name);
		$result->state = 'success';
		$result->message = 'success';
	}
	else {
		$result->message = "Couldn't select category";
	}
}
else {
	if (($res = $category->selectAllCategories($conn)) === false) {
		$result->message = "Couldn't select categories";
	}
	else {
		$result->data = $res;
		$result->state = 'success';
		$result->message = 'success';
	}
}
echo json_encode($result);

?>