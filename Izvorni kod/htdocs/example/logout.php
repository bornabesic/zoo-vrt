<?php
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		session_start();
		unset($_SESSION['logged_in']);
		unset($_SESSION['username']);
		session_destroy();
		require_once("redirect.php");
		Redirector::redirect("login.php");
	}
?>