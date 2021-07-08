<?php

class recipeView
{
    function __construct()
    {
    }

    public function setHtmlDetails($recipe){
        var_dump($recipe);
        $data = $recipe['data'];
        $ingredients = $data['ingredients'];
        $steps = $data['steps'];
        $recipe = "<div><h2></h2></div>";
        return $recipe;
    }
}