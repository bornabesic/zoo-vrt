<?php
	
	define("DB_USERNAME", "zoo_vrt");
	define("DB_PASSWORD", "rzjesmece");
	define("DB_NAME", "zoo_vrt");
	define("DB_SERVER", "localhost");
	define("NUM_RECOMMENDATIONS", 3);

	function log_to_file($msg) {
		$file = './Database_log.txt';
		file_put_contents($file, $msg . "\r\n", LOCK_EX | FILE_APPEND);
	}

	class Database {

		private $db;

		public function __construct(){
			$this->db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
			if(!$this->db) echo json_encode(
							array( "error" => $this->db->error )
						);
			$this->db->set_charset('utf8');
		}

		//USERS
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
				"email" => $email,
				"role" => $role
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

		function login_user($username, $password){
			require_once("./phpass/PasswordHash.php");
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

		function update_user($username, $password, $first_last_name, $year_of_birth, $city, $email, $role, $user_id){
			require_once("./phpass/PasswordHash.php");
			$hasher = new PasswordHash(8, false);
			$password_hash = $hasher->HashPassword($password);

			// UPDATE USER WITH GIVEN ID
			$update_query="UPDATE ". DB_NAME . ".users SET `username`=?, `password_hash`=?, `first_last_name`=?, `year_of_birth`=?, `city`=?, `email`=?, `role`=? WHERE `user_id`=?;";
			$update_statement=$this->db->prepare($update_query);
			if($update_statement){
				$update_statement->bind_param("sssissii", $username, $password_hash, $first_last_name, $year_of_birth, $city, $email, $role, $user_id);
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

		//CLASSES
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

		function get_classes(){
			$classes_query = "SELECT * FROM " . DB_NAME . ".classes;";
			$classes_statement = $this->db->prepare($classes_query);
			if($classes_statement){
				//$users_statement->bind_param("s", $username);
		     	$classes_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			$classes_result = $classes_statement->get_result();

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			$classes = array();
			while ($row = $classes_result->fetch_assoc()) {
				array_push($classes, array(
					"class_id" => $row["class_id"],
					"name" => $row["name"]
				));
    		}

			return json_encode($classes);
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

		function update_class($class_id, $name){
			$update_query="UPDATE ". DB_NAME . ".classes SET `name`=? WHERE `class_id`=?;";
			$update_statement=$this->db->prepare($update_query);
			if($update_statement){
				$update_statement->bind_param("si", $name, $class_id);
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
						"error" => "Class with the given ID could not be updated." 
					));
					break;
			};

			return json_encode(array(
				"class_id" => $class_id
			));
		}

		//ORDERS
		function add_order($name, $parent_class_id){
			//ADD ORDER
			$add_order_query = "INSERT INTO " . DB_NAME . ".orders (`name`, `parent_class_id`) VALUES (?,?);";
			$add_order_statement = $this->db->prepare($add_order_query);
			if($add_order_statement){
				$add_order_statement->bind_param("si", $name, $parent_class_id);
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
				"name" => $name,
				"parent_class_id" => $row['parent_class_id']
			));
		}

		function remove_order($order_id){
			//remove order with given id
			$delete_query = "DELETE FROM " . DB_NAME . ".orders WHERE order_id=?;";
			$delete_statement = $this->db->prepare($delete_query);
			if($delete_statement){
				$delete_statement->bind_param("i", $order_id);
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
				"order_id" => $order_id
			));
		}

		function get_orders($parent_class_id){
			if($parent_class_id>=0) $condition=" WHERE parent_class_id=?";
			else $condition="";

			$orders_query = "SELECT * FROM " . DB_NAME . ".orders" . $condition . ";";
			$orders_statement = $this->db->prepare($orders_query);
			if($orders_statement){
				if($parent_class_id>=0) $orders_statement->bind_param("i", $parent_class_id);
		     	$orders_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			$orders_result = $orders_statement->get_result();

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			$orders = array();
			while ($row = $orders_result->fetch_assoc()) {
				array_push($orders, array(
					"order_id" => $row["order_id"],
					"name" => $row["name"],
					"parent_class_id" => $row["parent_class_id"]
				));
    		}

			return json_encode($orders);
		}

		function update_order($order_id, $name, $parent_class_id){
			$update_query="UPDATE ". DB_NAME . ".orders SET `name`=?, `parent_class_id`=? WHERE `order_id`=?;";
			$update_statement=$this->db->prepare($update_query);
			if($update_statement){
				$update_statement->bind_param("sii", $name, $parent_class_id, $order_id);
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
						"error" => "Order with the given ID could not be updated." 
					));
					break;
			};

			return json_encode(array(
				"order_id" => $order_id
			));
		}

		//FAMILIES
		function add_family($name, $parent_order_id){
			$add_family_query = "INSERT INTO " . DB_NAME . ".families (`name`, `parent_order_id`) VALUES (?, ?);";
			$add_family_statement = $this->db->prepare($add_family_query);
			if($add_family_statement){
				$add_family_statement->bind_param("si", $name, $parent_order_id);
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

			switch($add_family_statement->affected_rows){
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
			$family_id_query = "SELECT * FROM " . DB_NAME . ".families WHERE name=?;";
			$family_id_statement = $this->db->prepare($family_id_query);
			if($family_id_statement){
				$family_id_statement->bind_param("s", $name);
				$family_id_statement->execute();
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

			$family_id_result = $family_id_statement->get_result();
			$row  =$family_id_result->fetch_assoc();

			return json_encode(array(
					"family_id" => $row['family_id'],
					"name" => $name,
					"parent_order_id" => $row['parent_order_id']
				));
		} /*greska*/

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
				"family_id" => $family_id
			));
		}

		function get_families($parent_order_id){
			if($parent_order_id>=0) $condition=" WHERE parent_order_id=?";
			else $condition="";

			$families_query = "SELECT * FROM " . DB_NAME . ".families" . $condition . ";";
			$families_statement = $this->db->prepare($families_query);
			if($families_statement){
				if($parent_order_id>=0) $families_statement->bind_param("i", $parent_order_id);
		     	$families_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			$families_result = $families_statement->get_result();

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			$families = array();
			while ($row = $families_result->fetch_assoc()) {
				array_push($families, array(
					"family_id" => $row["family_id"],
					"name" => $row["name"],
					"parent_order_id" => $row["parent_order_id"]
				));
    		}

			return json_encode($families);
		}

		function update_family($family_id, $name, $parent_order_id){
			$update_query="UPDATE ". DB_NAME . ".families SET `name`=?, `parent_order_id`=? WHERE `family_id`=?;";
			$update_statement=$this->db->prepare($update_query);
			if($update_statement){
				$update_statement->bind_param("sii", $name, $parent_order_id, $family_id);
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
						"error" => "Order with the given ID could not be updated." 
					));
					break;
			};

			return json_encode(array(
				"family_id" => $family_id
			));
		}


		//SPECIES
		function add_species($name, $family_id, $size, $nutrition, $predators, $lifetime, $habitat, $lifestyle, $reproduction, $distribution, $location_x, $location_y){
			$add_species_query = "INSERT INTO " . DB_NAME . ".species ( `species_id`, `name`, `family_id`, `size`,  `nutrition`, `predators`, `lifetime`, `habitat`, `lifestyle`, `reproduction`, `distribution`, `location_x`, `location_y`) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
			$add_species_statement = $this->db->prepare($add_species_query);
			if($add_species_statement){
				$add_species_statement->bind_param("sissssssssii", $name, $family_id, $size, $nutrition, $predators, $lifetime, $habitat, $lifestyle, $reproduction, $distribution, $location_x, $location_y);
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
				"family_id" => $family_id,
				"size" => $size,
				"nutrition" => $nutrition,
				"predators" => $predators,
				"lifetime" => $lifetime,
				"habitat" => $habitat,
				"lifestyle" => $lifestyle,
				"reproduction" => $reproduction,
				"distribution" => $distribution,
				"location_x" => $location_x,
				"location_y" => $location_y
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
				"species_id" => $species_id
			));
		}

		function update_species($species_id, $name, $class_id, $order_id, $family_id, $size, $nutrition, $predators, $lifetime, $habitat, $lifestyle, $reproduction, $distribution){

		}

		function get_species($parent_family_id){
			if($parent_family_id>=0) $condition=" WHERE parent_family_id=?";
			else $condition="";

			$species_query = "SELECT * FROM " . DB_NAME . ".species" . $condition . ";";
			$species_statement = $this->db->prepare($species_query);
			if($species_statement){
				if($parent_family_id>=0) $species_statement->bind_param("i", $parent_family_id);
		     	$species_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			$species_result = $species_statement->get_result();

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			$species = array();
			while ($row = $species_result->fetch_assoc()) {
				array_push($species, array(
					"species_id" => $row['species_id'],
					"name" => $row['name'],
					"family_id" => $row['family_id'],
					"size" => $row['size'],
					"nutrition" => $row['nutrition'],
					"predators" => $row['predators'],
					"lifetime" => $row['lifetime'],
					"habitat" => $row['habitat'],
					"lifestyle" => $row['lifestyle'],
					"reproduction" => $row['reproduction'],
					"distribution" => $row['distribution'],
					"location_x" => $row['location_x'],
					"location_y" => $row['location_y']
				));
    		}

			return json_encode($species);
		}

		//MAMMALS
		function add_mammal($species_id, $name, $age, $sex, $birth_location, $arrival_date, $photo_path, $interesting_facts){
			//ADD MAMMAL
			$add_mammal_query = "INSERT INTO " . DB_NAME . ".mammal_animals (`species_id`, `name`, `age`, `sex`, `birth_location`, `arrival_date`, `photo_path`, `interesting_facts`) VALUES (?,?,?,?,?,?,?,?);";
			$add_mammal_statement = $this->db->prepare($add_mammal_query);
			if($add_mammal_statement){
				$add_mammal_statement->bind_param("isiissss", $species_id, $name, $age, $sex, $birth_location, $arrival_date, $photo_path, $interesting_facts);
		     	$add_mammal_statement->execute();
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

			// GET ANIMAL ID
			$animal_id_query = "SELECT * FROM " . DB_NAME . ".mammal_animals WHERE name=?;";
			$animal_id_statement = $this->db->prepare($animal_id_query);
			if($animal_id_statement){
				$animal_id_statement->bind_param("s", $name);
		     	$animal_id_statement->execute();
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

			$animal_id_result = $animal_id_statement->get_result();
			$row = $animal_id_result->fetch_assoc();

			return json_encode(array(
				"animal_id" => $row['animal_id'],
				"species_id" => $species_id,
				"name" => $name,
				"age" => $age,
				"sex" => $sex,
				"birth_location" => $birth_location,
				"arrival_date" => $arrival_date,
				"photo_path" => $photo_path,
				"interesting_facts" => $interesting_facts
			));

		}

		function remove_mammal($animal_id){
			//REMOVE MAMMAL WITH GIVEN ID
			$delete_query="DELETE FROM ". DB_NAME . ".mammal_animals WHERE animal_id=?;";
			$delete_statement=$this->db->prepare($delete_query);
			if($delete_statement){
				$delete_statement->bind_param("i", $animal_id);
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
						"error" => "Animal with the given ID could not be deleted." 
					));
					break;
			};

			return json_encode(array(
				"animal_id" => $animal_id
			));
		}

		//VISITS & STATISTICS
		function register_visit($user_id, $species_id){
			//REGISTER USER`S VISIT TO SPECIES
			$visit_query="INSERT INTO ". DB_NAME . ".visits (`user_id`, `species_id`) VALUES (?,?);";
			$visit_statement = $this->db->prepare($visit_query);
			if($visit_statement){
				$visit_statement->bind_param("ii", $user_id, $species_id);
		     	$visit_statement->execute();
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

			return json_encode(array(
				"user_id" => $user_id,
				"species_id" => $species_id
			));
		}

		function get_visit_count($species_id){
			//GET COUNT OF SPECIES' VISITS
			$count_query="SELECT COUNT(user_id) as count FROM " . DB_NAME . ".visits WHERE species_id=?;";
			$count_statement = $this->db->prepare($count_query);
			if($count_statement){
				$count_statement->bind_param("i", $species_id);
		     	$count_statement->execute();
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

			$count_result = $count_statement->get_result();
			$row = $count_result->fetch_assoc();


			return json_encode(array(
				"count" => $row['count'],
				"species_id" => $species_id
			));
		}

		function recommend_species($current_species_id, $user_id){
			$recommend_query = "SELECT species_id FROM " . DB_NAME . ".species WHERE species_id NOT IN (SELECT species_id FROM " . DB_NAME . ".visits WHERE user_id= ? ) AND species_id != ? LIMIT " . NUM_RECOMMENDATIONS . ";";
			$recommend_statement = $this->db->prepare($recommend_query);
			if($recommend_statement){
				$recommend_statement->bind_param("ii", $user_id, $current_species_id);
				$recommend_statement->execute();
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
			$recommend_result = $recommend_statement->get_result();
			$rows = array();
			
			while($r = $recommend_result->fetch_assoc()){
				$rows[] = $r['species_id'];		
			}

			if(count($rows) < NUM_RECOMMENDATIONS){
				$fill_missing_query = "SELECT species_id FROM " . DB_NAME . ".species WHERE species_id != ? LIMIT ?;";
				$fill_missing_statement = $this->db->prepare($fill_missing_query);
				if($fill_missing_statement){
					$num_missing = NUM_RECOMMENDATIONS-count($rows);
					$fill_missing_statement->bind_param("ii", $current_species_id, $num_missing);
					$fill_missing_statement->execute();
				}
				else {return json_encode(array( "error" => $this->db->error ));
				}

				if($this->db->errno!=0){return json_encode(array("error" => $this->db->error));
				}
				
				$fill_missing_result = $fill_missing_statement->get_result();
			
				while($r = $fill_missing_result->fetch_assoc()){
					$rows[] = $r['species_id'];		
				}
			}
			return json_encode($rows);
		}

	}


	/* 
		kod update metoda affected_rows bude 0 ako se ništa ne mijenja pa javlja gresku, a ne bi trebala bit
	*/
	$database = new Database();

	//USERS
	if($_POST['action']==="register_user"){
		//echo $database->register_user($_GET['username'], $_GET['password'], $_GET['first_last_name'], $_GET['year_of_birth'], $_GET['city'], $_GET['email'], 1);
		echo $database->register_user($_POST['username'], $_POST['password'], $_POST['first_last_name'], $_POST['year_of_birth'], $_POST['city'], $_POST['email'], $_POST['role']);
	}
	else if($_POST['action']==="login_user"){
		echo $database->login_user($_POST['username'], $_POST['password']);
	}
	else if($_POST['action']==="delete_user"){
		echo $database->delete_user($_POST['user_id']);
	}
	else if($_POST['action']==="update_user"){
		echo $database->update_user($_POST['username'], $_POST['password'], $_POST['first_last_name'], $_POST['year_of_birth'], $_POST['city'], $_POST['email'], $_POST['role'], $_POST['user_id']);
	}
	else if($_POST['action']==="get_users"){
		echo $database->get_users();
	}

	//CLASSES
	else if($_POST['action']==="add_class"){
		echo $database->add_class($_POST['name']);
	}
	else if($_POST['action']==="remove_class"){
		echo $database->remove_class($_POST['class_id']);
	}
	else if($_POST['action']==="update_class"){
		echo $database->update_class($_POST['class_id'], $_POST['name']);
	}
	else if($_POST['action']==="get_classes"){
		echo $database->get_classes();
	}

	//ORDERS
	else if($_POST['action']==="add_order"){
		echo $database->add_order($_POST['name'], $_POST['parent_class_id']);
	}
	else if($_POST['action']==="remove_order"){
		echo $database->remove_order($_POST['order_id']);
	}
	else if($_POST['action']==="update_order"){
		echo $database->update_order($_POST['order_id'], $_POST['name'], $_POST['parent_class_id']);
	}
	else if($_POST['action']==="get_orders"){
		if(isset($_POST['parent_class_id'])) $parent_class_id=$_POST['parent_class_id'];
		else $parent_class_id=-1;

		echo $database->get_orders($parent_class_id);
	}

	//FAMILIES
	else if($_POST['action']==="add_family"){
		echo $database->add_family($_POST['name'], $_POST['parent_order_id']);
	}
	else if($_POST['action']==="remove_family"){
		echo $database->remove_family($_POST['family_id']);
	}
	else if($_POST['action']==="update_family"){
		echo $database->update_family($_POST['family_id'], $_POST['name'], $_POST['parent_order_id']);
	}
	else if($_POST['action']==="get_families"){
		if(isset($_POST['parent_order_id'])) $parent_order_id=$_POST['parent_order_id'];
		else $parent_order_id=-1;

		echo $database->get_families($parent_order_id);
	}

	//SPECIES
	else if($_POST['action']==="add_species"){
		echo $database->add_species($_POST['name'], $_POST['family_id'], $_POST['size'], $_POST['nutrition'], $_POST['predators'], $_POST['lifetime'], $_POST['habitat'], $_POST['lifestyle'], $_POST['reproduction'], $_POST['distribution'], $_POST['location_x'], $_POST['location_y']);
	}
	else if($_POST['action']==="remove_species"){
		echo $database->remove_species($_POST['species_id']);
	}
	else if($_POST['action']==="update_species"){
		echo $database->add_species($_POST['species_id'], $_POST['name'], $_POST['family_id'], $_POST['size'], $_POST['nutrition'], $_POST['predators'], $_POST['lifetime'], $_POST['habitat'], $_POST['lifestyle'], $_POST['reproduction'], $_POST['distribution']);
	}
	else if($_POST['action']==="get_species"){
		if(isset($_POST['parent_family_id'])) $parent_family_id=$_POST['parent_family_id'];
		else $parent_family_id=-1;

		echo $database->get_species($parent_family_id);
	}

	//MAMMALS
	else if($_POST['action']==="add_mammal"){
		echo $database->add_mammal($_POST['species_id'], $_POST['name'], $_POST['age'], $_POST['sex'], $_POST['birth_location'], $_POST['arrival_date'], $_POST['photo_path'], $_POST['interesting_facts']);
		//echo $database->add_mammal($_GET['species_id'], $_GET['name'], $_GET['age'], $_GET['sex'], $_GET['birth_location'], $_GET['arrival_date'], $_GET['photo_path'], $_GET['interesting_facts']);
	}
	else if($_POST['action']==="remove_mammal"){
		echo $database->remove_mammal($_POST['animal_id']);
	}
	else if($_POST['action']==="register_visit"){
		echo $database->register_visit($_POST['user_id'], $_POST['species_id']);
	}
	else if($_POST['action']==="get_visit_count"){
		echo $database->get_visit_count($_POST['species_id']);
	}
	else if($_POST['action']==="recommend_species"){
		//echo $database->recommend_species($_GET['current_species_id'], $_GET['user_id']);
		echo $database->recommend_species($_POST['current_species_id'], $_POST['user_id']);
	}
	

?>