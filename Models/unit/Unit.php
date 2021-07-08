<?php
class Unit
{
	public	$id;
	public	$text;

	public function __construct($data)
	{
		$this->set($data);
	}

	public function	set($data)
	{
		if ($data == null)
			return (0);
		foreach ($data as $key => $value)
			$this->{$key} = $value;
	}

	public function		selectUnit($conn) {
		$select_query = "SELECT * FROM unit WHERE id = :id";
		$stmt = $conn->prepare($select_query);
		$stmt->bindValue(':id', htmlspecialchars(strip_tags($this->id)), PDO::PARAM_INT);
		try {			
			$stmt->execute();
			$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if ($res == 0 || count($res) == 0) {
				return (false);
			} else {
				$this->set($res[0]);
			}
		}
		catch (Exception $e) {
			return (false);
		}
		return (true);
	}

	public function		selectAllUnits($conn) {
		$select_query = "SELECT * FROM unit";
		$stmt = $conn->prepare($select_query);
		try {
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public function			deleteUnit($conn) {
		$delete_query = "DELETE FROM unit WHERE id= :id";
		$stmt = $conn->prepare($delete_query);
		$stmt->bindValue(':id', htmlspecialchars(strip_tags($this->id)), PDO::PARAM_INT);
		try {
			return $stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public function			insertUnit($conn) {
		$insert_query = "INSERT INTO `unit`(text) VALUES(:text)";
		$insert_stmt = $conn->prepare($insert_query);
		// DATA BINDING
		$insert_stmt->bindValue(':text', htmlspecialchars(strip_tags($this->text)), PDO::PARAM_STR);
		try {
			return $insert_stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	
	public function			updateUnit($conn) {
		$update_query = "UPDATE unit SET text = :text WHERE id = :id;";
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
}

?>