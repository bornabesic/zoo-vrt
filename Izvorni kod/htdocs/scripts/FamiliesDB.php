<?php

	require_once (__DIR__ . "/config.php");

	class FamiliesDB{

		private $db;

		public function __construct($database){
			$this->db=$database;
		}

		// Metoda za registraciju nove životinjske porodice
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

		// Metoda za brisanje životinjske porodice
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

		// Metoda za dohvat svih životinjskih porodica
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

		// Metoda za ažuriranje životinjske porodice
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

	}

?>