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
if (isset($data->name) && isset($data->email) && isset($data->password) && isset($data->firstname) && isset($data->confirmpassword) && isset($data->role)) {
    if ($data->password != $data->confirmpassword) {
        $msg['message'] = 'les mots de passe ne correspondent pas';
    } else if (!is_password_ok($data->password)) {
        $msg['state'] = 'error';
        $msg['message'] = 'Invalid password. Please enter a password containing at least one uppercase letter, one lowercase letter and a number and 8 characters.';
    } else if (email_already_exists($conn, $data->email)) {
        $msg['state'] = 'error';
        $msg['message'] = 'Account already exists';
    } else {
        // CHECK DATA VALUE IS EMPTY OR NOT
        if (!empty($data->name) && !empty($data->email) && !empty($data->password) && !empty($data->firstname) && !empty($data->role)) {

            $encodedpass = hash('sha256', $data->password);
            $insert_query = "INSERT INTO `user`(name,email,password, firstname,role) VALUES(:name,:email,:password,:firstname,:role)";

            $insert_stmt = $conn->prepare($insert_query);
            // DATA BINDING
            $insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($data->name)), PDO::PARAM_STR);
            $insert_stmt->bindValue(':firstname', htmlspecialchars(strip_tags($data->firstname)), PDO::PARAM_STR);
            $insert_stmt->bindValue(':email', htmlspecialchars(strip_tags($data->email)), PDO::PARAM_STR);
            $insert_stmt->bindValue(':password', htmlspecialchars(strip_tags($encodedpass)), PDO::PARAM_STR);
            $insert_stmt->bindValue(':role', htmlspecialchars(strip_tags($data->role)), PDO::PARAM_STR);

            if ($insert_stmt->execute()) {
                $msg['state'] = 'success';
                $msg['message'] = 'Data Inserted Successfully';
            } else {
                $msg['message'] = 'Data not Inserted';
                $msg['state'] = 'error';
            }
        } else {
            $msg['state'] = 'error';
            $msg['message'] = 'Oops! empty field detected. Please fill all the fields';
        }
    }
} else {
    $msg['state'] = 'error';
    $msg['message'] = 'Please fill all the fields | name, email, password,firstname';
}
//ECHO DATA IN JSON FORMAT
echo json_encode($msg);

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
?>