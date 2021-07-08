<?php
class User
{
	public	$id;
	public	$name;
	public	$firstname;
	public	$email;
	public	$password;
	public	$id_role;
	public	$is_deleted;

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

	public function selectUser($conn)
	{
		$encodedpass = hash('sha256', $this->password);
		$select_query = "SELECT * FROM `user` WHERE email='$this->email' AND is_deleted='0';";
		$stmt = $conn->prepare($select_query);
		$stmt->execute();
		$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if ($res === false || count($res) === 0 || password_verify($this->password, $res[0]["password"]) === false) {
	
			return (false);
		} else {
			$this->set($res[0]);
		}
		return true;
	}
	
	public static function selectAllUsers($conn)
	{
		$select_query = "SELECT * FROM `user` WHERE is_deleted='0';";
		$stmt = $conn->prepare($select_query);
		try {
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch (Exception $e) {
			return (false);
		}
	}
	
	public function selectUserById($conn)
	{
		$select_query = "SELECT * FROM `user` WHERE id='$this->id' AND is_deleted='0';";
		$stmt = $conn->prepare($select_query);
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

	public function	insertUser($conn)
	{
		if (!($encodedpass = password_hash($this->password, PASSWORD_DEFAULT)))
			return (false);
		$insert_query = "INSERT INTO `user`(name,email,password, firstname, id_role) VALUES(:name,:email,:password,:firstname,2)";

		$insert_stmt = $conn->prepare($insert_query);
		// DATA BINDING
		$insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($this->name)), PDO::PARAM_STR);
		$insert_stmt->bindValue(':firstname', htmlspecialchars(strip_tags($this->firstname)), PDO::PARAM_STR);
		$insert_stmt->bindValue(':email', htmlspecialchars(strip_tags($this->email)), PDO::PARAM_STR);
		$insert_stmt->bindValue(':password', htmlspecialchars(strip_tags($encodedpass)), PDO::PARAM_STR);
		try {
			return $insert_stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public function	updateUser($conn) {
		if (!($encodedpass = password_hash($this->password, PASSWORD_DEFAULT)))
			return (false);
		$update_query = "UPDATE user SET name = :name, firstname = :firstname, email = :email, password = :password WHERE id = :id;";
		$stmt = $conn->prepare($update_query);
		$stmt->bindValue(':name', htmlspecialchars(strip_tags($this->name)), PDO::PARAM_STR);
		$stmt->bindValue(':firstname', htmlspecialchars(strip_tags($this->firstname)), PDO::PARAM_STR);
		$stmt->bindValue(':email', htmlspecialchars(strip_tags($this->email)), PDO::PARAM_STR);
		$stmt->bindValue(':password', htmlspecialchars(strip_tags($encodedpass)), PDO::PARAM_STR);
		$stmt->bindValue(':id', htmlspecialchars(strip_tags($this->id)), PDO::PARAM_INT);
		try {
			return $stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public function deleteUser($conn)
	{
		$delete_query = "UPDATE user SET is_deleted = 1 WHERE id='$this->id';";
		$stmt = $conn->prepare($delete_query);
		$stmt->execute();
		try {
			return $stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}
}