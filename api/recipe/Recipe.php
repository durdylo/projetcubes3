<?php

class Recipe {
	public $id;
	public $name;
	public $description;
	public $id_user;
	public $is_delete;

	public function __construct($data) {
		$this->set($data);
	}

	public function	set($data) {
		if ($data == 0)
			return (0);
		foreach ($data AS $key => $value)
			$this->{$key} = $value;
	}

	public function	selectUserRecipes($conn) {
		$select_query = "SELECT * FROM `recette` WHERE id_user='$this->id_user' AND is_delete='0'";
		$stmt = $conn->prepare($select_query);
		$stmt->execute();
		return  $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function	selectRecipe($conn) {
		$select_recette = "SELECT * from recette where id = $this->id";
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

	public function	insertRecipe($conn) {
		$insert_query = "INSERT INTO `recette`(name, description, id_user )VALUES(:name,:description,:id_user)";
        $insert_stmt = $conn->prepare($insert_query);
        // DATA BINDING
        $insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($this->name)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':description', htmlspecialchars(strip_tags($this->description)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':id_user', htmlspecialchars(strip_tags($this->id_user)), PDO::PARAM_INT);
		return $insert_stmt->execute();
	}
}

?>