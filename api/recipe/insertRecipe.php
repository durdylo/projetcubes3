<?php
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

//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';

// CHECK IF RECEIVED DATA FROM THE REQUEST
if (isset($data->name) && isset($data->description) && isset($data->idUser)) {

    // CHECK DATA VALUE IS EMPTY OR NOT
    if (!empty($data->name) && !empty($data->description) && !empty($data->idUser)) {

        $insert_query = "INSERT INTO `recette`(name, description, user_id )VALUES(:name,:description,:user_id)";

        $insert_stmt = $conn->prepare($insert_query);
        // DATA BINDING
        $insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($data->name)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':description', htmlspecialchars(strip_tags($data->description)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':user_id', htmlspecialchars(strip_tags($data->idUser)), PDO::PARAM_INT);


        if ($insert_stmt->execute()) {
            $msg['message'] = 'Data Inserted Successfully';
            $msg['state'] = 'success';
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
    $msg['message'] = 'Please fill all the fields | name, email, password,firstname';
}
//ECHO DATA IN JSON FORMAT
echo json_encode($msg);