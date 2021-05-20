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
		if ($res == 0 || count($res) == 0 || !password_verify($this->password, $res[0]["password"])) {
			return (false);
		} else {
			$this->set($res[0]);
		}
		return (true);
	}

	public function	insertUser($conn)
	{
		if (!($encodedpass = password_hash($this->password, PASSWORD_DEFAULT)))
			return (false);
		$insert_query = "INSERT INTO `user`(name,email,password, firstname,id_role) VALUES(:name,:email,:password,:firstname,:id_role)";

		$insert_stmt = $conn->prepare($insert_query);
		// DATA BINDING
		$insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($this->name)), PDO::PARAM_STR);
		$insert_stmt->bindValue(':firstname', htmlspecialchars(strip_tags($this->firstname)), PDO::PARAM_STR);
		$insert_stmt->bindValue(':email', htmlspecialchars(strip_tags($this->email)), PDO::PARAM_STR);
		$insert_stmt->bindValue(':password', htmlspecialchars(strip_tags($encodedpass)), PDO::PARAM_STR);
		$insert_stmt->bindValue(':id_role', htmlspecialchars(strip_tags($this->id_role)), PDO::PARAM_INT);
		try {
			return $insert_stmt->execute();
		}
		catch (Exception $e) {
			return (false);
		}
	}

	public function	updateUser($conn) {
		
	}
}