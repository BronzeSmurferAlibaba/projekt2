<?php

	session_start();

	if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location: panel.php');
		exit();
	}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <link rel="stylesheet" href="style.css">
  <title>
    Logowanie Rejestracja
  </title>
</head>
<body>
  <hr></hr>
  <div id="rejestracja">
  <a href="rejestracja.php"><h2>Rejestracja</h2></a>

</div>
  <hr></hr>
  <div id="logowanie">
  <h2>Logowanie</h2>
  <form action="logowanie.php" method="post">
      Login: <input type="text" name="login"/></br>
      Hasło: <input type="password" name="haslo"/></br>
      <input type="submit" value="Zaloguj!"/>
  </form>
  <?php
	if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
?>
</div>
<hr></hr>
</body>
</html>
