<?php 
session_start();
require_once "pdo.php";
//Protection
if (! isset($_SESSION['user_id']) && ! isset($_SESSION['name'])) {
	die('<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><h1 class="container" style="color: red; text-align: center;">UNAUTHORIZED</h1>');
}
//ID Protection 
if (! isset($_GET['profile_id'])) {
	die('<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><h1 class="container" style="color: red; text-align: center;">id parameter missing</h1>');
}

//Validation
if (isset($_SESSION['user_id']) && isset($_SESSION['name']) && isset($_GET['profile_id']) && isset($_POST['delete'])) {
	$sql = "DELETE FROM profile WHERE profile_id = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(
		':id' => $_GET['profile_id']));
	$_SESSION['succes'] = "Record deleted";
	header('Location: index.php');
	return;
}

//Error_Check
$sql = "SELECT * FROM profile WHERE profile_id = :pid";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':pid' => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
	$_SESSION['error'] = "Bad id for selected element";
	header('Location: index.php');
	return;
}
//Cancel
if (isset($_POST['cancel'])) {
	header("Location: index.php");
	return;
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
</head>
<body>
	<div class="container">
		<h1>Deleting Profile</h1>
		<?php 
		//Process Comprobation
		if (isset($_SESSION['error'])) {
			echo('<div style="color: red;" class="text-center">'.$_SESSION['error'].'</div>');
			unset($_SESSION['error']);
		}
		if (isset($_SESSION['succes'])) {
			echo('<div style="color: green;" class="text-center">'.$_SESSION['succes'].'</div>');
			unset($_SESSION['succes']);
		}
		//Data question
		$sql = $pdo->prepare("SELECT first_name, last_name FROM profile WHERE profile_id = :pid");
		$stmt = $sql->execute(array(
			':pid' => $_GET['profile_id']));
		$row = $sql->fetch(PDO::FETCH_ASSOC);
		echo ("<p>First Name: ".$row['first_name']."</p>");
		echo ("<p>Last Name: ".$row['last_name']."</p>");
		?>
		<form method="post">
			<div><input type="hidden" name="user_id"><input type="submit" name="delete" value="Delete"><input type="submit" name="cancel" value="Cancel"></div>
		</form>
	</div>
<!-- Resources -->
</body>
</html>