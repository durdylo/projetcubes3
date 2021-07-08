<?php

class Recipe {
	public $id;
	public $name;
	public $description;
	public $id_user;
	public $id_category;
	public $name_category;

	public function __construct($data) {
		$this->set($data);
	}

	public function	set($data) {
		if ($data == null)
			return (0);
		foreach ($data AS $key => $value)
			$this->{$key} = $value;
	}

	public function	selectUserRecipes($conn) {
		$select_query = "SELECT recipe.*, category.name as 'name_category' FROM `recipe` LEFT JOIN category ON recipe.id_category = category.id WHERE id_user='$this->id_user'";
		$stmt = $conn->prepare($select_query);
		try {
			$stmt->execute();
			return  $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public function	selectRecipe($conn) {
		$select_recette = "SELECT recipe.*, category.name as 'name_category' FROM `recipe` LEFT JOIN category ON recipe.id_category = category.id where recipe.id = $this->id";
		$stmt = $conn->prepare($select_recette);
		$stmt->execute();
		$res =  $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($res == 0 || count($res) == 0) {
			return (false);
		}
		else {
			$this->set($res[0]);
		}
		return (true);
	}
	public function	selectRecipes($conn) {
		$select_recette = "SELECT recipe.*, category.name as 'name_category' FROM `recipe` LEFT JOIN category ON recipe.id_category = category.id";
		$stmt = $conn->prepare($select_recette);
		try {
			$stmt->execute();
			return  $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch (Exception $e) {
			return (false);
		}
	
	}
	public function	insertRecipe($conn) {
		$insert_query = "INSERT INTO `recipe`(name, description, id_user, id_category)VALUES(:name,:description,:id_user,:id_category)";
        $insert_stmt = $conn->prepare($insert_query);
        // DATA BINDING
        $insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($this->name)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':description', htmlspecialchars(strip_tags($this->description)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':id_user', htmlspecialchars(strip_tags($this->id_user)), PDO::PARAM_INT);
        $insert_stmt->bindValue(':id_category', htmlspecialchars(strip_tags($this->id_category)), PDO::PARAM_INT);
		try {
			return $insert_stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public function	updateRecipe($conn) {
		$update_query = "UPDATE recipe SET name = :name, description = :description, id_category = :id_category WHERE id = :id;";
		$stmt = $conn->prepare($update_query);
		$stmt->bindValue(':name', htmlspecialchars(strip_tags($this->name)), PDO::PARAM_STR);
		$stmt->bindValue(':description', htmlspecialchars(strip_tags($this->description)), PDO::PARAM_STR);
		$stmt->bindValue(':id_category', htmlspecialchars(strip_tags($this->id_category)), PDO::PARAM_INT);
		$stmt->bindValue(':id', htmlspecialchars(strip_tags($this->id)), PDO::PARAM_INT);
		try {
			return $stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public function	deleteRecipe($conn) {
		if ((Step::deleteStepsFromRecipe($conn, $this->id)) === false || (Ingredient::deleteIngredientsFromRecipe($conn, $this->id)) === false)
			return (false);
		$delete_query = "DELETE FROM recipe WHERE id= :id";
		$stmt = $conn->prepare($delete_query);
		$stmt->bindValue(':id', htmlspecialchars(strip_tags($this->id)), PDO::PARAM_INT);
		try {
			return $stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}
}

?>