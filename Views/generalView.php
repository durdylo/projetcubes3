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
        <link rel='stylesheet' type='text/css' href='assets/css/compte.css'>
        <link rel='stylesheet' type='text/css' href='assets/css/inscription.css'>
        <link rel='stylesheet' media='screen and (min-device-width: 320px ) and (max-device-width: 1250px )' href='./assets/css/responsive_mobile.css'/>
        <script type='text/javascript' src='assets/js/script.js'></script>


            </head>";
    }

    public function setHTMLFooter()
    {
        return "

        <div class='footer'>


        <p class='v8_23'>Copyrights © 2021 - <a href='#'>Mentions légales</a> - <a href='#'>Politique de
                confidentialité</a> - CGU - Créé par <a href='#'>Webcom</a></p>
        <img class='logo2' src='./assets/images/Rectangle2.png' alt='Logo' width='100' height='100'>

    </div>";
    }

    public function setHTMLHeader($isConected = false, $idUser = false)
    {
        $link = ($isConected !== false && $idUser !== false ? " <a href='index.php?p=cmp&idUser=".$idUser."' class='e8_17'>Mon compte</a>" : " <a href='index.php?p=cmp' class='e8_17'>Se connecter</a>");
        return "    <div class='topbar'>
        <span class='header'>OFFRE ABONNEMENT : ACCÉDEZ À DES MILLIERS DE RECETTES SANS LIMITE !</span>
        <div class='e8_16'>
$link        </div>
    </div>

    <div class='v1'>
        <span class='e4_2'>JLCuisine</span>
        <img class='logo1' src='./assets/images/Rectangle2.png' alt='Logo' width='200' height='200'>
        <span class='e4_5'>Inspirez-vous des plus créatifs !</span>
    </div>";
    }
}