<?php
require_once('Recipe.php');
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
if (isset($data->name) && isset($data->description) && isset($data->id_user) && isset($data->ingredients)) {
    // CHECK DATA VALUE IS EMPTY OR NOT
    if (!empty($data->name) && !empty($data->description) && !empty($data->id_user) && !empty($data->ingredients)) {

        if ($new_recipe->insertRecipe($conn)) {

            $idRecipe = $conn->lastInsertId();

            foreach ($data->ingredients as $ingredient) {
                $insert_query = "INSERT INTO `ingredient_recette`(id_recette, id_ingredient, quantity )VALUES(:id_recette, :id_ingredient, :quantity)";
                $insert_ingredient = $conn->prepare($insert_query);
                $insert_ingredient->bindValue(':id_recette', htmlspecialchars(strip_tags($idRecipe)),  PDO::PARAM_INT);
                $insert_ingredient->bindValue(':id_ingredient', htmlspecialchars(strip_tags($ingredient->id)), PDO::PARAM_INT);
                $insert_ingredient->bindValue(':quantity', htmlspecialchars(strip_tags($ingredient->quantity)), PDO::PARAM_INT);
                $insert_ingredient->execute();
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