<?php

	require_once (__DIR__ . "/config.php");

	class ExclusiveContentDB{

		private $db;

		public function __construct($database){
			$this->db=$database;
		}

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

	}

?>