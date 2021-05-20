<?php
class Step {
	public	$id;
	public	$id_recipe;
	public	$step_order;
	public	$text;

	public function __construct($data) {
		$this->set($data);
	}

	public function	set($data) {
		if ($data == null)
			return (0);
		foreach ($data AS $key => $value)
			$this->{$key} = $value;
	}

	public static function	deleteStepsFromRecipe($conn, $id_recipe) {
		$delete_steps = "DELETE FROM step WHERE step.id_recipe = :id_recipe";
		$stmt = $conn->prepare($delete_steps);
		$stmt->bindValue(':id', htmlspecialchars(strip_tags($id_recipe)), PDO::PARAM_INT);
		try {
			return $stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public static function selectStepsFromRecipe($conn, $id_recipe) {
		$select_query = "SELECT * FROM `step` WHERE id_recipe='$id_recipe' ORDER BY step_order ASC;";
		$stmt = $conn->prepare($select_query);
		$stmt->execute();
		return  $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public static function	insertStepsInRecipe($conn, $id_recipe, $step_order, $text) {
		$insert_query = "INSERT INTO `step`(id_recipe, step_order, text) VALUES(:id_recipe,:step_order,:text)";

		$insert_stmt = $conn->prepare($insert_query);
		$insert_stmt->bindValue(':id_recipe', htmlspecialchars(strip_tags($id_recipe)), PDO::PARAM_INT);
		$insert_stmt->bindValue(':step_order', htmlspecialchars(strip_tags($step_order)), PDO::PARAM_INT);
		$insert_stmt->bindValue(':text', htmlspecialchars(strip_tags($text)), PDO::PARAM_STR);
		return $insert_stmt->execute();
	}

	public function			insertStep($conn) {
		$insert_query = "INSERT INTO `step`(id_recipe, step_order, text) VALUES(:id_recipe, :step_order, :text)";
		$insert_stmt = $conn->prepare($insert_query);
		$insert_stmt->bindValue(':id_recipe', htmlspecialchars(strip_tags($this->id_recipe)), PDO::PARAM_STR);
		$insert_stmt->bindValue(':step_order', htmlspecialchars(strip_tags($this->step_order)), PDO::PARAM_STR);
		$insert_stmt->bindValue(':text', htmlspecialchars(strip_tags($this->text)), PDO::PARAM_STR);
		try {
			return $insert_stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public function			updateStep($conn) {
		$update_query = "UPDATE step SET text= :text WHERE id= :id";

		$stmt = $conn->prepare($update_query);
		$stmt->bindValue(':text', htmlspecialchars(strip_tags($this->text)), PDO::PARAM_STR);
		$stmt->bindValue(':id', htmlspecialchars(strip_tags($this->id)), PDO::PARAM_INT);
		try {
			return $stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public function			deleteStep($conn) {
	
		$delete_query = "DELETE FROM step WHERE id= :id";
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