<?php
require_once('Recipe.php');
require_once('../ingredient/Ingredient.php');
require_once('../step/Step.php');
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
//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';
if (!empty($recipe->id)) {
	if ($recipe->deleteRecipe($conn)) {
		$msg['state'] = 'success';
		$msg['message'] = 'Data Removed Successfully';
	} else {
		$msg['message'] = 'Data not Removed';
		$msg['state'] = 'error';
	}
} else {
    $msg['state'] = 'error';
    $msg['message'] = 'Please fill all the fields';
}
echo json_encode($msg);