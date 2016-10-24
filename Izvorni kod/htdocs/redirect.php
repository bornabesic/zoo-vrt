<?php

class Redirector{
	public static function redirect_logged_in_user($target){
		session_start();
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']){
			Redirector::redirect($target);
		}
	}

	public static function redirect($target){
		echo '<script type="text/javascript"> window.location = "' . $target . '"</script>';
	}
}

?>