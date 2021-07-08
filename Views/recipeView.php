<?php

class recipeView
{
    function __construct()
    {
    }

    public function setHtmlDetails($recipe){
        $data = $recipe['data'];
        $ingredients = $data['ingredients'];
        $steps = $data['steps'];
        // var_dump($data);
        // var_dump($steps);
        // var_dump($ingredients);
        $recipe = "<div class='row-items'>

        <figure>
            <img src='i60852-top-15-des-recettes-healthy-du-moment1.png' class='imgDetail'>
            <figcaption>".$data['name']."</figcaption>
        </figure>

        <div class='column-items'>

        <h3>Ingrédients</h3>
        <ul>";
        foreach ($ingredients as $ingredient) {
            $unit = (utf8_decode($ingredient['text']) === 'unités' ? '' : utf8_decode($ingredient['text']));
            $recipe .= " <li>".$ingredient['quantity'].$unit.' '.$ingredient['name']."</li>";
        }
        $recipe .= "</ul>";
        $recipe .= "<h3>Étapes</h3>";
        
        $recipe .= "<ol>";
        foreach ($steps as $step) {
            $recipe .= "<li>".utf8_decode($step['text'])."</li>";

        }
        $recipe .= "
                </ol>
            </div>
        </div>
        ";
        return $recipe;
    }


    public function selectData($data, $name, $selected = false, $valueSelected = false){
        $select = "
        <select name='$name' id=''>";
        foreach ($data as $item) {
            $id = $item['id'];
            $value = (isset($item['text']) ? $item['text'] : $item['name']);
            $valueH = utf8_decode($value);
            if($selected && $value === $valueSelected){
                $select .= "<option selected value='$id'>$valueH</option>";

            }else{
                $select .= "<option value='$id'>$valueH</option>";
            }
        }
            $select .= "
        </select>";
        return $select;
    }

    public function setHtmlAdd($units, $ingredients, $category, $isModif = false, $recipe = false){
        if($isModif){
            $this->selectData($units, 'unit_1');
            $form = "<form id='' action='' method='post'>
            <input type='text' value='".$recipe['name']."' name='name' id=''>
            <label for='category'>Categorie</label>
            ".$this->selectData($category, 'category')."
            <input type='text' name='description' value='".$recipe['description']."'id=''>"; 
            $form .=  "<h4>ingrdeients</h4>
            <input type='number' value='".intval($recipe['ingredients'][0]['quantity'])."' name='qty_1' id=''>
            ".        $this->selectData($ingredients, 'ingr_1', true, $recipe['ingredients'][0]['name'] )
            ."".        $this->selectData($units, 'unit_1', true, $recipe['ingredients'][0]['text'] )
            ."
            <input type='number' value='".intval($recipe['ingredients'][1]['quantity'])."' name='qty_2' id=''>
            ".        $this->selectData($ingredients, 'ingr_2', true, $recipe['ingredients'][1]['name'])
            ."
            ".        $this->selectData($units, 'unit_2', true, $recipe['ingredients'][1]['text'] )
            ."
            <input type='number'  value='".intval($recipe['ingredients'][2]['quantity'])."' name='qty_3' id=''>
            ".        $this->selectData($ingredients, 'ingr_3', true, $recipe['ingredients'][2]['name'])
            ."
            ".        $this->selectData($units, 'unit_3', true, $recipe['ingredients'][2]['text'] )
            ."
                <h4>Étapes</h4>
            <input type='text' value='".$recipe['steps'][0]['text']."'name='step_1' id=''>
            <input type='text'  value='".$recipe['steps'][1]['text']."' name='step_2' id=''>
            <input type='text'  value='".$recipe['steps'][2]['text']."' name='step_3' id=''>
            <button type='submit'>Modidier</button>";
            $form .=    "</form>";

            return $form;
        }
        $this->selectData($units, 'unit_1');
        $form = "<form id='' action='' method='post'>
        <input type='text' name='name' id=''>
        <label for='category'>Categorie</label>
        ".$this->selectData($category, 'category')."
        <input type='text' name='description' id=''>"; 

        $form .=  "<h4>ingrdeients</h4>
        <input type='number' name='qty_1' id=''>
        ".        $this->selectData($ingredients, 'ingr_1')
        ."".        $this->selectData($units, 'unit_1')
        ."
        <input type='number' name='qty_2' id=''>
         ".        $this->selectData($ingredients, 'ingr_2')
        ."
        ".        $this->selectData($units, 'unit_2')
        ."
        <input type='number' name='qty_3' id=''>
        ".        $this->selectData($ingredients, 'ingr_3')
        ."
        ".        $this->selectData($units, 'unit_3')
        ."
            <h4>Étapes</h4>
        <input type='text' name='step_1' id=''>
        <input type='text' name='step_2' id=''>
        <input type='text' name='step_3' id=''>
        <button type='submit'>Ajouter</button>";
        $form .=    "</form>";

        return $form;
    
    }
}