<?php

	require_once (__DIR__ . "/config.php");

	class GuardsDB{

		private $db;

		public function __construct($database){
			$this->db=$database;
		}

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

	}

?>