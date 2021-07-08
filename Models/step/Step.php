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
		$delete_steps = "DELETE FROM step WHERE id_recipe = :id_recipe";
		$stmt = $conn->prepare($delete_steps);
		$stmt->bindValue(':id_recipe', htmlspecialchars(strip_tags($id_recipe)), PDO::PARAM_INT);
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
		try {
			$stmt->execute();
			return  $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch (Exception $e) {
			return (false);
		}
	}
	
	public static function	insertStepsInRecipe($conn, $id_recipe, $step_order, $text) {
		$insert_query = "INSERT INTO `step`(id_recipe, step_order, text) VALUES(:id_recipe,:step_order,:text)";

		$insert_stmt = $conn->prepare($insert_query);
		$insert_stmt->bindValue(':id_recipe', htmlspecialchars(strip_tags($id_recipe)), PDO::PARAM_INT);
		$insert_stmt->bindValue(':step_order', htmlspecialchars(strip_tags($step_order)), PDO::PARAM_INT);
		$insert_stmt->bindValue(':text', htmlspecialchars(strip_tags($text)), PDO::PARAM_STR);
		try {
			return $insert_stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
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

	public static function		updateStepInRecipe($conn, $recipe, $step) {
		$update_query = "UPDATE step SET text= :text WHERE id_recipe= :id_recipe AND step_order= :step_order;";
		$update_query = "INSERT INTO step (text, id_recipe, step_order) VALUES(:text, :id_recipe, :step_order) ON DUPLICATE KEY UPDATE text=:text";

		$stmt = $conn->prepare($update_query);
		$stmt->bindValue(':text', htmlspecialchars(strip_tags($step->text)), PDO::PARAM_STR);
		$stmt->bindValue(':id_recipe', htmlspecialchars(strip_tags($recipe->id)), PDO::PARAM_INT);
		$stmt->bindValue(':step_order', htmlspecialchars(strip_tags($step->step_order)), PDO::PARAM_INT);
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
	
	public static function		deleteStepsInRecipe($conn, $recipe, $not_to_delete) {
		$placeHolders = implode(', ', array_fill(0, count($not_to_delete), '?'));
		if (count($not_to_delete) > 0)
			$delete_query = "DELETE FROM step WHERE id_recipe= ? AND step_order NOT IN ($placeHolders);";
		else
			$delete_query = "DELETE FROM step WHERE id_recipe= ?;";
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
