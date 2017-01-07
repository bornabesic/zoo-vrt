<?php

	require_once (__DIR__ . "/config.php");

	class SpeciesDB{

		private $db;

		public function __construct($database){
			$this->db=$database;
		}

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

	}

?>