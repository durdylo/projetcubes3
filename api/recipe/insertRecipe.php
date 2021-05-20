<?php
require_once('Recipe.php');
require_once('../ingredient/Ingredient.php');
require_once('../step/Step.php');
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
require '../database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$new_recipe = new Recipe($data);
//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';

// CHECK IF RECEIVED DATA FROM THE REQUEST
if (isset($data->name) && isset($data->description) && isset($data->id_user) && isset($data->id_category) && isset($data->ingredients) && isset($data->steps)) {
    // CHECK DATA VALUE IS EMPTY OR NOT
    if (!empty($data->name) && !empty($data->description) && !empty($data->id_user) && isset($data->id_category) && !empty($data->ingredients) && !empty($data->steps)) {

        if ($new_recipe->insertRecipe($conn)) {

            $idRecipe = $conn->lastInsertId();

            foreach ($data->ingredients as $ingredient) {
                //TODO check if succeeded
                Ingredient::insertIngredientsInRecipe($conn, $idRecipe, $ingredient->id, $ingredient->quantity, $ingredient->id_unit);
            }
            foreach ($data->steps as $step) {
                //TODO check if succeeded
                Step::insertStepsInRecipe($conn, $idRecipe, $step->step_order, $step->text);
            }
        } else {
            $msg['message'] = 'Data not Inserted';
            $msg['state'] = 'error';
        }
    } else {
        $msg['state'] = 'error';
        $msg['message'] = 'Oops! empty field detected. Please fill all the fields';
    }
} else {
    $msg['state'] = 'error';
    $msg['message'] = 'Please fill all the fields ';
}
//ECHO DATA IN JSON FORMAT
echo json_encode($msg);