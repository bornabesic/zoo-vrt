<?php

	require_once (__DIR__ . "/config.php");

	class ClassesDB{

		private $db;

		public function __construct($database){
			$this->db=$database;
		}

		// Metoda za registraciju novog životinjskog razreda
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

		// Metoda za dohvat svih životinjskih razreda
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

		// Metoda za brisanje životinjskog razreda
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

		// Metoda za ažuriranje životinjskog razreda
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

	}

?>

