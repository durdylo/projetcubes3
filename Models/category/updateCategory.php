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
require_once('../../database.php');
$db_connection = new Database();
$conn = $db_connection->dbConnection();
// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$category = new Category($data);

$result = new Response;
$result->state = 'error';

if (empty($data->id)) {
	$result->message = "Please enter an id to be updated";
}
else if (empty($data->name)) {
	$result->message = "Please enter a new name for your category";
}
else {
	if (category_already_exists($conn, $category->name)) {
		$result->message = 'Category already exists';
	}
	else if ($category->updateCategory($conn)) {
		$result->state = 'success';
		$result->message = 'Category successfully updated';
	}
	else {
		$result->message = "Category not updated";
	}
}
echo json_encode($result);


function    category_already_exists($conn, $name) {
	$select_query = "SELECT * FROM `category` WHERE name=:name";
	$stmt = $conn->prepare($select_query);
	$stmt->bindValue(':name', htmlspecialchars(strip_tags($name)), PDO::PARAM_STR);
	$stmt->execute();
	$res =  $stmt->fetch(PDO::FETCH_ASSOC);
	return ($res > 0);
}

?>