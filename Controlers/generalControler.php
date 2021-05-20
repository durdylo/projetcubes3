<?php
include_once('Views/allViews.php');
include_once('Models/user/User.php');
include_once('database.php');
class generalControler
{
    private $html;
    private $conn;
    function __construct()
    {
        $db_connection = new Database();
        $this->conn = $db_connection->dbConnection();

        $this->controls();
        echo $this->html;
    }


    private function controls()
    {
        if (isset($_GET['p'])) {
            if ($_GET['p'] == 'cmp') {
                if (isset($_SESSION['id'])) {
                    $this->setMoncompte($_SESSION['id']);
                } else {
                    $this->setMoncompte();
                }
            } elseif ($_GET['p'] == 'inscr') {
                $this->setInscription();
            }
        } else {
            $this->setAccueil();
        }
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
    private function setHeader()
    {
        $view = new generalView;
        return $view->setHTMLHeader();
    }
    private function setAccueil()
    {

        $view = new accueilView;

        $this->html =  $this->setHead() . $this->setHeader() . $view->setHTMLAccueil() . $this->setFooter();
    }

    private function setMoncompte($userId = false)
    {
        $view = new monCompteView;
        if (!$userId) {

            $this->html = $this->setHead() . $this->setHeader() . $view->setHTMLConnexion() . $this->setFooter();
        } else {
            $this->html =  'Mon compte';
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