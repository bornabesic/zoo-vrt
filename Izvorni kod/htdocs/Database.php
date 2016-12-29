<?php
	define("DB_USERNAME", "zoo_vrt");
	define("DB_PASSWORD", "rzjesmece");
	define("DB_NAME", "zoo_vrt");
	define("DB_SERVER", "localhost");
	define("NUM_RECOMMENDATIONS", 3);

	$media_dir="/media/";

	session_start();
	if(!isset($_SESSION["logged_in"])){
		$_SESSION["logged_in"]=false;
		$_SESSION["role"]=0;
	}

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

		function logout_user(){
			$_SESSION["logged_in"]=false;
			$_SESSION["role"]=0;
			return json_encode(array("status" => "Successfully logged out."));
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

		//HIERARCHY
		function get_species_hierarchy($species_id){
			$this->db->select_db(DB_NAME);

			$hierarchy_query =
			"SELECT families.family_id, families.name AS family_name, orders.order_id, orders.name AS order_name, classes.class_id, classes.name AS class_name
			FROM families
			LEFT JOIN orders ON families.parent_order_id=orders.order_id
			LEFT JOIN classes ON orders.parent_class_id=classes.class_id
			LEFT JOIN species ON species.family_id=families.family_id
			WHERE species.species_id=?;";

			$hierarchy_statement = $this->db->prepare($hierarchy_query);
			if($hierarchy_statement){
				$hierarchy_statement->bind_param("i", $species_id);
		     	$hierarchy_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			$hierarchy_result = $hierarchy_statement->get_result();

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			$row = $hierarchy_result->fetch_assoc();

			
			

			return json_encode(array(
				"family_id" => $row["family_id"],
				"family_name" => $row["family_name"],
				"order_id" => $row["order_id"],
				"order_name" => $row["order_name"],
				"class_id" => $row["class_id"],
				"class_name" => $row["class_name"]
			));
		}


		//SPECIES
		function add_species($name, $family_id, $size, $nutrition, $predators, $lifetime, $habitat, $lifestyle, $reproduction, $distribution, $location_x, $location_y, $photo_path){
			$add_species_query = "INSERT INTO " . DB_NAME . ".species ( `species_id`, `name`, `family_id`, `size`,  `nutrition`, `predators`, `lifetime`, `habitat`, `lifestyle`, `reproduction`, `distribution`, `location_x`, `location_y`, `photo_path`) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
			$add_species_statement = $this->db->prepare($add_species_query);
			if($add_species_statement){
				$add_species_statement->bind_param("sissssssssiis", $name, $family_id, $size, $nutrition, $predators, $lifetime, $habitat, $lifestyle, $reproduction, $distribution, $location_x, $location_y, $photo_path);
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
				"location_y" => $location_y,
				"photo_path" => $photo_path
			));
		}
		

		function remove_species($species_id){
			//get the photo path so it could be deleted
			$photo_query = "SELECT * FROM " . DB_NAME . ".species WHERE species_id=?;";
			$photo_statement = $this->db->prepare($photo_query);
			if($photo_statement){
				$photo_statement->bind_param("i", $species_id);
		     	$photo_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			$photo_result = $photo_statement->get_result();

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			$row = $photo_result->fetch_assoc();
			unlink("." . $row["photo_path"]);


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

		function update_species($species_id, $name, $family_id, $size, $nutrition, $predators, $lifetime, $habitat, $lifestyle, $reproduction, $distribution, $location_x, $location_y, $photo_path){
			$update_query="UPDATE ". DB_NAME . ".species SET `name`=?, `family_id`=?, `size`=?, `nutrition`=?, `predators`=?, `lifetime`=?, `habitat`=?, `lifestyle`=?, `reproduction`=?, `distribution`=?, `location_x`=?, `location_y`=?, `photo_path`=? WHERE `species_id`=?;";
			$update_statement=$this->db->prepare($update_query);
			if($update_statement){
				$update_statement->bind_param("sissssssssiisi", $name, $family_id, $size, $nutrition, $predators, $lifetime, $habitat, $lifestyle, $reproduction, $distribution, $location_x, $location_y, $photo_path, $species_id);
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
						"error" => "Species with the given ID could not be updated." 
					));
					break;
			};

			return json_encode(array(
				"species_id" => $species_id
			));
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
					"location_y" => $row['location_y'],
					"photo_path" => $row['photo_path']
				));
    		}

			return json_encode($species);
		}

		/* dob kod jedinke treba promijeniti tako da jedinka ima datum rodenja umjesto izravne dobi i onda se tek racuna dob koju backend vraca prema danasnjem datumu */
		//MAMMALS - 'mammal' je glup naziv, promijenit cemo u 'animal'
		function add_mammal($species_id, $name, $age, $sex, $birth_location, $arrival_date, $photo_path, $interesting_facts){
			//ADD MAMMAL
			$add_mammal_query = "INSERT INTO " . DB_NAME . ".mammal_animals (`species_id`, `name`, `age`, `sex`, `birth_location`, `arrival_date`, `photo_path`, `interesting_facts`) VALUES (?,?,?,?,?,?,?,?);";
			$add_mammal_statement = $this->db->prepare($add_mammal_query);
			if($add_mammal_statement){
				$add_mammal_statement->bind_param("isisssss", $species_id, $name, $age, $sex, $birth_location, $arrival_date, $photo_path, $interesting_facts);
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

		/* species_id je maknut od parametara */
		function update_animal($animal_id, $name, $age, $sex, $birth_location, $arrival_date, $photo_path, $interesting_facts){

			$update_query="UPDATE ". DB_NAME . ".mammal_animals SET `name`=?, `age`=?, `sex`=?, `birth_location`=?, `arrival_date`=?, `photo_path`=?, `interesting_facts`=? WHERE `animal_id`=?;";
			$update_statement=$this->db->prepare($update_query);

			if($update_statement){
				$update_statement->bind_param("sisssssi", $name, $age, $sex, $birth_location, $arrival_date, $photo_path, $interesting_facts, $animal_id);
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
						"error" => "Species with the given ID could not be updated." 
					));
					break;
			};

			return json_encode(array(
				"animal_id" => $animal_id
			));
		}

		function get_animals($species_id){ /* ako je species_id -1 onda treba dohvatiti pravi */
			if($species_id>=0) $condition=" WHERE species_id=?";
			else $condition="";

			$query = "SELECT * FROM " . DB_NAME . ".mammal_animals" . $condition . ";";
			$statement = $this->db->prepare($query);
			if($statement){
				if($species_id>=0) $statement->bind_param("i", $species_id);
				$statement->execute();
			}else{
				return json_encode(array("error" => $this->db->error));
			}

			if($this->db->errno!=0){
				return json_encode(array("error" => $this->db->error));
			}
			$result = $statement->get_result();
			$animals = array();
			while($row = $result->fetch_assoc()){
				array_push($animals, array(
					"animal_id" => $row['animal_id'],
					"species_id" => $row['species_id'],
					"name" => $row['name'],
					"age" => $row['age'],
					"sex" => $row['sex'],
					"birth_location" => $row['birth_location'],
					"arrival_date" => $row['arrival_date'],
					"photo_path" => $row['photo_path'],
					"interesting_facts" => $row['interesting_facts']
				));
			}
			return json_encode($animals);
		}

		// koristi li se ova funkcija ?
		function get_mammal($animal_id){
			$query = "SELECT * FROM " . DB_NAME . ".mammal_animals WHERE animal_id=?";
			$statement = $this->db->prepare($query);
			if($statement){
				$statement->bind_param("i", $animal_id);
				$statement->execute();
			}else {
				return json_encode(array("error" => $this->db->error));
			}

			if($this->db->errno!=0){
				return json_encode(array("error" => $this->db->error));
			}
			$result = $statement->get_result();
			$row = $result->fetch_assoc();
			if(is_null($row)){
				return json_encode(array());
			}else {
				return json_encode($row);
			}
		}


		// ADOPTION
		function adopt($user_id, $animal_id, $email, $first_last_name, $city){
			$adopt_query="INSERT INTO ". DB_NAME . ".adoptions (`visitor_id`, `animal_id`) VALUES (?,?);";
			$adopt_statement = $this->db->prepare($adopt_query);
			if($adopt_statement){
				$adopt_statement->bind_param("ii", $user_id, $animal_id);
		     	$adopt_statement->execute();
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

			require_once("./Mail.php");
			send_donation_mail($email, $first_last_name, $city);

			return json_encode(array(
				"user_id" => $user_id,
				"animal_id" => $animal_id
			));
		}

		function get_adopted($user_id){
			$this->db->select_db(DB_NAME);

			$query = "SELECT *
			FROM adoptions
			LEFT JOIN mammal_animals ON adoptions.animal_id=mammal_animals.animal_id
			WHERE visitor_id=?;";

			$statement = $this->db->prepare($query);
			if($statement){
				$statement->bind_param("i", $user_id);
				$statement->execute();
			}else{
				return json_encode(array("error" => $this->db->error));
			}

			if($this->db->errno!=0){
				return json_encode(array("error" => $this->db->error));
			}
			$result = $statement->get_result();
			$adopted = array();
			while($row = $result->fetch_assoc()){
				array_push($adopted, array(
					"animal_id" => $row["animal_id"],
					"species_id" => $row["species_id"],
					"name" => $row["name"], 
					"age" => $row["age"],
					"sex" => $row["sex"],
					"birth_location" => $row["birth_location"],
					"arrival_date" => $row["arrival_date"],
					"photo_path" => $row["photo_path"],
					"interesting_facts" => $row["interesting_facts"]
				));
			}

			return json_encode($adopted);
		}

		// --------------- NIJE TESTIRANO -------------------
		//GUARDS
		function assign_animal($user_id, $animal_id){
			$this->db->select_db(DB_NAME);

			$query="INSERT INTO guard_assigned_animals (`guard_id`, `animal_id`) VALUES (?, ?);";
			$statement = $this->db->prepare($query);
			if($statement){
				$statement->bind_param("ii", $user_id, $animal_id);
				$statement->execute();
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

			switch($statement->affected_rows){
				case -1:
					return json_encode(array(
						"error" => $this->db->error 
					));
					break;
				case 0:
					return json_encode(array(
						"error" => "Could not assign animal to the guard." 
					));
					break;
			};

			return json_encode(array("user_id" => $animal_id, "animal_id" => $animal_id));
		}

		function unassign_animal($user_id, $animal_id){
			$this->db->select_db(DB_NAME);

			//REMOVE MAMMAL WITH GIVEN ID
			$query="DELETE FROM guard_assigned_animals WHERE guard_id=? AND animal_id=?;";
			$statement=$this->db->prepare($query);
			if($statement){
				$statement->bind_param("ii", $user_id, $animal_id);
		     	$statement->execute();
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

			switch($statement->affected_rows){
				case -1:
					return json_encode(array(
						"error" => $this->db->error 
					));
					break;
				case 0:
					return json_encode(array(
						"error" => "Animal with the given ID could not be unassigned from the guard." 
					));
					break;
			};

			return json_encode(array(
				"user_id" => $user_id, 
				"animal_id" => $animal_id
			));
		}
		// --------------- NIJE TESTIRANO -------------------

		function get_assigned_animals($user_id){
			$this->db->select_db(DB_NAME);

			$query = "SELECT guard_assigned_animals.animal_id, mammal_animals.name AS animal_name, species.species_id, species.name AS species_name, age, sex, birth_location, arrival_date, mammal_animals.photo_path, interesting_facts FROM
			guard_assigned_animals
			LEFT JOIN mammal_animals ON  guard_assigned_animals.animal_id = mammal_animals.animal_id
			LEFT JOIN species ON mammal_animals.species_id=species.species_id
			WHERE guard_id=?";

			$statement = $this->db->prepare($query);
			if($statement){
				$statement->bind_param("i", $user_id);
				$statement->execute();
			}else{
				return json_encode(array("error" => $this->db->error));
			}

			if($this->db->errno!=0){
				return json_encode(array("error" => $this->db->error));
			}
			$result = $statement->get_result();

			$animals = array();
			while($row = $result->fetch_assoc()){
				array_push($animals, array(
					"animal_id" => $row['animal_id'],
					"animal_name" => $row['animal_name'],
					"species_id" => $row['species_id'],
					"species_name" => $row['species_name'],
					"age" => $row['age'],
					"sex" => $row['sex'],
					"birth_location" => $row['birth_location'],
					"arrival_date" => $row['arrival_date'],
					"photo_path" => $row['photo_path'],
					"interesting_facts" => $row['interesting_facts']
				));
			}
			return json_encode($animals);
		}

		//EXCLUSIVE CONTENT
		function add_exclusive_fact($animal_id, $fact){
			$this->db->select_db(DB_NAME);

			$query="INSERT INTO adopter_exclusive_facts (`animal_id`, `fact`) VALUES (?, ?);";
			$statement = $this->db->prepare($query);
			if($statement){
				$statement->bind_param("is", $animal_id, $fact);
				$statement->execute();
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

			switch($statement->affected_rows){
				case -1:
					return json_encode(array(
						"error" => $this->db->error 
					));
					break;
				case 0:
					return json_encode(array(
						"error" => "Exclusive fact could not be added." 
					));
					break;
			};

			return json_encode(array("animal_id" => $animal_id, "fact" => $fact));
		}

		function add_exclusive_photo($animal_id, $photo_path){
			$this->db->select_db(DB_NAME);

			$query="INSERT INTO adopter_exclusive_photos (`animal_id`, `photo_path`) VALUES (?, ?);";
			$statement = $this->db->prepare($query);
			if($statement){
				$statement->bind_param("is", $animal_id, $photo_path);
				$statement->execute();
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

			switch($statement->affected_rows){
				case -1:
					return json_encode(array(
						"error" => $this->db->error 
					));
					break;
				case 0:
					return json_encode(array(
						"error" => "Exclusive photo could not be added." 
					));
					break;
			};

			return json_encode(array("animal_id" => $animal_id, "photo_path" => $photo_path));
		}

		function add_exclusive_video($animal_id, $video_path){
			$this->db->select_db(DB_NAME);

			$query="INSERT INTO adopter_exclusive_videos (`animal_id`, `video_path`) VALUES (?, ?);";
			$statement = $this->db->prepare($query);
			if($statement){
				$statement->bind_param("is", $animal_id, $video_path);
				$statement->execute();
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

			switch($statement->affected_rows){
				case -1:
					return json_encode(array(
						"error" => $this->db->error 
					));
					break;
				case 0:
					return json_encode(array(
						"error" => "Exclusive video could not be added." 
					));
					break;
			};

			return json_encode(array("animal_id" => $animal_id, "video_path" => $video_path));
		}

		function get_exclusive_content($animal_id){
			$this->db->select_db(DB_NAME);

			// ------------ PHOTOS ---------------

			$query = "SELECT *
			FROM mammal_animals
			LEFT JOIN adopter_exclusive_photos ON mammal_animals.animal_id=adopter_exclusive_photos.animal_id
			WHERE mammal_animals.animal_id=?;";

			$statement = $this->db->prepare($query);
			if($statement){
				$statement->bind_param("i", $animal_id);
				$statement->execute();
			}else{
				return json_encode(array("error" => $this->db->error));
			}

			if($this->db->errno!=0){
				return json_encode(array("error" => $this->db->error));
			}

			$photos=array();
			$result = $statement->get_result();
			while($row = $result->fetch_assoc()){
				if($row['photo_path']!=null) array_push($photos, $row['photo_path']);
			}

			// ------------ VIDEOS ---------------

			$query = "SELECT *
			FROM mammal_animals
			LEFT JOIN adopter_exclusive_videos ON mammal_animals.animal_id=adopter_exclusive_videos.animal_id
			WHERE mammal_animals.animal_id=?;";

			$statement = $this->db->prepare($query);
			if($statement){
				$statement->bind_param("i", $animal_id);
				$statement->execute();
			}else{
				return json_encode(array("error" => $this->db->error));
			}

			if($this->db->errno!=0){
				return json_encode(array("error" => $this->db->error));
			}

			$videos=array();
			$result = $statement->get_result();
			while($row = $result->fetch_assoc()){
				if($row['video_path']!=null) array_push($videos, $row['video_path']);
			}


			// ------------ FACTS ---------------

			$query = "SELECT *
			FROM mammal_animals
			LEFT JOIN adopter_exclusive_facts ON mammal_animals.animal_id=adopter_exclusive_facts.animal_id
			WHERE mammal_animals.animal_id=?;";

			$statement = $this->db->prepare($query);
			if($statement){
				$statement->bind_param("i", $animal_id);
				$statement->execute();
			}else{
				return json_encode(array("error" => $this->db->error));
			}

			if($this->db->errno!=0){
				return json_encode(array("error" => $this->db->error));
			}

			$facts=array();
			$result = $statement->get_result();
			while($row = $result->fetch_assoc()){
				if($row['fact']!=null) array_push($facts, $row['fact']);
			}

			// ---------------------------

			if(count($photos)==0){
				$photos=null;
			}
			if(count($videos)==0){
				$videos=null;
			}
			if(count($facts)==0){
				$facts=null;
			}

			$content = array(
				"animal_id" => $animal_id,
				"photos" => $photos,
				"videos" => $videos,
				"facts" => $facts
			);

			return json_encode($content);

		}

		//VISITS & STATISTICS
		function register_visit($user_id, $species_id){
			//REGISTER USER`S VISIT TO SPECIES
			$visit_query="INSERT INTO ". DB_NAME . ".visits (`visitor_id`, `species_id`) VALUES (?,?);";
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

		function check_visit($user_id, $species_id){
			//REGISTER USER`S VISIT TO SPECIES
			$visit_query="SELECT COUNT(DISTINCT visitor_id) AS visited FROM " . DB_NAME . ".visits WHERE visitor_id=? AND species_id=?;";
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

			$visit_result = $visit_statement->get_result();
			$row = $visit_result->fetch_assoc();

			return json_encode(array(
				"visited" => $row["visited"]>0
			));
		}

		function get_visit_count($species_id){
			//GET COUNT OF SPECIES' VISITS
			$count_query="SELECT COUNT(*) as count FROM " . DB_NAME . ".visits WHERE species_id=?;";
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

	if($_POST['action']==="check_user_state"){
		echo json_encode(array(
			"logged_in" => $_SESSION["logged_in"],
			"is_visitor" =>  $_SESSION["role"]&1,
			"is_guard" =>  $_SESSION["role"]&2,
			"is_admin" =>  $_SESSION["role"]&4
		));
		exit();
	}

	if($_POST['action']==="login_user"){
		echo $database->login_user($_POST['username'], $_POST['password']);
		exit();
	}
	else if($_POST['action']==="logout_user"){
		echo $database->logout_user();
		exit();
	}
	else if($_POST['action']==="register_user"){
		echo $database->register_user($_POST['username'], $_POST['password'], $_POST['first_last_name'], $_POST['year_of_birth'], $_POST['city'], $_POST['email'], $_POST['role']);
		exit();
	}

	if (session_status() == PHP_SESSION_NONE || !$_SESSION["logged_in"]) { //nije ulogiran
		echo json_encode(array("error" => "User is not logged in."));
		exit();
	}

	// ------- OGRAĐENI POZIVI ------------

	//USERS
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

	//HIERARCHY
	else if($_POST['action']==="get_species_hierarchy"){
		echo $database->get_species_hierarchy($_POST['species_id']);
	}

	//SPECIES
	else if($_POST['action']==="add_species"){
		mkdir("." . $media_dir, 0700);
		$photo_path = $media_dir . basename($_FILES["photo"]["name"]);
		move_uploaded_file($_FILES["photo"]["tmp_name"], "." . $photo_path);


		echo $database->add_species($_POST['name'], $_POST['family_id'], $_POST['size'], $_POST['nutrition'], $_POST['predators'], $_POST['lifetime'], $_POST['habitat'], $_POST['lifestyle'], $_POST['reproduction'], $_POST['distribution'], $_POST['location_x'], $_POST['location_y'], $photo_path);
	}
	else if($_POST['action']==="remove_species"){
		echo $database->remove_species($_POST['species_id']);
	}
	else if($_POST['action']==="update_species"){
		mkdir("." . $media_dir, 0700);
		$photo_path = $media_dir . basename($_FILES["photo"]["name"]);
		move_uploaded_file($_FILES["photo"]["tmp_name"], "." . $photo_path);

		echo $database->update_species($_POST['species_id'], $_POST['name'], $_POST['family_id'], $_POST['size'], $_POST['nutrition'], $_POST['predators'], $_POST['lifetime'], $_POST['habitat'], $_POST['lifestyle'], $_POST['reproduction'], $_POST['distribution'], $_POST['location_x'], $_POST['location_y'], $photo_path);
	}
	else if($_POST['action']==="get_species"){
		if(isset($_POST['parent_family_id'])) $parent_family_id=$_POST['parent_family_id'];
		else $parent_family_id=-1;

		echo $database->get_species($parent_family_id);
	}

	//MAMMALS odnosno ANIMALS
	else if($_POST['action']==="add_mammal"){
		mkdir("." . $media_dir, 0700);
		$photo_path = $media_dir . basename($_FILES["photo"]["name"]);
		move_uploaded_file($_FILES["photo"]["tmp_name"], "." . $photo_path);

		echo $database->add_mammal($_POST['species_id'], $_POST['name'], $_POST['age'], $_POST['sex'], $_POST['birth_location'], $_POST['arrival_date'], $photo_path, $_POST['interesting_facts']);
	}
	else if($_POST['action']==="remove_mammal"){
		echo $database->remove_mammal($_POST['animal_id']);
	}
	else if($_POST['action']==="update_animal"){
		mkdir("." . $media_dir, 0700);
		$photo_path = $media_dir . basename($_FILES["photo"]["name"]);
		move_uploaded_file($_FILES["photo"]["tmp_name"], "." . $photo_path);

		echo $database->update_animal($_POST["animal_id"], $_POST["name"], $_POST["age"], $_POST["sex"], $_POST["birth_location"], $_POST["arrival_date"], $photo_path, $_POST["interesting_facts"]);
	}
	else if($_POST['action']==="get_animals"){
		if(isset($_POST['species_id'])) $species_id=$_POST['species_id'];
		else $species_id=-1;

		echo $database->get_animals($species_id);
	}
	else if($_POST['action']==="get_mammal"){
		echo $database->get_mammal($_POST['animal_id']);
	}

	//ADOPTION
	else if($_POST['action']==="adopt"){
		echo $database->adopt($_POST['user_id'], $_POST['animal_id'], $_POST['email'], $_POST['first_last_name'], $_POST['city']);
	}
	else if($_POST['action']==="get_adopted"){
		echo $database->get_adopted($_POST['user_id']);
	}

	//GUARDS
	else if($_POST['action']==="assign_animal"){
		echo $database->assign_animal($_POST['user_id'], $_POST['animal_id']);
	}
	else if($_POST['action']==="unassign_animal"){
		echo $database->unassign_animal($_POST['user_id'], $_POST['animal_id']);
	}
	else if($_POST['action']==="get_assigned_animals"){
		echo $database->get_assigned_animals($_POST['user_id']);
	}

	//EXCLUSIVE CONTENT
	else if($_POST['action']==="add_exclusive_fact"){
		echo $database->add_exclusive_fact($_POST['animal_id'], $_POST['fact']);
	}
	else if($_POST['action']==="add_exclusive_photo"){
		mkdir("." . $media_dir, 0700);
		$photo_path = $media_dir . basename($_FILES["photo"]["name"]);
		move_uploaded_file($_FILES["photo"]["tmp_name"], "." . $photo_path);
		
		echo $database->add_exclusive_photo($_POST['animal_id'], $photo_path);
	}
	else if($_POST['action']==="add_exclusive_video"){
		mkdir("." . $media_dir, 0700);
		$video_path = $media_dir . basename($_FILES["video"]["name"]);
		move_uploaded_file($_FILES["video"]["tmp_name"], "." . $video_path);

		echo $database->add_exclusive_video($_POST['animal_id'], $video_path);
	}
	else if($_POST['action']==="get_exclusive_content"){
		echo $database->get_exclusive_content($_POST['animal_id']);
	}

	//VISITS & STATISTICS
	else if($_POST['action']==="register_visit"){
		echo $database->register_visit($_POST["user_id"], $_POST["species_id"]);
	}
	else if($_POST['action']==="check_visit"){
		echo $database->check_visit($_POST["user_id"], $_POST["species_id"]);
	}
	else if($_POST['action']==="get_visit_count"){
		echo $database->get_visit_count($_POST['species_id']);
	}
	else if($_POST['action']==="recommend_species"){
		//echo $database->recommend_species($_GET['current_species_id'], $_GET['user_id']);
		echo $database->recommend_species($_POST['current_species_id'], $_POST['user_id']);
	}
	

?>