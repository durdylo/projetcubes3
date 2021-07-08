<?php
require_once('User.php');
require_once('../Response.php');
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
require_once('../../database.php');
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$new_user = new User($data);

//CREATE MESSAGE ARRAY AND SET EMPTY
$result = new Response;
$result->state = 'error';

// CHECK IF RECEIVED DATA FROM THE REQUEST
// CHECK DATA VALUE IS EMPTY OR NOT
if (!empty($data->name) && !empty($data->email) && !empty($data->password) && !empty($data->firstname)&& !empty($data->confirmpassword)) {
    if (strcmp($data->password, $data->confirmpassword) != 0) {
        $result->message = 'les mots de passe ne correspondent pas';
    } else if (!is_password_ok($new_user->password)) {
        $result->message = 'Invalid password. Please enter a password containing at least one uppercase letter, one lowercase letter and a number and 8 characters.';
    } else if (email_already_exists($conn, $new_user->email)) {
        $result->message = 'Account already exists';
    }
    else if ($new_user->insertUser($conn)) {
		$result->state = 'success';
        $result->message = 'Data Inserted Successfully';
    } else {
       $result->message = 'Data not Inserted';
    }
} else {
   $result->message = 'Oops! empty field detected. Please fill all the fields';
}

//ECHO DATA IN JSON FORMAT
echo json_encode($result);

    function    is_password_ok($password) {
		return (strlen($password) >= 8 && preg_match("~[0-9]+~", $password) && preg_match("~[a-z]+~", $password) && preg_match("~[A-Z]+~", $password));
    }

    function    email_already_exists($conn, $email) {
        $select_query = "SELECT * FROM `user` WHERE email='$email'";
        $stmt = $conn->prepare($select_query);
        $stmt->execute();
        $res =  $stmt->fetch(PDO::FETCH_ASSOC);
        return ($res > 0);
    }