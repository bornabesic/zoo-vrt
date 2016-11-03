<?php


	if(	empty($_POST['username']) ||
		empty($_POST['password']))
				die();

	require('phpass/PasswordHash.php');

	$username = $_POST['username'];
	$password = $_POST['password'];

	$db = mysqli_connect('localhost','login_system','rzjesmece') or
			die('Unable to connect to database.');

	$db->set_charset('utf8');

	$login_query = 'SELECT password_hash FROM login_test.users WHERE username=?;';
	$login_statement = $db->prepare($login_query);
	if($login_statement){
		$login_statement->bind_param("s", $username);
     	$login_statement->execute();
	}
	else die('Cannot prepare statement.');
	//echo $login_query  . '<br>';

	//$login_result = $db->query($login_query);

	$login_result = $login_statement->get_result();

	if($db->errno!=0){
		echo $db->error . '<br>';
		die('Can\'t login!' . '<br>');
	}

	if($login_result->num_rows==1){
		$row = $login_result->fetch_assoc();

		$hasher = new PasswordHash(8, false);
		$password_hash = $row['password_hash'];

		$check = $hasher->CheckPassword($password, $password_hash);

		/*echo '<script language="javascript">';
		echo 'alert("' . $out . '")';
		echo '</script>';*/

		if($check) {
			session_start();
			$_SESSION['logged_in']=true;
			$_SESSION['username']=$username;
		}
		else echo "Login failed.";
	}
	else echo "Login failed.";
	$db->close();

	require_once("redirect.php");
	Redirector::redirect_logged_in_user("index.php");
?>