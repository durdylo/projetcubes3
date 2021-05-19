<?php
require_once('Recipe.php');
require_once('Ingredient.php');
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
require '../database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"), true);

//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';
if (isset($_GET['userId'])) {

    $recipe = new Recipe();
    $recipe->id_user = $_GET['userId'];
    $res =  $recipe->selectUserRecipes($conn);

    echo json_encode($res);

    // PUSH POST DATA IN OUR $posts_array ARRAY
} elseif (isset($_GET['recipeId'])) {

    $recipe = new Recipe();
    $recipe->id = $_GET['recipeId'];
    // TODO verif si tout c'est bien passé
    $recipe->selectRecipe($conn);

    $select_ingredients = "SELECT * from ingredient INNER JOIN ingredient_recette on ingredient.id = ingredient_recette.id_ingredient where ingredient_recette.id_recette = $recipe->id";
    $ingredientsRes =  Ingredient::selectIngredientFromRecipe($conn, $recipe->id);

    echo json_encode($ingredientsRes);
    echo json_encode(array("name" => $recipe->name, "description" => $recipe->description));
}