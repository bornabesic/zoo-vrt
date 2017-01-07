<?php

	require_once (__DIR__ . "/scripts/config.php");

	$media_dir="/media/";

	function log_to_file($msg) {
		$file = './Database_log.txt';
		file_put_contents($file, $msg . "\r\n", LOCK_EX | FILE_APPEND);
	}

	// -----------------------

	session_start();
	if(!isset($_SESSION["logged_in"])){
		$_SESSION["logged_in"]=false;
		$_SESSION["role"]=0;
	}

	$db_connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
	if(!$db_connection) echo json_encode(
		array( "error" => $db_connection->error )
	);
	$db_connection->set_charset('utf8');


	if(!isset($_POST['action'])){
		echo json_encode(array('error' => "No action defined."));
		exit();
	}
		

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
		require_once(__DIR__ . "/scripts/UsersDB.php");
		$database = new UsersDB($db_connection);

		echo $database->login_user($_POST['username'], $_POST['password']);
		exit();
	}
	else if($_POST['action']==="logout_user"){
		require_once(__DIR__ . "/scripts/UsersDB.php");
		$database = new UsersDB($db_connection);

		echo $database->logout_user();
		exit();
	}
	else if($_POST['action']==="register_user"){
		require_once(__DIR__ . "/scripts/UsersDB.php");
		$database = new UsersDB($db_connection);

		echo $database->register_user($_POST['username'], $_POST['password'], $_POST['first_last_name'], $_POST['year_of_birth'], $_POST['city'], $_POST['email'], $_POST['role']);
		exit();
	}

	if (session_status() == PHP_SESSION_NONE || !$_SESSION["logged_in"]) { //nije ulogiran
		echo json_encode(array("error" => "User is not logged in."));
		exit();
	}

	// ------- OGRAĐENI POZIVI ------------

	//USERS
	if($_POST['action']==="delete_user"){
		require_once(__DIR__ . "/scripts/UsersDB.php");
		$database = new UsersDB($db_connection);

		echo $database->delete_user($_POST['user_id']);
	}
	else if($_POST['action']==="update_user"){
		require_once(__DIR__ . "/scripts/UsersDB.php");
		$database = new UsersDB($db_connection);

		echo $database->update_user($_POST['username'], $_POST['password'], $_POST['first_last_name'], $_POST['year_of_birth'], $_POST['city'], $_POST['email'], $_POST['role'], $_POST['user_id']);
	}
	else if($_POST['action']==="get_users"){
		require_once(__DIR__ . "/scripts/UsersDB.php");
		$database = new UsersDB($db_connection);

		echo $database->get_users();
	}

	//CLASSES
	else if($_POST['action']==="add_class"){
		require_once(__DIR__ . "/scripts/ClassesDB.php");
		$database = new ClassesDB($db_connection);

		echo $database->add_class($_POST['name']);
	}
	else if($_POST['action']==="remove_class"){
		require_once(__DIR__ . "/scripts/ClassesDB.php");
		$database = new ClassesDB($db_connection);

		echo $database->remove_class($_POST['class_id']);
	}
	else if($_POST['action']==="update_class"){
		require_once(__DIR__ . "/scripts/ClassesDB.php");
		$database = new ClassesDB($db_connection);

		echo $database->update_class($_POST['class_id'], $_POST['name']);
	}
	else if($_POST['action']==="get_classes"){
		require_once(__DIR__ . "/scripts/ClassesDB.php");
		$database = new ClassesDB($db_connection);

		echo $database->get_classes();
	}

	//ORDERS
	else if($_POST['action']==="add_order"){
		require_once(__DIR__ . "/scripts/OrdersDB.php");
		$database = new OrdersDB($db_connection);

		echo $database->add_order($_POST['name'], $_POST['parent_class_id']);
	}
	else if($_POST['action']==="remove_order"){
		require_once(__DIR__ . "/scripts/OrdersDB.php");
		$database = new OrdersDB($db_connection);

		echo $database->remove_order($_POST['order_id']);
	}
	else if($_POST['action']==="update_order"){
		require_once(__DIR__ . "/scripts/OrdersDB.php");
		$database = new OrdersDB($db_connection);

		echo $database->update_order($_POST['order_id'], $_POST['name'], $_POST['parent_class_id']);
	}
	else if($_POST['action']==="get_orders"){
		require_once(__DIR__ . "/scripts/OrdersDB.php");
		$database = new OrdersDB($db_connection);

		if(isset($_POST['parent_class_id'])) $parent_class_id=$_POST['parent_class_id'];
		else $parent_class_id=-1;

		echo $database->get_orders($parent_class_id);
	}

	//FAMILIES
	else if($_POST['action']==="add_family"){
		require_once(__DIR__ . "/scripts/FamiliesDB.php");
		$database = new FamiliesDB($db_connection);

		echo $database->add_family($_POST['name'], $_POST['parent_order_id']);
	}
	else if($_POST['action']==="remove_family"){
		require_once(__DIR__ . "/scripts/FamiliesDB.php");
		$database = new FamiliesDB($db_connection);

		echo $database->remove_family($_POST['family_id']);
	}
	else if($_POST['action']==="update_family"){
		require_once(__DIR__ . "/scripts/FamiliesDB.php");
		$database = new FamiliesDB($db_connection);

		echo $database->update_family($_POST['family_id'], $_POST['name'], $_POST['parent_order_id']);
	}
	else if($_POST['action']==="get_families"){
		require_once(__DIR__ . "/scripts/FamiliesDB.php");
		$database = new FamiliesDB($db_connection);

		if(isset($_POST['parent_order_id'])) $parent_order_id=$_POST['parent_order_id'];
		else $parent_order_id=-1;

		echo $database->get_families($parent_order_id);
	}

	//SPECIES
	else if($_POST['action']==="get_species_hierarchy"){
		require_once(__DIR__ . "/scripts/SpeciesDB.php");
		$database = new SpeciesDB($db_connection);

		echo $database->get_species_hierarchy($_POST['species_id']);
	}
	else if($_POST['action']==="add_species"){
		require_once(__DIR__ . "/scripts/SpeciesDB.php");
		$database = new SpeciesDB($db_connection);

		mkdir("." . $media_dir, 0700);
		$photo_path = $media_dir . basename($_FILES["photo"]["name"]);
		move_uploaded_file($_FILES["photo"]["tmp_name"], "." . $photo_path);


		echo $database->add_species($_POST['name'], $_POST['family_id'], $_POST['size'], $_POST['nutrition'], $_POST['predators'], $_POST['lifetime'], $_POST['habitat'], $_POST['lifestyle'], $_POST['reproduction'], $_POST['distribution'], $_POST['location_x'], $_POST['location_y'], $photo_path);
	}
	else if($_POST['action']==="remove_species"){
		require_once(__DIR__ . "/scripts/SpeciesDB.php");
		$database = new SpeciesDB($db_connection);

		echo $database->remove_species($_POST['species_id']);
	}
	else if($_POST['action']==="update_species"){
		require_once(__DIR__ . "/scripts/SpeciesDB.php");
		$database = new SpeciesDB($db_connection);

		mkdir("." . $media_dir, 0700);
		$photo_path = $media_dir . basename($_FILES["photo"]["name"]);
		move_uploaded_file($_FILES["photo"]["tmp_name"], "." . $photo_path);

		echo $database->update_species($_POST['species_id'], $_POST['name'], $_POST['family_id'], $_POST['size'], $_POST['nutrition'], $_POST['predators'], $_POST['lifetime'], $_POST['habitat'], $_POST['lifestyle'], $_POST['reproduction'], $_POST['distribution'], $_POST['location_x'], $_POST['location_y'], $photo_path);
	}
	else if($_POST['action']==="get_species"){
		require_once(__DIR__ . "/scripts/SpeciesDB.php");
		$database = new SpeciesDB($db_connection);

		if(isset($_POST['parent_family_id'])) $parent_family_id=$_POST['parent_family_id'];
		else $parent_family_id=-1;

		echo $database->get_species($parent_family_id);
	}

	//MAMMALS
	else if($_POST['action']==="add_mammal"){
		require_once(__DIR__ . "/scripts/MammalsDB.php");
		$database = new MammalsDB($db_connection);

		mkdir("." . $media_dir, 0700);
		$photo_path = $media_dir . basename($_FILES["photo"]["name"]);
		move_uploaded_file($_FILES["photo"]["tmp_name"], "." . $photo_path);

		echo $database->add_mammal($_POST['species_id'], $_POST['name'], $_POST['age'], $_POST['sex'], $_POST['birth_location'], $_POST['arrival_date'], $photo_path, $_POST['interesting_facts']);
	}
	else if($_POST['action']==="remove_mammal"){
		require_once(__DIR__ . "/scripts/MammalsDB.php");
		$database = new MammalsDB($db_connection);

		echo $database->remove_mammal($_POST['animal_id']);
	}
	else if($_POST['action']==="update_animal"){
		require_once(__DIR__ . "/scripts/MammalsDB.php");
		$database = new MammalsDB($db_connection);

		mkdir("." . $media_dir, 0700);
		$photo_path = $media_dir . basename($_FILES["photo"]["name"]);
		move_uploaded_file($_FILES["photo"]["tmp_name"], "." . $photo_path);

		echo $database->update_animal($_POST["animal_id"], $_POST["name"], $_POST["age"], $_POST["sex"], $_POST["birth_location"], $_POST["arrival_date"], $photo_path, $_POST["interesting_facts"]);
	}
	else if($_POST['action']==="get_animals"){
		require_once(__DIR__ . "/scripts/MammalsDB.php");
		$database = new MammalsDB($db_connection);

		if(isset($_POST['species_id'])) $species_id=$_POST['species_id'];
		else $species_id=-1;

		echo $database->get_animals($species_id);
	}
	else if($_POST['action']==="get_mammal"){
		require_once(__DIR__ . "/scripts/MammalsDB.php");
		$database = new MammalsDB($db_connection);

		echo $database->get_mammal($_POST['animal_id']);
	}

	//ADOPTION
	else if($_POST['action']==="adopt"){
		require_once(__DIR__ . "/scripts/AdoptionsDB.php");
		$database = new AdoptionsDB($db_connection);

		echo $database->adopt($_POST['user_id'], $_POST['animal_id'], $_POST['email'], $_POST['first_last_name'], $_POST['city']);
	}
	else if($_POST['action']==="get_adopted"){
		require_once(__DIR__ . "/scripts/AdoptionsDB.php");
		$database = new AdoptionsDB($db_connection);

		echo $database->get_adopted($_POST['user_id']);
	}

	//GUARDS
	else if($_POST['action']==="assign_animal"){
		require_once(__DIR__ . "/scripts/GuardsDB.php");
		$database = new GuardsDB($db_connection);

		echo $database->assign_animal($_POST['user_id'], $_POST['animal_id']);
	}
	else if($_POST['action']==="unassign_animal"){
		require_once(__DIR__ . "/scripts/GuardsDB.php");
		$database = new GuardsDB($db_connection);

		echo $database->unassign_animal($_POST['user_id'], $_POST['animal_id']);
	}
	else if($_POST['action']==="get_assigned_animals"){
		require_once(__DIR__ . "/scripts/GuardsDB.php");
		$database = new GuardsDB($db_connection);

		echo $database->get_assigned_animals($_POST['user_id']);
	}

	//EXCLUSIVE CONTENT
	else if($_POST['action']==="add_exclusive_fact"){
		require_once(__DIR__ . "/scripts/ExclusiveContentDB.php");
		$database = new ExclusiveContentDB($db_connection);

		echo $database->add_exclusive_fact($_POST['animal_id'], $_POST['fact']);
	}
	else if($_POST['action']==="add_exclusive_photo"){
		require_once(__DIR__ . "/scripts/ExclusiveContentDB.php");
		$database = new ExclusiveContentDB($db_connection);

		mkdir("." . $media_dir, 0700);
		$photo_path = $media_dir . basename($_FILES["photo"]["name"]);
		move_uploaded_file($_FILES["photo"]["tmp_name"], "." . $photo_path);
		
		echo $database->add_exclusive_photo($_POST['animal_id'], $photo_path);
	}
	else if($_POST['action']==="add_exclusive_video"){
		require_once(__DIR__ . "/scripts/ExclusiveContentDB.php");
		$database = new ExclusiveContentDB($db_connection);

		mkdir("." . $media_dir, 0700);
		$video_path = $media_dir . basename($_FILES["video"]["name"]);
		move_uploaded_file($_FILES["video"]["tmp_name"], "." . $video_path);

		echo $database->add_exclusive_video($_POST['animal_id'], $video_path);
	}
	else if($_POST['action']==="get_exclusive_content"){
		require_once(__DIR__ . "/scripts/ExclusiveContentDB.php");
		$database = new ExclusiveContentDB($db_connection);

		echo $database->get_exclusive_content($_POST['animal_id']);
	}

	//VISITS
	else if($_POST['action']==="register_visit"){
		require_once(__DIR__ . "/scripts/VisitsDB.php");
		$database = new VisitsDB($db_connection);

		echo $database->register_visit($_POST["user_id"], $_POST["species_id"]);
	}
	else if($_POST['action']==="check_visit"){
		require_once(__DIR__ . "/scripts/VisitsDB.php");
		$database = new VisitsDB($db_connection);

		echo $database->check_visit($_POST["user_id"], $_POST["species_id"]);
	}
	else if($_POST['action']==="get_visit_count"){
		require_once(__DIR__ . "/scripts/VisitsDB.php");
		$database = new VisitsDB($db_connection);

		echo $database->get_visit_count($_POST['species_id']);
	}
	else if($_POST['action']==="recommend_species"){
		require_once(__DIR__ . "/scripts/VisitsDB.php");
		$database = new VisitsDB($db_connection);

		echo $database->recommend_species($_POST['current_species_id'], $_POST['user_id']);
	}
	

?>