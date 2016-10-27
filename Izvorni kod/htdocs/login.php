<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ZOO Vrt</title>
<style type="text/css">
body {
	background-image: url(login2.jpg);
	background-repeat: no-repeat;
	background-size: cover;
	font-family: Verdana, Geneva, sans-serif;
	font-weight: bold;
	color: #FFF;
	background-color: #FFF;
	
}
input, button{
	margin-bottom:5px;
	-moz-border-radius: 15px;
 	border-radius: 15px;
    padding:5px;
}
#logo{
	text-align:center;
}
#form{
	color:#FFF;
	text-align:center;
}
#registration{
	display:none;
}
.text{
	border-width:5px;
	height:50px;
    font-size:16pt;
	padding:3px;
}
.text:focus {
    border-color: green;
    box-shadow: 0 0 15px green;
}
a:link {
	color: #FFF;
}
a:visited {
	color: #FFF;
}
a:hover {
	color: #FFF;
}
#registration_info{
	font-size:12pt;
	text-align:right;
}
</style>
<script src="jquery-3.1.1.js"></script>
</head>

<body>
<div id="registration_info">Nemate korisnički račun? <u><a id="registration_link" href="#">Registrirajte se.</a></u></div>
<div id="logo"><img src="logo.png" width="544" height="360" /></div>
<div id="form">
<form id="iform" action="acc.php?do=login" method="post" novalidate>
<input class="text" id="un" name="username" type="text" size="32" placeholder="Korisničko ime"/><br />
<input class="text" name="password" type="password" size="32" placeholder="Lozinka"/><br />
<div id="registration">
<input class="text" name="first_last_name" type="text" size="32" placeholder="Ime i prezime"/><br />
<input class="text" name="email" type="email" size="32" placeholder="E-mail adresa"/><br />
<input class="text" name="city" type="text" size="32" placeholder="Mjesto stanovanja"/><br />
<input class="text" name="year_of_birth" type="text" size="32" placeholder="Godina rođenja"/><br />
</div>
<input class="text" id="submit_button" type="submit" form="iform" value="Prijavi se"/><br />
</form>
</div>

<script type="text/javascript">

$('#registration_link').click(show_registration_form);

function show_registration_form(){
	$("#registration").show(1000);
	$("#submit_button").val("Registriraj se");
	$("#iform").attr("action", "acc.php?do=register");
	$("#un").focus();
}

</script>
</body>
</html>