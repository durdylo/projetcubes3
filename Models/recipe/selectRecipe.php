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
//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';
if (isset($_GET['id_user'])) {
    $recipe = new Recipe(null);
    $recipe->id_user = $_GET['id_user'];
    $res =  $recipe->selectUserRecipes($conn);

    echo json_encode($res);

    // PUSH POST DATA IN OUR $posts_array ARRAY
} elseif (isset($_GET['id_recipe'])) {

    $recipe = new Recipe(null);
    $recipe->id = $_GET['id_recipe'];
    // TODO verif si tout c'est bien passé
    $recipe->selectRecipe($conn);
    $ingredientsRes =  Ingredient::selectIngredientsFromRecipe($conn, $recipe->id);
    $stepsRes = Step::selectStepsFromRecipe($conn, $recipe->id);

    echo json_encode(array(
        "name" => $recipe->name, "description" => $recipe->description, "category" => $recipe->name_category,
        "ingredients" => $ingredientsRes, "steps" => $stepsRes
    ));
}