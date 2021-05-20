<?php
class monCompteView
{
    function __construct()
    {
    }

    public function setHTMLConnexion()
    {
        return " <div class='body-block'>
        <h2 class='connexion-title'>Connexion</h2>
        <div class='connexion-body'>
            <input class='connexion-button-input' type='text' placeholder='Adresse Mail'>
            <input class='connexion-button-input' type='text' placeholder='Mot de Passe'>
            <button class='connexion-button'>Se connecter</button>
            <a class='creation-link' href=''>Première visite ? Créez un compte !</a>
        </div>

    </div>";
    }
}