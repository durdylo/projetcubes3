<?php
require_once('Recipe.php');
require_once('../ingredient/Ingredient.php');
require_once('../step/Step.php');
require_once('../Response.php');
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
$data = json_decode(file_get_contents("php://input"), true);
$recipe = new Recipe($data);

$result = new Response;
$result->state = 'error';

if (!empty($data->id)) {
	if ($recipe->deleteRecipe($conn)) {
		$result->state = 'success';
		$result->message = 'Data Removed Successfully';
	} else {
		$result->message = 'Data not Removed';
	}
} else {
    $result->message = 'Please fill all the fields';
}
echo json_encode($result);