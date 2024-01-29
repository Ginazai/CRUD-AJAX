<?php 
session_start();
require_once "pdo.php";

if (isset($_POST['cancel'])) {
	header("Location: index.php");
	return;
}

//Data Validation
if (isset($_POST['log']) && isset($_POST['email']) && isset($_POST['password'])) {
	$salt = 'XyZzy12*_';
	$email_check = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
	$check = $_POST['password'];
	// $check = hash('md5', $salt.$_POST['password']);
	//Empty field check
	if (strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1) {
		$_SESSION['error'] = "All fields are required";
		header("Location: login.php");
		return;
	}
	//Email error
	if ($email_check == false) {
		$_SESSION['error'] = "Invalid email Adress";
		header("Location: login.php");
		return;
	}
	//Data Selection
		$sql = "SELECT user_id, name FROM users WHERE email = :em AND password = :pw";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':pw' => $check,
			':em' => $email_check));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		//Data Validation
		if ($row !== false) {
			$_SESSION['name'] = $row['name'];
			$_SESSION['user_id'] = $row['user_id'];
			header("Location: index.php");
			return;
		} 
		if ($row == false) {
			$_SESSION['error'] = "Invalid credentials";
			header("Location: login.php");
			return;
		}
	}
?>
<!-- End of the model -->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Rafael Caballero</title>
	<link rel="stylesheet" type="text/css" href="crud_stylesheet.css">
<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">

<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
    crossorigin="anonymous">

<link rel="stylesheet" 
    href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>

<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">
	<script type="text/javascript" src="validate.js"></script>
</head>
<body>
	<div class="container">
		<h1 class="text-center">Please log in</h1>
		<?php
		if (isset($_SESSION['error'])) {
			echo("<div style='color: red;' class='text-center'>".$_SESSION['error']."</div>");
			unset($_SESSION['error']);
		} elseif (isset($_SESSION['succes'])) {
			echo("<div style='color: green;' class='text-center'>".$_SESSION['succes']."</div>");
			unset($_SESSION['succes']);
		}
		?>
		<!-- Log Check -->
		<form method="post">
			<p>E-mail <input id="e-mail" type="text" name="email"></p>
			<p>Password <input type="password" name="password" id="id_1723"></p>
			<input type="submit" onclick="return doValidate();" name="log" value="Log In">
			<input type="submit" name="cancel" value="Cancel">
		</form>
		<?php 

		?>
	</div>
<!-- Resources -->
</body>
</html>