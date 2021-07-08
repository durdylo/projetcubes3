<?php
class Category
{
	public	$id;
	public	$name;

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

	public function		selectCategory($conn) {
		$select_query = "SELECT * FROM category WHERE id = :id";
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

	public function		selectAllCategories($conn) {
		$select_query = "SELECT * FROM category";
		$stmt = $conn->prepare($select_query);
		try {
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public function			insertCategory($conn) {
		$insert_query = "INSERT INTO `category`(name) VALUES(:name)";
		$insert_stmt = $conn->prepare($insert_query);
		// DATA BINDING
		$insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($this->name)), PDO::PARAM_STR);
		try {
			return $insert_stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	
	public function			updateCategory($conn) {
		$update_query = "UPDATE category SET name = :name WHERE id = :id;";
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
}

?>