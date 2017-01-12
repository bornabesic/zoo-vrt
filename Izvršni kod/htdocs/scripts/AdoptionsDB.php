<?php
	
	require_once (__DIR__ . "/config.php");
	
	class AdoptionsDB{

		private $db;

		public function __construct($database){
			$this->db=$database;
		}

		// Metoda za registraciju posvojenja ( korisnik posvaja jedinku )
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

			require_once(__DIR__ . "/Mail.php");
			send_donation_mail($email, $first_last_name, $city);

			return json_encode(array(
				"user_id" => $user_id,
				"animal_id" => $animal_id
			));
		}

		// Metoda za dohvat posvojenih jedinki određenog korisnika
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

	}

?>