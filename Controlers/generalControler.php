<?php
include_once('Views/allViews.php');
include_once('Models/user/User.php');
include_once('Models/recipe/Recipe.php');
include_once('Models/ingredient/Ingredient.php');
include_once('Models/unit/Unit.php');
include_once('Models/category/Category.php');
include_once('Models/FPDF/fpdf.php');
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
                    if(isset($_GET['a'])){
                        if ($_GET['a'] == 'addRecipe') {
                            $this->createRecipe();

                        }elseif($_GET['a'] == 'deleteRecipe'){
                            $this->deleteRecipe($_GET['recetteId']);
                        }elseif($_GET['a'] == 'modifRecipe'){
                            $this->modifRecipe($_GET['recetteId']);
                        }
                    }
                } else {
                    $this->setMoncompte();
                }
            } elseif ($_GET['p'] == 'inscr') {
                $this->setInscription();
            }elseif($_GET['p'] == 'details'){
                if(isset($_GET['a']) && $_GET['a'] == 'pdf'){
                    $this->dwnlpdf($_GET['recetteId']);
                }
                $this->setRecipeDetailsHtml($_GET['recetteId']);

            }
        } else {
            $this->setAccueil();
        }
    }
    private function dwnlpdf($id){
        $data = ['id' => $id];
        $res = $this->callAPI('GET',"http://localhost/web/projetCubes3/Models/recipe/selectRecipe.php", $data);
        $view = new recipeView;
        $res = json_decode($res, true);
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,10,htmlspecialchars_decode($view->setHtmlDetails($res, $id, true)));
        $pdf->Output();
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
        $res = $this->callAPI('GET',"http://localhost/web/projetCubes3/Models/recipe/selectRecipe.php", $data);
        $view = new recipeView;
        $res = json_decode($res, true);
        if(isset($_SESSION['userId'])){

            $isConected = true;
            $userId = $_SESSION['userId'];
        }else{
            $isConected = false;
            $userId = false;
        }
        $this->html =  $this->setHead() . $this->setHeader($isConected, $userId) .  $view->setHtmlDetails($res, $id) . $this->setFooter();
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
    private function createRecipe(){
        if(isset($_POST['name'])){
            $ingredientsPost = [];
            $stepsPost = [];
            foreach ($_POST as $key => $item) {
                $tmp = explode('_', $key);
                if ($tmp[0] === 'ingr') {
                    array_push($ingredientsPost, ['id' => $item, 'quantity' => $_POST['qty_'.$tmp[1]], 'id_unit'=> $_POST['unit_'.$tmp[1]]]);
                } elseif ($tmp[0] === 'step') {
                    array_push($stepsPost, ['step_order' => $tmp[1], 'text' => $item]);
                    
                }
            }
            $arrayOpts = ['name' => $_POST['name'], 'id_category' => $_POST['category'], 'id_user' => $_SESSION['userId'], 'description' => $_POST['description']];
            $arrayOpts['ingredients'] = $ingredientsPost;
            $arrayOpts['steps'] = $stepsPost;
            // var_dump(json_encode($arrayOpts));
            $this->callAPI('POST', 'http://localhost/web/projetCubes3/Models/recipe/insertRecipe.php', $arrayOpts);
        }
        $unitObj = new Unit([]);
        $units = $unitObj->selectAllUnits($this->conn);
        $ingredientsObj = new Ingredient([]);
        $ingredients = $ingredientsObj->selectAllIngredients($this->conn);
        $categoriesObj = new Category([]);
        $categories = $categoriesObj->selectAllCategories($this->conn);
        $view = new recipeView;
        $this->html = $this->setHead() . $this->setHeader() . $view->setHtmlAdd($units, $ingredients, $categories) . $this->setFooter();
    }
    private function deleteRecipe($idRecipe){
        $this->callAPI('POST', 'http://localhost/web/projetCubes3/Models/recipe/deleteRecipe.php', ['id' => $idRecipe]);

    }
    private function modifRecipe($idRecipe){
        if(isset($_POST['name'])){
            $ingredientsPost = [];
            $stepsPost = [];
            foreach ($_POST as $key => $item) {
                $tmp = explode('_', $key);
                if ($tmp[0] === 'ingr') {
                    array_push($ingredientsPost, ['id' => $item, 'quantity' => $_POST['qty_'.$tmp[1]], 'id_unit'=> $_POST['unit_'.$tmp[1]]]);
                } elseif ($tmp[0] === 'step') {
                    array_push($stepsPost, ['step_order' => $tmp[1], 'text' => $item]);
                    
                }
            }
            $arrayOpts = ['name' => $_POST['name'], 'id_category' => $_POST['category'], 'id_user' => $_SESSION['userId'], 'description' => $_POST['description']];
            $arrayOpts['ingredients'] = $ingredientsPost;
            $arrayOpts['steps'] = $stepsPost;
            $arrayOpts['id'] = $idRecipe;
            // var_dump(json_encode($arrayOpts));
            $res = $this->callAPI('GET', 'http://localhost/web/projetCubes3/Models/recipe/updateRecipe.php', $arrayOpts);
        }
        $unitObj = new Unit([]);
        $units = $unitObj->selectAllUnits($this->conn);
        $ingredientsObj = new Ingredient([]);
        $ingredients = $ingredientsObj->selectAllIngredients($this->conn);
        $categoriesObj = new Category([]);
        $categories = $categoriesObj->selectAllCategories($this->conn);
        $data = ['id' => $idRecipe];
        $res = $this->callAPI('GET',"http://localhost/web/projetCubes3/Models/recipe/selectRecipe.php", $data );
        $view = new recipeView;
        $res = json_decode($res, true);
        $this->html = $this->setHead() . $this->setHeader() . $view->setHtmlAdd($units, $ingredients, $categories, true, $res['data'] ) . $this->setFooter();
    }
}