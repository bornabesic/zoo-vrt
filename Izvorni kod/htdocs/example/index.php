<?php
	session_start();
	echo 'Bok, ' . $_SESSION['username'] . '!';
?>
<form action="logout.php" method="post">
<input type="submit" value="Odjavi se">
</form>