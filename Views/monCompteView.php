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
        <form method='post'>
            <input class='connexion-button-input' name='email' type='text' placeholder='Adresse Mail'>
            <input class='connexion-button-input' type='password' name='mdp' placeholder='Mot de Passe'>
            <button class='connexion-button'>Se connecter</button>
            <a class='creation-link' href='index.php?p=inscr'>Première visite ? Créez un compte !</a>
            </form>
        </div>

    </div>";
    }

    public function setHTMLInscription()
    {
        return "   <div class='body-block'>
        <h2 class='body-title'>Création de compte</h2>
        <form method='post'>

        <input class='input-text' type='text' name='email' placeholder='Mail'>
        <input class='input-text' type='password' name='password' placeholder='Mot de Passe'>
        <input class='input-text' type='text' name='name' placeholder='Nom'>
        <input class='input-text' type='text' name='firstname' placeholder='Prénom'>
        <button type='submit' class='inscription-link'>S'inscrire</button>
        </form>

        <a class='connexion-link' href='index.php?p=cmp'>Déjà membre ? Connectez-vous</a>
    </div>";
    }
}