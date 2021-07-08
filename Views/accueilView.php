<?php

class accueilView
{
    function __construct()
    {
    }

    public function setHTMLAccueil($recipes)
    {

        $main =  "<body>
        <div class='row-menu'>
            <div class='e4_8'><p class='types-plats'>Entrées</p></div>
            <div class='e4_8'><p class='types-plats'>Salades</p></div>
            <div class='e4_8'><p class='types-plats'>Soupes</p></div>
            <div class='e4_8'><p class='types-plats'>Gratins</p></div>
            <div class='e4_8'><p class='types-plats'>Plats</p></div>
            <div class='e4_8'><p class='types-plats'>Desserts</p></div>
        </div>
    
        <p class='e7_5'>Les tendances du moment</p>
    
        <main id='recettes'>";
        foreach ($recipes as $recipe) {
            $main .=  "
            <div>
            <a href='index.php?p=details&recetteId=".$recipe['id']."'><figure><img src='assets/images/93607de4-f877-4c0f-9683-5bc0c65dee55-recettes-plats-au-four-tout-en-un-one-sheet-pan-768x512.png' class='corsica'><figcaption>".$recipe['name']."</figcaption></figure>
            <h3>".utf8_decode($recipe['name_category'])."</h3>
        </a>  
        </div> ";
        }
       
    $main .= "</main> 
    
    
        <script type='text/javascript' src='Script.js'></script>
    </body>";
    return $main;
    }
}