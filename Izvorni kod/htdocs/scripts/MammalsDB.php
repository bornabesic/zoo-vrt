<?php

	require_once (__DIR__ . "/config.php");

	class MammalsDB{

		private $db;

		public function __construct($database){
			$this->db=$database;
		}

		// Metoda za registraciju nove jedinke
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

		// Metoda za brisanje jedinke
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

		// Metoda za ažuriranje jedinke
		function update_animal($name, $age, $sex, $birth_location, $arrival_date, $photo_path, $interesting_facts, $animal_id){
			
			$bind_params=array("name", "age", "sex", "birth_location", "arrival_date", "photo_path", "interesting_facts", "animal_id");
			$bind_values=array($name, $age, $sex, $birth_location, $arrival_date, $photo_path, $interesting_facts, $animal_id);
			$bind_types=array("s", "i", "s", "s", "s", "s", "s", "i");

			$query="UPDATE ". DB_NAME . ".mammal_animals SET ";

			$type_string="";
			for($i=0; $i<count($bind_params); $i++){
				if($bind_values[$i]!=null){ // povezi sve osim animal_id
					$type_string.=$bind_types[$i];

					if($i!=count($bind_params)-1)
						$query.=$bind_params[$i] . "=?,";
				}
			}
			$query = substr($query, 0, -1);
			$query .= " WHERE `animal_id`=?;";

			$a_params[] = & $type_string;
			for($i=0; $i<count($bind_params); $i++){
				if($bind_values[$i]!=null){
					$a_params[]=& $bind_values[$i];
				}
			}



			//OLD $update_query="UPDATE ". DB_NAME . ".mammal_animals SET `name`=?, `age`=?, `sex`=?, `birth_location`=?, `arrival_date`=?, `photo_path`=?, `interesting_facts`=? WHERE `animal_id`=?;";
			$update_statement=$this->db->prepare($query);

			if($update_statement){
				call_user_func_array(array($update_statement, "bind_param"), $a_params);
				//OLD $update_statement->bind_param("sisssssi", $name, $age, $sex, $birth_location, $arrival_date, $photo_path, $interesting_facts, $animal_id);
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

		// Metoda za dohvat svih jedinki
		function get_animals($species_id){
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

		// Metoda za dohvat jedinke po ID-u
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

	}

?>