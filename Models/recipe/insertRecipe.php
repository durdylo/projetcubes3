<?php
require_once('Recipe.php');
require_once('../ingredient/Ingredient.php');
require_once('../step/Step.php');
require_once('../Response.php');

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
$new_recipe = new Recipe($data);

$result = new Response;
$result->state = 'error';

// CHECK DATA VALUE IS EMPTY OR NOT
if (!empty($data->name) && !empty($data->description) && !empty($data->id_user) && isset($data->id_category) && isset($data->ingredients) && isset($data->steps)) {

    if ($new_recipe->insertRecipe($conn)) {

        $idRecipe = $conn->lastInsertId();
		$failed = false;
        if (insertRecipeIngredients($data, $conn, $idRecipe, $result) && insertRecipeSteps($data, $conn, $idRecipe, $result))
		{
			$result->state = 'success';
			$result->message = 'Data Inserted Successfully';
        }
    } else {
        $result->message = 'Data not Inserted';
    }
} else {
    $result->message = 'Oops! empty field detected. Please fill all the fields';
}

//ECHO DATA IN JSON FORMAT
echo json_encode($result);

function insertRecipeIngredients($data, $conn, $idRecipe, $result) {
	foreach ($data->ingredients as $ingredient) {
		if (!Ingredient::insertIngredientsInRecipe($conn, $idRecipe, $ingredient->id, $ingredient->quantity, $ingredient->id_unit)){
			$result->message = 'Data not inserted';
			return (false);
		}
	}
	return (true);
}

function insertRecipeSteps($data, $conn, $idRecipe, $result) {
	foreach ($data->steps as $step) {
		if (!Step::insertStepsInRecipe($conn, $idRecipe, $step->step_order, $step->text)){
			$result->message = 'Data not inserted';
			return (false);
		}
	}
	return (true);	
}