 <html>
 <head>
<?php
	require_once("redirect.php");
	Redirector::redirect_logged_in_user("index.php");
?>
 </head>
<body>
REGISTRACIJA:
<form action="register.php" method="post">
Username: <input type="text" name="username"><br>
Password: <input type="password" name="password"><br>
E-mail: <input type="text" name="email"><br>
First and last name: <input type="text" name="first_last_name"><br>
City: <input type="text" name="city"><br>
Year of birth: <input type="text" name="year_of_birth"><br>
<input type="submit" value="Registriraj se">
</form>
<hr>
PRIJAVA:
<form action="user.php" method="post">
Username: <input type="text" name="username"><br>
Password: <input type="password" name="password"><br>
<input type="submit" value="Prijavi se">
</form>

</body>
</html> 