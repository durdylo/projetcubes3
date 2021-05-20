<?php

class accueilView
{
    function __construct()
    {
    }

    public function setHTMLAccueil()
    {
        return "<body>
        <div class='row-menu'>
            <div class='e4_8'><p class='types-plats'>Entrées</p></div>
            <div class='e4_8'><p class='types-plats'>Salades</p></div>
            <div class='e4_8'><p class='types-plats'>Soupes</p></div>
            <div class='e4_8'><p class='types-plats'>Gratins</p></div>
            <div class='e4_8'><p class='types-plats'>Plats</p></div>
            <div class='e4_8'><p class='types-plats'>Desserts</p></div>
        </div>
    
        <p class='e7_5'>Les tendances du moment</p>
    
            <div class='top-recettes'>
                <div class='avocat'></div>
                <div class='gratin'></div>
                <div class='poelee'></div>
            </div>
        
    
            <div class='bottom-recettes'>
                <div class='corsica'></div>
                <div class='lasagnes'></div>
                <div class='tagliatelles'></div>
            </div>      
    
    
        <script type='text/javascript' src='Script.js'></script>
    </body>";
    }
}