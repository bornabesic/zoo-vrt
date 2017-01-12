<?php

	require_once (__DIR__ . "/config.php");

	class UsersDB {

		private $db;

		public function __construct($database){
			$this->db=$database;
		}

		// Metoda za registraciju novog korisnika
		function register_user($username, $password, $first_last_name, $year_of_birth, $city, $email, $role){
			require_once(__DIR__ . "/phpass/PasswordHash.php");
			$hasher = new PasswordHash(8, false);
			$password_hash = $hasher->HashPassword($password);

			// REGISTER USER
			$registration_query = "INSERT INTO " . DB_NAME . ".users (`user_id`, `username`, `password_hash`, `first_last_name`, `year_of_birth`, `city`, `email`, `role`) VALUES ('', ?, ?, ?, ?, ?, ?, ?);";
			$registration_statement = $this->db->prepare($registration_query);
			if($registration_statement){
				$registration_statement->bind_param("sssissi", $username, $password_hash, $first_last_name, $year_of_birth, $city, $email, $role);
		     	$registration_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			switch($registration_statement->affected_rows){
				case -1:
					return json_encode(array(
						"error" => $this->db->error 
					));
					break;
				case 0:
					return json_encode(array(
						"error" => "User could not be registered." 
					));
					break;
			};

			// GET USER'S ID

			$user_id_query = "SELECT * FROM " . DB_NAME . ".users WHERE users.username=?;";
			$user_id_statement = $this->db->prepare($user_id_query);
			if($user_id_statement){
				$user_id_statement->bind_param("s", $username);
		     	$user_id_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			$user_id_result = $user_id_statement->get_result();

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			$row = $user_id_result->fetch_assoc();

			return json_encode(array(
				"user_id" => $row['user_id'],
				"username" => $username,
				"first_last_name" => $first_last_name,
				"year_of_birth" => $year_of_birth,
				"city" => $city,
				"email" => $email,
				"role" => $role
			));
		}

		// Metoda za brisanje korisnika
		function delete_user($user_id){
			// DELETE USER WITH GIVEN ID
			$delete_query="DELETE FROM ". DB_NAME . ".users WHERE user_id=?;";
			$delete_statement=$this->db->prepare($delete_query);
			if($delete_statement){
				$delete_statement->bind_param("i", $user_id);
		     	$delete_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			switch($delete_statement->affected_rows){
				case -1:
					return json_encode(array(
						"error" => $this->db->error 
					));
					break;
				case 0:
					return json_encode(array(
						"error" => "User with the given ID could not be deleted." 
					));
					break;
			};

			return json_encode(array(
				"user_id" => $user_id
			));
		}

		// Metoda za prijavu korisnika
		function login_user($username, $password){
			require_once(__DIR__ . "/phpass/PasswordHash.php");
			$hasher = new PasswordHash(8, false);

			//USER INFO
			$user_query="SELECT * FROM " . DB_NAME . ".users WHERE users.username=?;";
			$user_statement = $this->db->prepare($user_query);
			if($user_statement){
				$user_statement->bind_param("s", $username);
		     	$user_statement->execute();
			}
			else{
				return json_encode(
							array( "error" => $this->db->error )
						);
			}
			

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			$user_result = $user_statement->get_result();
			$row = $user_result->fetch_assoc();

			if($hasher->CheckPassword($password, $row['password_hash'])){
				$_SESSION["logged_in"]=true;
				$_SESSION["role"]=$row['role'];

				return json_encode(array(
					"user_id" => $row['user_id'],
					"role" => $row['role'],
					"username" => $row['username'],
					"first_last_name" => $row['first_last_name'],
					"year_of_birth" => $row['year_of_birth'],
					"city" => $row['city'],
					"email" => $row['email']
				));
			}
			else {
				return json_encode(
					array( "error" => "Username or password invalid." )
				);
			}
		}

		// Metoda za odjavu korisnika
		function logout_user(){
			$_SESSION["logged_in"]=false;
			$_SESSION["role"]=0;
			return json_encode(array("status" => "Successfully logged out."));
		}

		// Metoda za ažuriranje korisnika
		function update_user($username, $password, $first_last_name, $year_of_birth, $city, $email, $role, $user_id){
			require_once(__DIR__ . "/phpass/PasswordHash.php");
			$hasher = new PasswordHash(8, false);
			$password_hash=null;
			if($password!=null) $password_hash = $hasher->HashPassword($password);

			$bind_params=array("username", "password_hash", "first_last_name", "year_of_birth", "city", "email", "role", "user_id");
			$bind_values=array($username, $password_hash, $first_last_name, $year_of_birth, $city, $email, $role, $user_id);
			$bind_types=array("s", "s", "s", "i", "s", "s", "i", "i");

			$query="UPDATE ". DB_NAME . ".users SET ";

			$type_string="";
			for($i=0; $i<count($bind_params); $i++){
				if($bind_values[$i]!=null){ // povezi sve osim user_id
					$type_string.=$bind_types[$i];

					if($i!=count($bind_params)-1)
						$query.=$bind_params[$i] . "=?,";
				}
			}
			$query = substr($query, 0, -1);
			$query .= " WHERE `user_id`=?;";

			$a_params[] = & $type_string;
			for($i=0; $i<count($bind_params); $i++){
				if($bind_values[$i]!=null){
					$a_params[]=& $bind_values[$i];
				}
			}


			// UPDATE USER WITH GIVEN ID
			//OLD $update_query="UPDATE ". DB_NAME . ".users SET `username`=?, `password_hash`=?, `first_last_name`=?, `year_of_birth`=?, `city`=?, `email`=?, `role`=? WHERE `user_id`=?;";
			$update_statement=$this->db->prepare($query);
			if($update_statement){
				call_user_func_array(array($update_statement, "bind_param"), $a_params);
				//OLD $update_statement->bind_param("sssissii", $username, $password_hash, $first_last_name, $year_of_birth, $city, $email, $role, $user_id);
		     	$update_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			switch($update_statement->affected_rows){
				case -1:
					return json_encode(array(
						"error" => $this->db->error 
					));
					break;
				case 0:
					return json_encode(array(
						"error" => "User with the given ID could not be updated." 
					));
					break;
			};

			return json_encode(array(
				"user_id" => $user_id
			));
		}

		// Metoda za dohvat svih korisnika
		function get_users(){
			$users_query = "SELECT * FROM " . DB_NAME . ".users;";
			$users_statement = $this->db->prepare($users_query);
			if($users_statement){
				//$users_statement->bind_param("s", $username);
		     	$users_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			$users_result = $users_statement->get_result();

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			$users = array();
			while ($row = $users_result->fetch_assoc()) {
				array_push($users, array(
					"user_id" => $row["user_id"],
					"username" => $row["username"],
					"first_last_name" => $row["first_last_name"],
					"year_of_birth" => $row["year_of_birth"],
					"city" =>$row["city"],
					"email" => $row["email"],
					"role" => $row["role"]
				));
    		}

			return json_encode($users);
		}

	}

?>