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
$data = json_decode(file_get_contents("php://input"));
$recipe = new Recipe($data);

$result = new Response;
$result->state = 'error';

if (!empty($data->id) && isset($data->ingredients) && isset($data->steps)) {
	if ($recipe->selectRecipe($conn) === false) {
		$result->message = "This recipe doesn't exist";
	}
	else {
		$recipe->set($data);
		if ($recipe->updateRecipe($conn) && update_recipe_details($conn, $data, $recipe)) {
			$result->state = 'success';
			$result->message = 'Data Updated Successfully';

		} else {
			$result->message = 'Data not Updated';
		}
	}
} else {
    $result->message = 'Please fill all the fields';
}
echo json_encode($result);

function	update_recipe_details($conn, $data, $recipe) {
	$ingredients = $data->ingredients;
	$ingredients_not_to_delete = array();
	$steps = $data->steps;
	$steps_not_to_delete = array();

	foreach ($ingredients as $ingredient) {
		if (Ingredient::updateIngredientInRecipe($conn, $recipe, $ingredient) === false)
			return (false);
		array_push($ingredients_not_to_delete, $ingredient->id);
	}
	if (Ingredient::deleteIngredientInRecipe($conn, $recipe, $ingredients_not_to_delete) === false)
		return(false);

	foreach ($steps as $step) {
		if (Step::updateStepInRecipe($conn, $recipe, $step) === false)
			return (false);
		array_push($steps_not_to_delete, $step->step_order);
	}
	if (Step::deleteStepsInRecipe($conn, $recipe, $steps_not_to_delete) === false)
		return (false);
	return (true);
}