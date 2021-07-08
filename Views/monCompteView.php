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
            <input class='connexion-button-input' type='password' name='password' placeholder='Mot de Passe'>
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

    public function setHTLMonCompte($user,  $recipes)
    {
        var_dump($user);

        $html = "<h2 class='body-title'>Mon Compte</h2>";
        $html .= "<nav><a href='index.php?p=cmp&a=addRecipe'>Créer une recette</a></nav>";
        foreach ($recipes as $recipe) {
            $html .=  "
            <div>
            <a href='index.php?p=cmp&a=deleteRecipe&recetteId=".$recipe['id']."'>Suprimer</a>
            <a href='index.php?p=cmp&a=modifRecipe&recetteId=".$recipe['id']."'>Modif</a>
            <a href='index.php?p=cmp&modif=1&recetteId=".$recipe['id']."'><figure><img src='assets/images/93607de4-f877-4c0f-9683-5bc0c65dee55-recettes-plats-au-four-tout-en-un-one-sheet-pan-768x512.png' class='corsica'><figcaption>".$recipe['name']."</figcaption></figure>
            <h3>".$recipe['name_category']."</h3>
        </a>  
        </div> ";
        }
        return $html;

       
    }

    public function setCreateRecipe($ingredients){
        // $ingredientsHTML = '';
        // foreach ($ingredients as $ingredient) {
        //     $ingredientsHTML .= "<option value='".$ingredient['name']."'>".$ingredient['name']."</option>";
        // }
        // return "   <div class='body-block ajout_ingredients'>
        // <h2 class=''></h2>
        
        // <select class='selectIngredients'>
        // $ingredientsHTML
        //     </select>
        // </div>";

    }
}
