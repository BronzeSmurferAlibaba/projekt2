<?php

	session_start();

	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}

?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8" />
	<title>Panel Administratora</title>
</head>

<body>

<?php
	echo "<p>Witaj ".@$_SESSION['login'].'! [ <a href="logout.php">Wyloguj się!</a> ]</p>';
	echo "<p><b>Imie</b>: ".$_SESSION['imie'];
	echo "<b>Nazwisko</b>: ".$_SESSION['nazwisko'];

?>

</body>
</html>
