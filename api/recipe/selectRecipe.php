<?php
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
$data = json_decode(file_get_contents("php://input"));

//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';
if (isset($_GET['userId'])) {

    $userId = $_GET['userId'];
    $select_query = "SELECT * FROM `recette` WHERE user_id='$userId'";

    $stmt = $conn->prepare($select_query);
    $stmt->execute();
    $res =  $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($res);

    // PUSH POST DATA IN OUR $posts_array ARRAY
} elseif (isset($_GET['recipeId'])) {

    $recipeId = $_GET['recipeId'];

    $select_ingredients = "SELECT * from ingredient INNER JOIN ingredient_recette on ingredient.id = ingredient_recette.id_ingredient where ingredient_recette.id_recette = $recipeId";

    $select_recette = "SELECT * from recette where id = $recipeId";

    $ingredients = $conn->prepare($select_ingredients);
    $recette = $conn->prepare($select_recette);

    $ingredients->execute();
    $recette->execute();

    $ingredientsRes =  $ingredients->fetchAll(PDO::FETCH_ASSOC);
    $recetteRes =  $recette->fetch(PDO::FETCH_ASSOC);

    echo json_encode($ingredientsRes);
    echo json_encode($recetteRes);
}