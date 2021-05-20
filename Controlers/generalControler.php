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
                $this->setMoncompte();
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

    private function setAccueil()
    {

        $view = new accueilView;

        $this->html =  $this->setHead() . $view->setHTMLAccueil() . $this->setFooter();
    }

    private function setMoncompte()
    {
        $this->html =  'Mon compte';
    }
}