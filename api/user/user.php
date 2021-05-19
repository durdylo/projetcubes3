<?php
	class User {
		public	$id;
		public	$name;
		public	$firstname;
		public	$email;
		public	$password;
		public	$role;
		public	$is_delete;
 
		public function __construct($data) {
			$this->set($data);
		}

		public function	set($data) {
			foreach ($data AS $key => $value)
				$this->{$key} = $value;
		}

		public function selectUser($conn) {
			$encodedpass = hash('sha256', $this->password);
			$select_query = "SELECT * FROM `user` WHERE email='$this->email' AND password='$encodedpass' AND is_delete='0';";
			$stmt = $conn->prepare($select_query);
			$stmt->execute();
			$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if ($res == 0 || count($res) == 0) {
				return (false);
			}
			else {
				$this->set($res[0]);
			}
			return (true);
		}
	}
?>
