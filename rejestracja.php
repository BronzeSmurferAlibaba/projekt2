<?php

	session_start();

	if (isset($_POST['email']))
{
	$wszystko_OK=true;

	$imie = $_POST['imie'];

	if ((strlen($imie)<3) || (strlen($imie)>20))
	{
		$wszystko_OK=false;
		$_SESSION['e_imie']="Imie musi posiadać od 3 do 20 znaków!";
	}

	if (ctype_alnum($imie)==false)
	{
		$wszystko_OK=false;
		$_SESSION['e_imie']="Imie może składać się tylko z liter i cyfr (bez polskich znaków)";
	}
	$nazwisko = $_POST['nazwisko'];

	if ((strlen($nazwisko)<3) || (strlen($nazwisko)>20))
	{
		$wszystko_OK=false;
		$_SESSION['e_nazwisko']="Nazwisko musi posiadać od 3 do 20 znaków!";
	}

	if (ctype_alnum($nazwisko)==false)
	{
		$wszystko_OK=false;
		$_SESSION['e_nazwisko']="Nazwisko może składać się tylko z liter i cyfr (bez polskich znaków)";
	}
	$login = $_POST['login'];

	if ((strlen($login)<3) || (strlen($login)>20))
	{
		$wszystko_OK=false;
		$_SESSION['e_login']="Login musi posiadać od 3 do 20 znaków!";
	}

	if (ctype_alnum($login)==false)
	{
		$wszystko_OK=false;
		$_SESSION['e_login']="Login może składać się tylko z liter i cyfr (bez polskich znaków)";
	}

	$email = $_POST['email'];
	$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

	if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
	{
		$wszystko_OK=false;
		$_SESSION['e_email']="Podaj poprawny adres e-mail!";
	}

	$haslo1 = $_POST['haslo2'];
	$haslo2 = $_POST['haslo2'];

	if ((strlen($haslo1)<8) || (strlen($haslo1)>20))
	{
		$wszystko_OK=false;
		$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków!";
	}

	if ($haslo1!=$haslo2)
	{
		$wszystko_OK=false;
		$_SESSION['e_haslo']="Podane hasła nie są identyczne!";
	}

	$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

	if (!isset($_POST['regulamin']))
	{
		$wszystko_OK=false;
		$_SESSION['e_regulamin']="Potwierdź akceptację regulaminu!";
	}
	$_SESSION['fr_imie'] = $imie;
	$_SESSION['fr_email'] = $email;
	$_SESSION['fr_haslo1'] = $haslo1;
	$_SESSION['fr_haslo2'] = $haslo2;
	$_SESSION['fr_nazwisko'] = $nazwisko;
	$_SESSION['fr_login'] = $login;
	if (isset($_POST['regulamin'])) $_SESSION['fr_regulamin'] = true;

	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);

	try
	{
		$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
		if ($polaczenie->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
			$rezultat = $polaczenie->query("SELECT id FROM user WHERE email='$email'");

			if (!$rezultat) throw new Exception($polaczenie->error);

			$ile_takich_maili = $rezultat->num_rows;
			if($ile_takich_maili>0)
			{
				$wszystko_OK=false;
				$_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail!";
			}

			$rezultat = $polaczenie->query("SELECT id FROM user WHERE imie='$imie'");

			if (!$rezultat) throw new Exception($polaczenie->error);

			$ile_takich_nickow = $rezultat->num_rows;
			if($ile_takich_nickow>0)
			{
				$wszystko_OK=false;
				$_SESSION['e_imie']="Istnieje już to Imie!";
			}

			if ($wszystko_OK==true)
			{

				if ($polaczenie->query("INSERT INTO 'user' (`login`, `haslo`, `Imie`, `nazwisko`, `email`) VALUES (NULL, '$login', '	$haslo_hash', '$imie', '$nazwisko', '$email');"));
				{
					$_SESSION['udanarejestracja']=true;
					header('Location: witamy.php');
				}

			}

			$polaczenie->close();
		}

	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
		echo '<br />Informacja developerska: '.$e;
	}

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
  <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<form method="post">
  Imie: </br><input type="text" name="imie" value="<?php
			if (isset($_SESSION['fr_imie']))
			{
				echo $_SESSION['fr_imie'];
				unset($_SESSION['fr_imie']);
			}
		?>"/></br>
		<?php
			if (isset($_SESSION['e_imie']))
			{
				echo '<div class="error">'.$_SESSION['e_imie'].'</div>';
				unset($_SESSION['e_imie']);
			}
		?>
  Nazwisko: </br><input type="text" name="nazwisko"/></br>
  Email: </br><input type="text" name="email" value="<?php
			if (isset($_SESSION['fr_email']))
			{
				echo $_SESSION['fr_email'];
				unset($_SESSION['fr_email']);
			}
		?>"/></br>
		<?php
		if (isset($_SESSION['e_email']))
		{
			echo '<div class="error">'.$_SESSION['e_email'].'</div>';
			unset($_SESSION['e_email']);
		}
	?>
  Login: </br><input type="text" name="login"/></br>
  Hasło: </br><input type="password" name="haslo1" value="<?php
			if (isset($_SESSION['fr_haslo1']))
			{
				echo $_SESSION['fr_haslo1'];
				unset($_SESSION['fr_haslo1']);
			}
		?>"/></br>
		<?php
		if (isset($_SESSION['e_haslo']))
		{
			echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
			unset($_SESSION['e_haslo']);
		}
	?>
  Ponownie Hasło: </br><input type="password" name="haslo2" value="<?php
			if (isset($_SESSION['fr_haslo2']))
			{
				echo $_SESSION['fr_haslo2'];
				unset($_SESSION['fr_haslo2']);
			}
		?>"/></br>
  <label>
</br><input type="checkbox" name="regulamin" <?php
			if (isset($_SESSION['fr_regulamin']))
			{
				echo "checked";
				unset($_SESSION['fr_regulamin']);
			}
				?>/>Akceptacja regilaminu
</label>
<?php
		if (isset($_SESSION['e_regulamin']))
		{
			echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
			unset($_SESSION['e_regulamin']);
		}
	?>
  </br><input type="submit" value="Wyślij!"/>
</form>





</body>
</html>
