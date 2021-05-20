<?php
include_once('Views/allViews.php');
class generalControler
{
    private $html;
    function __construct()
    {
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
}