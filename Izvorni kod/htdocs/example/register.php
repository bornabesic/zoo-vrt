<?php
	if(	empty($_POST['first_last_name']) ||
		empty($_POST['city']) ||
		empty($_POST['year_of_birth']) ||
		empty($_POST['email']) ||
		empty($_POST['username']) ||
		empty($_POST['password']))
			die('Please, fill out the form.');

	require('phpass/PasswordHash.php');

	$first_last_name = $_POST['first_last_name'];
	$city = $_POST['city'];
	$year_of_birth = $_POST['year_of_birth'];
	$email = $_POST['email'];
	$username = $_POST['username'];
	$password = $_POST['password'];

	$db = mysqli_connect('localhost','login_system','rzjesmece') or
		die('Unable to connect to database.');

	$db->set_charset('utf8');

	if(!preg_match('/.{1,32}/', $first_last_name) ||
		!preg_match('/\d{4}/', $year_of_birth) ||
		!preg_match('/.{1,32}/', $city) ||
		!preg_match('/.{1,32}/', $email) ||
		!preg_match('/.{1,32}/', $username) ||
		!preg_match('/.{1,32}/', $password))
		die('Regex doesnt match!');

	$hasher = new PasswordHash(8, false);
	$password_hash = $hasher->HashPassword($password);

	$registration_query = "INSERT INTO login_test.users VALUES (?, ?, ?, ?, ?, ?, NULL);";
	$registration_statement = $db->prepare($registration_query);
	if($registration_statement){
		$registration_statement->bind_param("ssssss", $first_last_name, $year_of_birth, $city, $email, $username, $password_hash);
     	$registration_statement->execute();
	}
	else die('Cannot prepare statement.');

	//$registration_result = $db->query($registration_query);
	$registration_result = $registration_statement->get_result();

	if($db->errno!=0){
		echo $db->error;
		die('Can\'t register user!' . '<br>');
	}
	$db->close();

	session_start();
	$_SESSION['logged_in']=true;
	$_SESSION['username']=$username;
	require_once("redirect.php");
	Redirector::redirect_logged_in_user("index.php");
?>