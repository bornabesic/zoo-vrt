<?php
	
	//echo json_encode(array( "error" => "Kuki"));

	define("DB_USERNAME", "zoo_vrt");
	define("DB_PASSWORD", "rzjesmece");
	define("DB_NAME", "zoo_vrt");

	class Database {

		private $db;

		public function __construct(){
			$this->db = mysqli_connect('localhost', DB_USERNAME, DB_PASSWORD);
			if(!$this->db){
				return json_encode(
							array( "error" => $this->db->error )
						);
			}
			//$this->db->set_charset('utf8');
		}

		public function register_user($username, $password, $first_last_name, $year_of_birth, $city, $email, $role){
			require_once("./phpass/PasswordHash.php");
			$hasher = new PasswordHash(8, false);
			$password_hash = $hasher->HashPassword($password);

			$registration_query = "INSERT INTO " . DB_NAME . ".users (`user_id`, `username`, `password_hash`, `first_last_name`, `year_of_birth`, `city`, `email`, `role`) VALUES ('', ?, ?, ?, ?, ?, ?, ?);";
			//echo $registration_query;
			$registration_statement = $this->db->prepare($registration_query);
			if($registration_statement){
				$registration_statement->bind_param("sssssss", $username, $password_hash, $first_last_name, $year_of_birth, $city, $email, $role);
		     	$registration_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			$registration_result = $registration_statement->get_result();

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			return json_encode(array(
				"username" => $username,
				"first_last_name" => $first_last_name,
				"year_of_birth" => $year_of_birth,
				"city" => $city,
				"email" => $email
			));
		}

		function delete_user($user_id){

		}

		function add_class($name){

		}

		function remove_class($class_id){

		}

		function add_order($name){

		}

		function remove_order($order_id){

		}

		function add_family($name){

		}

		function remove_family($family_id){

		}

		function add_species($name, $class_id, $order_id, $family_id, $size, $nutrition, $predators, $lifetime, $habitat, $lifestyle, $reproduction, $distribution){

		}

		function remove_species($species_id){

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

	//echo $database->register_user($_GET['username'], $_GET['password'], $_GET['first_last_name'], $_GET['year_of_birth'], $_GET['city'], $_GET['email'], 1);
	echo $database->register_user($_POST['username'], $_POST['password'], $_POST['first_last_name'], $_POST['year_of_birth'], $_POST['city'], $_POST['email'], 1);
?>