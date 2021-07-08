<?php
include_once('Views/allViews.php');
include_once('Models/user/User.php');
include_once('Models/recipe/Recipe.php');
include_once('Models/ingredient/Ingredient.php');
include_once('database.php');
class generalControler
{
    private $html;
    private $conn;
    function __construct()
    {
        $db_connection = new Database();
        $this->conn = $db_connection->dbConnection();
        session_start();

        $this->controls();
        echo $this->html;
    }


    private function controls()
    {
        if (isset($_GET['p'])) {
            if ($_GET['p'] == 'cmp') {
                if (isset($_SESSION['userId'])) {

                    $this->setMoncompte($_SESSION['userId']);
                } else {
                    $this->setMoncompte();
                }
            } elseif ($_GET['p'] == 'inscr') {
                $this->setInscription();
            }elseif($_GET['p'] == 'details'){
                $this->setRecipeDetailsHtml($_GET['recetteId']);
            }
        } else {
            $this->setAccueil();
        }
    }
    private function callAPI($method, $url, $data = false)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);


        if ($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }


        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    private function setRecipeDetailsHtml($id) {
        $data = ['id' => $id];
        $res = $this->callAPI('GET',"http://localhost/web/projetCubes3/Models/recipe/selectRecipe.php", $data );
        $view = new recipeView;
        $res = json_decode($res, true);
        $view->setHtmlDetails($res);

    }
    private function setHead()
    {
        $view = new generalView;
        return $view->setHTMLHead();
    }
    private function setFooter()
    {
        $view = new generalView;
        return $view->setHTMLFooter();
    }
    private function setHeader($isConected = false, $userId = false)
    {
        $view = new generalView;
        return $view->setHTMLHeader($isConected , $userId);
    }
    private function setAccueil()
    {

        $obj = new Recipe([]);
        $view = new accueilView;
        $recipes = $obj->selectRecipes($this->conn);
        if(isset($_SESSION['userId'])){

            $isConected = true;
            $userId = $_SESSION['userId'];
        }else{
            $isConected = false;
            $userId = false;
        }
        var_dump($_SESSION['userId']);
        $this->html =  $this->setHead() . $this->setHeader($isConected, $userId) . $view->setHTMLAccueil($recipes) . $this->setFooter();
    }

    private function setMoncompte($userId = false)
    {
        $view = new monCompteView;
        if (isset($_POST['email'])) {
            if (!isset($_POST['id_role'])) {
                $_POST['id_role'] = 2;
            }
            $usertmp = new User($_POST);
            $usertmp->selectUser($this->conn);
            if (isset($usertmp)) {
                var_dump($usertmp);
                $_SESSION['userId'] = $usertmp->id;
                header('location: index.php');
            }
        }
        if ($userId == false) {

            $this->html = $this->setHead() . $this->setHeader() . $view->setHTMLConnexion() . $this->setFooter(); 
        } else {
            $user = new User(['id' => $_SESSION['userId']]);
            $user->selectUserById($this->conn);
            $ingredientObj = new Ingredient([]);
            $ingredients = $ingredientObj->selectAllIngredients($this->conn);
            $recipes = new Recipe(['id_user' => $user->id]);
            $recipesUser = $recipes->selectUserRecipes($this->conn);
            $this->html = $this->setHead() . $this->setHeader() . $view->setHTLMonCompte($user, $recipesUser, false). $this->setFooter(); 
        }
    }
    private function setInscription($userId = false)
    {
        $view = new monCompteView;
        if (isset($_POST['email'])) {
            if (!isset($_POST['id_role'])) {
                $_POST['id_role'] = 2;
            }
            $usertmp = new User($_POST);
            $usertmp->insertUser($this->conn);
            header('location: index.php');
        }
        if ($userId) {
            $this->setMoncompte($userId);
        } else {
            $this->html = $this->setHead() . $this->setHeader() . $view->setHTMLInscription() . $this->setFooter();
        }
    }
}