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

if (!empty($data->name)) {
	if (category_already_exists($conn, $category->name)) {
		$result->message = 'Category already exists';
	}
	else if ($category->insertCategory($conn)) {
		$result->state = 'success';
		$result->message = 'Category inserted successfully';
	}
	else {
		$result->message = "Category not inserted";
	}
}
else {
	$result->message = "Please enter a name for your category";
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