<?php

class generalView
{
    function __construct()
    {
    }

    public function setHTMLHead()
    {
        return "<head>
        <meta charset='UTF-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Accueil JL Cuisine</title>
        <link rel='stylesheet' type='text/css' href='assets/css/accueil.css'>
            </head>";
    }

    public function setHTMLFooter()
    {
        return "    <div class='e8_1'>
        <img class='logo2' src='Rectangle2.png' alt='Logo' width='200' height='200'>
        <p  class='e8_26'>JLCuisine</p>
        <p class='v8_23'>Copyrights © 2021 - Mentions légales - Politique de confidentialité - CGU - Créé par Webcom</p>
    </div>";
    }
}