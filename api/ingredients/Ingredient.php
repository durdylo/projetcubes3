<?php 

class Ingredient {
	public	$id;
	public	$name;
	public	$quantity;
	public function __construct($data) {
		$this->set($data);
	}

	public function	set($data) {
		if ($data == 0)
			return (0);
		foreach ($data AS $key => $value)
			$this->{$key} = $value;
	}
	
	public static function	selectIngredientsFromRecipe($conn, $recipe_id) {
		$select_ingredients = "SELECT ingredient.name, ingredient_recette.quantity, ingredient_recette.id_recette from ingredient INNER JOIN ingredient_recette on ingredient.id = ingredient_recette.id_ingredient where ingredient_recette.id_recette = '$recipe_id'";
		$stmt = $conn->prepare($select_ingredients);
		$stmt->execute();
		return  $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}

?>