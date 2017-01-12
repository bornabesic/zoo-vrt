<?php
	
	require_once (__DIR__ . "/config.php");

	class VisitsDB{

		private $db;

		public function __construct($database){
			$this->db=$database;
		}

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

?>