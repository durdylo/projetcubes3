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
require_once('../../database.php');
$db_connection = new Database();
$conn = $db_connection->dbConnection();
// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$recipe = new Recipe($data);

$result = new Response;
$result->state = 'error';

if (!empty($data->id_user)) {
    if (($res =  $recipe->selectUserRecipes($conn)) === false){
		$result->message = 'user recipe select failed';
	}
	else {
		$result->state = 'success';
		$result->message = 'success';
		$result->data = $res;
	}

    // PUSH POST DATA IN OUR $posts_array ARRAY
} elseif (!empty($data->id)) {
    // TODO verif si tout c'est bien passé
   if ($recipe->selectRecipe($conn) === false) {
		$result->message = 'recipe select failed';
   }
   else if  (($ingredientsRes = Ingredient::selectIngredientsFromRecipe($conn, $recipe->id)) === false ||
  		 ($stepsRes = Step::selectStepsFromRecipe($conn, $recipe->id)) === false) {
			$result->message = 'details select failed';
	}
	else {
		$result->state = 'success';
		$result->message = 'success';
		$result->data = array(
			"name" => $recipe->name, "description" => $recipe->description, "category" => $recipe->name_category,
			"ingredients" => $ingredientsRes, "steps" => $stepsRes
		);
	}
}
else {
	if (($res = $recipe->selectRecipes($conn)) === false) {
		$result->message = 'select failed';
	}
	else {
		$result->state = 'success';
		$result->message = 'success';
		$result->data = $res;
	}
}
echo json_encode($result);