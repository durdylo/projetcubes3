<?php 

class Ingredient {
	public	$id;
	public	$name;
	public	$quantity;
	
	public function 		__construct($data) {
		$this->set($data);
	}

	public function			set($data) {
		if ($data == null)
			return (0);
		foreach ($data AS $key => $value)
			$this->{$key} = $value;
	}
	
	public static function	selectIngredientsFromRecipe($conn, $recipe_id) {
		$select_ingredients = "SELECT ingredient.name, ingredient_recipe.quantity, unit.text FROM ingredient INNER JOIN ingredient_recipe on ingredient.id = ingredient_recipe.id_ingredient".
		" INNER JOIN unit on ingredient_recipe.id_unit = unit.id where ingredient_recipe.id_recipe = '$recipe_id'";
		$stmt = $conn->prepare($select_ingredients);
		$stmt->execute();
		return  $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function	insertIngredientsInRecipe($conn, $id_recipe, $id, $quantity, $id_unit) {
		$insert_query = "INSERT INTO `ingredient_recipe`(id_recipe, id_ingredient, quantity, id_unit)VALUES(:id_recipe, :id_ingredient, :quantity, :id_unit)";
		$stmt = $conn->prepare($insert_query);
		$stmt->bindValue(':id_recipe', htmlspecialchars(strip_tags($id_recipe)),  PDO::PARAM_INT);
		$stmt->bindValue(':id_ingredient', htmlspecialchars(strip_tags($id)), PDO::PARAM_INT);
		$stmt->bindValue(':quantity', htmlspecialchars(strip_tags($quantity)), PDO::PARAM_INT);
		$stmt->bindValue(':id_unit', htmlspecialchars(strip_tags($id_unit)), PDO::PARAM_INT);
		return $stmt->execute();
	}

	public static function	selectAllIngredients($conn) {
		$select_ingredients = "SELECT * FROM ingredient";
		$stmt = $conn->prepare($select_ingredients);
		$stmt->execute();
		return  $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function			insertIngredient($conn) {
		$insert_query = "INSERT INTO `ingredient`(name) VALUES(:name)";

		$insert_stmt = $conn->prepare($insert_query);
		$insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($this->name)), PDO::PARAM_STR);
		return $insert_stmt->execute();
	}

	public function			updateIngredient($conn) {
		$insert_query = "UPDATE ingredient SET name= :name WHERE id= :id";

		$insert_stmt = $conn->prepare($insert_query);
		$insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($this->name)), PDO::PARAM_STR);
		$insert_stmt->bindValue(':id', htmlspecialchars(strip_tags($this->id)), PDO::PARAM_INT);
		return $insert_stmt->execute();
	}
}

?>