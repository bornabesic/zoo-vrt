<?php
	
	//echo json_encode(array( "error" => "Kuki"));

	define("DB_USERNAME", "zoo_vrt");
	define("DB_PASSWORD", "rzjesmece");
	define("DB_NAME", "zoo_vrt");

	class Database {

		private $db;

		public function __construct(){
			$this->db = mysqli_connect('localhost', DB_USERNAME, DB_PASSWORD);
			if(!$this->db) echo json_encode(
							array( "error" => $this->db->error )
						);
			$this->db->set_charset('utf8');
		}

		public function register_user($username, $password, $first_last_name, $year_of_birth, $city, $email, $role){
			require_once("./phpass/PasswordHash.php");
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
				"email" => $email
			));
		}

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

		function add_class($name){
			//ADD CLASS
			$add_class_query = "INSERT INTO " . DB_NAME . ".classes (`name`) VALUES (?);";
			$add_class_statement = $this->db->prepare($add_class_query);
			if($add_class_statement){
				$add_class_statement->bind_param("s", $name);
		     	$add_class_statement->execute();
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

			// GET CLASS ID
			$class_id_query = "SELECT * FROM " . DB_NAME . ".classes WHERE name=?;";
			$class_id_statement = $this->db->prepare($class_id_query);
			if($class_id_statement){
				$class_id_statement->bind_param("s", $name);
		     	$class_id_statement->execute();
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

			$class_id_result = $class_id_statement->get_result();
			$row = $class_id_result->fetch_assoc();

			return json_encode(array(
				"class_id" => $row['class_id'],
				"name" => $name
			));
		}

		function remove_class($class_id){
			// REMOVE CLASS WITH GIVEN ID
			$delete_query="DELETE FROM ". DB_NAME . ".classes WHERE class_id=?;";
			$delete_statement=$this->db->prepare($delete_query);
			if($delete_statement){
				$delete_statement->bind_param("i", $class_id);
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
						"error" => "Class with the given ID could not be deleted." 
					));
					break;
			};

			return json_encode(array(
				"class_id" => $class_id
			));
		}

		function add_order($name){
			//ADD ORDER
			$add_order_query = "INSERT INTO " . DB_NAME . ".orders (`name`) VALUES (?);";
			$add_order_statement = $this->db->prepare($add_order_query);
			if($add_order_statement){
				$add_order_statement->bind_param("s", $name);
		     	$add_order_statement->execute();
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

			// GET ORDER ID
			$order_id_query = "SELECT * FROM " . DB_NAME . ".orders WHERE name=?;";
			$order_id_statement = $this->db->prepare($order_id_query);
			if($order_id_statement){
				$order_id_statement->bind_param("s", $name);
		     	$order_id_statement->execute();
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

			$order_id_result = $order_id_statement->get_result();
			$row = $order_id_result->fetch_assoc();

			return json_encode(array(
				"order_id" => $row['order_id'],
				"name" => $name
			));
		}

		//ak{
		function remove_order($order_id){
			//remove order with given id
			$delete_query = "DELETE FROM " . DB_NAME . ".orders WHERE order_id=?;";
			$delete_statement = $this->db->prepare($delete_query);
			if($delete_statement){
				$delete_statement->bind_param("i", $class_id);
				$delete_statement->execute();
			}else{
				return json_encode(
					array( "error" => $this->db->error )
				);
			}

			if($this->db->errno != 0){
				return json_encode(array(
					"error" => $this->db->error
				));
			}

			switch ($delete_statement->affected_rows) {
				case -1:
					return json_encode(array(
						"error" => $this->db->error
					));
					break;
				case 0:
					return json_encode(array(
						"error" => "Order with the given ID could not be deleted."
					));
					break;
			};
			return json_encode(array(
				"order_id" => $order_id;
			));
		}

		function add_family($name){
			$add_family_query = "INSERT INTO " .DB_NAME . ".families (`name`) VALUES (?);";
			$add_family_statement = $this->db->prepare($add_family_query);
			if($add_family_statement){
				$add_family_statement->bind_param("s", $name);
				$add_family_statement->execute();
			}else{
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			if($this->db->errno != 0){
				return json_encode(array(
					"error" => $this->db->error 
				));
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
						"error" => "Could not add family." 
					));
					break;
			};

			//get fam ID
			$family_id_query = "SELECT * FROM " . DB_NAME . "families WHERE name=?;";
			$family_id_statement = $this->db->prepare($family_id_query);
			if($family_id_statement){
				$family_id_statement->bind_param("s", $name);
				$family_id_statement->execute();
			}else{
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			if(this->db->errno != 0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			$family_id_result = $family_id_statement->get_result();
			$row  =$family_id_result->fetch_assoc();

			return json_encode(array(
					"order_id" => $row['order_id'],
					"name" => $name 
				));
		}

		function remove_family($family_id){
			//remove family with given ID
			$delete_query = "DELETE FROM " . DB_NAME . ".families WHERE family_id=?;";
			$delete_statement = $this->db->prepare($delete_query);
			if($delete_statement){
				$delete_statement->bind_param("i", $family_id);
				$delete_statement->execute();
			}else{
				return json_encode(
					array( "error" => $this->db->error )
				);
			}

			if($this->db->errno != 0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			switch ($delete_statement->affected_rows) {
				case -1:
					return json_encode(array(
						"error" => $this->db->error 
					));
					break;
				case 0:
					return json_encode(array(
						"error" => $this->db->error 
					));
					break;
			};
			return json_encode(array(
				"family_id" => $family_id;
			));
		}

		function add_species($name, $class_id, $order_id, $family_id, $size, $nutrition, $predators, $lifetime, $habitat, $lifestyle, $reproduction, $distribution){
			$add_species_query = "INSERT INTO " . DB_NAME . ".species ( `species_id`, `name`, `class_id`, `order_id`, `family_id`, `size`,  `nutrition`, `predators`, `lifetime`, `habitat`, `lifestyle`, `reproduction`, `distribution`) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);"
			$add_species_statement = $this->db->prepare($add_species_query);
			if($add_species_statement){
				$add_species_statement->bind_param("siiissssssss", $name, $class_id, $order_id, $family_id, $size, $nutrition, $predators, $lifetime, $habitat, $lifestyle, $reproduction, $distribution);
				$add_species_statement->execute();
			}else{
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			if($this->db->errno != 0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			switch($add_species_statement->affected_rows){
				case -1:
					return json_encode(array(
						"error" => $this->db->error 
					));
					break;
				case 0:
					return json_encode(array(
						"error" => "Could not add species." 
					));
					break;
			};

			//get species ID
			$species_id_query = "SELECT * FROM " . DB_NAME . ".species WHERE name=?;";
			$species_id_statement = $this->db->prepare($species_id_query);
			if($species_id_statement){
				$species_id_statement->bind_param("s", $name);
		     	$species_id_statement->execute();
			}
			else {
				return json_encode(array(
					"error" => $this->db->error
				));
			}

			$species_id_result = $species_id_statement->get_result();

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			$row = $species_id_result->fetch_assoc();

			return json_encode(array(
				"species_id" => $row['species_id'],
				"name" => $name,
				"class_id" => $class_id,
				"order_id" => $order_id,
				"family_id" => $family_id,
				"size" => $size,
				"nutrition" => $nutrition,
				"predators" => $predators,
				"lifetime" => $lifetime,
				"habitat" => $habitat,
				"lifestyle" => $lifestyle,
				"reproduction" => $reproduction,
				"distribution" => $distribution
			));
		}
		

		function remove_species($species_id){
			//remove species with given ID
			$delete_species_query = "DELETE FROM " . DB_NAME . ".species WHERE species_id=?;";
			$delete_species_statement = $this->db->prepare($delete_species_query);
			if($delete_species_statement){
				$delete_species_statement->bind_param("i", $species_id);
				$delete_species_statement->execute();
			}else{
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			if($this->db->errno != 0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			switch ($delete_species_statement->affected_rows) {
				case -1:
					return json_encode(array(
						"error" => $this->db->error 
					));
					break;
				case 0:
					return json_encode(array(
						"error" => $this->db->error 
					));
					break;
			};
			return json_encode(array(
				"species_id" => $species_id;
			));
		}

		function add_mammal($species_id, $name, $age, $sex, $birth_location, $arrival_date, $photo_path, $interesting_facts){

		}

		function remove_mammal($animal_id){

		}

		function register_visit($user_id, $species_id){

		}

		function get_visit_count($species_id){

		}

		function recommend_species($current_species_id){

		}

	}


	$database = new Database();

	if($_POST['action']==="register_user"){
		//echo $database->register_user($_GET['username'], $_GET['password'], $_GET['first_last_name'], $_GET['year_of_birth'], $_GET['city'], $_GET['email'], 1);
		echo $database->register_user($_POST['username'], $_POST['password'], $_POST['first_last_name'], $_POST['year_of_birth'], $_POST['city'], $_POST['email'], 1);
	}
	else if($_POST['action']==="delete_user"){
		echo $database->delete_user($_POST['user_id']);
	}
	else if($_POST['action']==="add_class"){
		echo $database->add_class($_POST['name']);
	}
	else if($_POST['action']==="remove_class"){
		echo $database->remove_class($_POST['class_id']);
	}
	else if($_POST['action']==="add_order"){
		echo $database->add_order($_POST['name']);
	}

	

?>