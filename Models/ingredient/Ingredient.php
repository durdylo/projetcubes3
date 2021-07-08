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
	
	public static function	deleteIngredientsFromRecipe($conn, $id_recipe) {
		$delete_ingredients = "DELETE FROM ingredient_recipe WHERE id_recipe = :id_recipe";
		$stmt = $conn->prepare($delete_ingredients);
		$stmt->bindValue(':id_recipe', htmlspecialchars(strip_tags($id_recipe)), PDO::PARAM_INT);
		try {
			return $stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public static function	selectIngredientsFromRecipe($conn, $recipe_id) {
		$select_ingredients = "SELECT ingredient.name, ingredient_recipe.quantity, unit.text FROM ingredient INNER JOIN ingredient_recipe on ingredient.id = ingredient_recipe.id_ingredient".
		" INNER JOIN unit on ingredient_recipe.id_unit = unit.id where ingredient_recipe.id_recipe = '$recipe_id'";
		$stmt = $conn->prepare($select_ingredients);
		try {
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public static function	insertIngredientsInRecipe($conn, $id_recipe, $id, $quantity, $id_unit) {
		$insert_query = "INSERT INTO `ingredient_recipe`(id_recipe, id_ingredient, quantity, id_unit)VALUES(:id_recipe, :id_ingredient, :quantity, :id_unit)";
		$stmt = $conn->prepare($insert_query);
		$stmt->bindValue(':id_recipe', htmlspecialchars(strip_tags($id_recipe)),  PDO::PARAM_INT);
		$stmt->bindValue(':id_ingredient', htmlspecialchars(strip_tags($id)), PDO::PARAM_INT);
		$stmt->bindValue(':quantity', htmlspecialchars(strip_tags($quantity)), PDO::PARAM_INT);
		$stmt->bindValue(':id_unit', htmlspecialchars(strip_tags($id_unit)), PDO::PARAM_INT);
		try {
			return $stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public static function	selectAllIngredients($conn) {
		$select_ingredients = "SELECT * FROM ingredient ORDER BY id ASC";
		$stmt = $conn->prepare($select_ingredients);
		try {
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch (Exception $e) {
			return (false);
		}
	}

	//TODO GERER LES EXCEPTIONS POUR TOUT LES $stmt->execute();
	public function			insertIngredient($conn) {
		$insert_query = "INSERT INTO `ingredient`(name) VALUES(:name)";
		$insert_stmt = $conn->prepare($insert_query);
		$insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($this->name)), PDO::PARAM_STR);
		try {
			return $insert_stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public function			updateIngredient($conn) {
		$update_query = "UPDATE ingredient SET name= :name WHERE id= :id";

		$stmt = $conn->prepare($update_query);
		$stmt->bindValue(':name', htmlspecialchars(strip_tags($this->name)), PDO::PARAM_STR);
		$stmt->bindValue(':id', htmlspecialchars(strip_tags($this->id)), PDO::PARAM_INT);
		try {
			return $stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public static function	updateIngredientInRecipe($conn, $recipe, $ingredient_recipe) {
		$update_query = "INSERT INTO ingredient_recipe (id_ingredient, id_recipe, quantity, id_unit) VALUES(:id_ingredient, :id_recipe, :quantity, :id_unit) ON DUPLICATE KEY UPDATE quantity=:quantity, id_unit=:id_unit;";

		$stmt = $conn->prepare($update_query);
		$stmt->bindValue(':quantity', htmlspecialchars(strip_tags($ingredient_recipe->quantity)), PDO::PARAM_INT);
		$stmt->bindValue(':id_unit', htmlspecialchars(strip_tags($ingredient_recipe->id_unit)), PDO::PARAM_INT);
		$stmt->bindValue(':id_ingredient', htmlspecialchars(strip_tags($ingredient_recipe->id)), PDO::PARAM_INT);
		$stmt->bindValue(':id_recipe', htmlspecialchars(strip_tags($recipe->id)), PDO::PARAM_INT);
		try {
			return $stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public function			deleteIngredient($conn) {
	
		$delete_query = "DELETE FROM ingredient WHERE id= :id";
		$stmt = $conn->prepare($delete_query);
		$stmt->bindValue(':id', htmlspecialchars(strip_tags($this->id)), PDO::PARAM_INT);
		try {
			return $stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public static function		deleteIngredientInRecipe($conn, $recipe, $not_to_delete) {
		$placeHolders = implode(', ', array_fill(0, count($not_to_delete), '?'));
		if (count($not_to_delete) > 0)
			$delete_query = "DELETE FROM ingredient_recipe WHERE id_recipe= ? AND id_ingredient NOT IN ($placeHolders)";
		else
			$delete_query = "DELETE FROM ingredient_recipe WHERE id_recipe= ?";

		$stmt = $conn->prepare($delete_query);
		$stmt->bindValue(1, htmlspecialchars(strip_tags($recipe->id)), PDO::PARAM_INT);
		foreach ($not_to_delete as $index => $value) {
			$stmt->bindValue($index + 2, $value, PDO::PARAM_INT);
		}
		try {
			return $stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}
}

?>