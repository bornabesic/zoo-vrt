<?php

	if(!isset($_GET['do']))
		die('Nije definirana akcija.');

	echo $_GET['do'];

?>