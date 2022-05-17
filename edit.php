<?php 
session_start();
require_once "pdo.php";
//Log Protection
if (! isset($_SESSION['user_id']) && ! isset($_SESSION['name'])) {
	die('<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><h1 class="container" style="color: red; text-align: center;">UNAUTHORIZED</h1>');
}
//profile_id protection
if (! isset($_GET['profile_id'])) {
	$_SESSION['error'] = "Bad id for user";
	header("Location: index.php");
	return;
}
//Validation
if (isset($_GET['profile_id']) && isset($_SESSION['user_id']) && isset($_SESSION['name'])) {
	//Data Submit validation
	if (isset($_POST['add']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
		//Field Protection
		if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1|| strlen($_POST['email'])
   		 < 1 || strlen($_POST['headline']) < 1|| strlen($_POST['summary']) < 1) {
			$_SESSION['error'] = "All fields are required";
			header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
			return;
		}
		//email validation
		$email_check = $_POST['email'];
		if (! filter_var($email_check, FILTER_VALIDATE_EMAIL)) {
			$_SESSION['error'] = "Invalid email adress";
			header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
			return;
		}
		//Dynamic fields validation
		for($i=0; $i<=8; $i++) {
       	 if ( ! isset($_POST['year'.$i]) ) continue;
       	 if ( ! isset($_POST['desc'.$i]) ) continue;
       	 $year = $_POST['year'.$i];
       	 $desc = $_POST['desc'.$i];
       	 if ( strlen($year) < 1 || strlen($desc) < 1 ) {
       	    $_SESSION['error'] = "All fields are required";
			header("Location: edit.php");
			return;
        }
        if ( ! is_numeric($year) ) {
            $_SESSION['error'] = "Year must be numeric";
			header("Location: edit.php");
			return;
        }
   		}
		//data modification
		$sql = "UPDATE profile SET first_name = :fn, last_name = :ln, email = :em, headline = :he, summary = :su";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':fn' => $_POST['first_name'],
			':ln' => $_POST['last_name'],
			':em' => $_POST['email'],
			':he' => $_POST['headline'],
			':su' => $_POST['summary']));
		//Second-time mod
		// Clear out the old position entries
		//$stmt = $pdo->prepare('DELETE FROM position WHERE profile_id=:pid');
		//$stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
		// Insert the position entries
		$rank = 0;
		for($i=0; $i<=8; $i++) {
  		  if ( ! isset($_POST['year'.$i]) ) continue;
  		  if ( ! isset($_POST['desc'.$i]) ) continue;
   		 $year = $_POST['year'.$i];
   		 $desc = $_POST['desc'.$i];
   		 $stmt = $pdo->prepare('INSERT INTO position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
   		 $stmt->execute(array(
    		    ':pid' => $_REQUEST['profile_id'],
    		    ':rank' => $rank,
    		    ':year' => $year,
    		    ':desc' => $desc)
   		 );
   		 $rank++;
		}
		$_SESSION['succes'] = "Record modified";
		header("Location: index.php");
		return;
	}
}
//Cancel
if (isset($_POST['cancel'])) {
	header("Location: index.php");
	return;
}
//Database Error Validaton
$sql = "SELECT * FROM profile WHERE profile_id = :pid";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
':pid' => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row == false) {
	$_SESSION['error'] = "Invalid profile";
	header("Location: index.php");
	return;
}
//Second-time data
$sql2 = "SELECT * FROM position WHERE profile_id = :pid";
$stmt2 = $pdo->prepare($sql);
$stmt2->execute(array(
':pid' => $_GET['profile_id']));
$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

for ($i = 0; $i <=8; $i++) {
	if (! isset($row2['year'.$i])) continue;
	if (! isset($row2['desc'.$i])) continue;
	$year = htmlentities($row2['year'.$i]);
	$desc= htmlentities($row2['desc'.$i]);
}
//Retrieving Values
$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$he = htmlentities($row['headline']);
$su = htmlentities($row['summary']);
?>
<!-- End of the model -->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Rafael Caballero</title>
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
		<h1 class="text-center">Editing Profile</h1>
		<?php 
		if (isset($_SESSION['error'])) {
			echo('<div style="color: red;" class="text-center">'.$_SESSION['error'].'</div>');
			unset($_SESSION['error']);
		}
		if (isset($_SESSION['succes'])) {
			echo('<div style="color: green;" class="text-center">'.$_SESSION['succes'].'</div>');
			unset($_SESSION['succes']);
		}
		?>
		<form method="post">
			<p>First Name: <input type="text" name="first_name" value="<?= $fn ?>"></p>
			<p>Last Name: <input type="text" name="last_name" value="<?= $ln ?>"></p>
			<p>Email: <input type="text" name="email" value="<?= $em ?>"></p>
			<p>Headline: <input type="text" name="headline" value="<?= $he ?>"></p>
			<p>Summary:<br><textarea name="summary" rows="8" cols="80"><?= $su ?></textarea></p>
			
			<p>Position: <input id="addPost" type="submit" name="addPost" value="+"></p>
			<div id="position_fields">
				<script type="text/javascript">
					var i = 0;
				$(document).ready(function () {
					$('#addPost').click( function (event) {
						event.preventDefault();
						if (i >= 9) {
							alert("Maximum of nine position entries exceeded");
						} else {
							if (i < 9) {
								var inst = "$('#position" + i + "').remove(); i -= 1;";
								$('#position_fields').append('<div id="position' + i + '">\
								<p>Year: <input type="text" name="year'+ i +'" value=""><input type="button" value="-" onclick="' + inst + '"></p><br /> \
								<textarea name="desc' + i + '" rows="8" cols="80"></textarea></div>'); 
								i++;
							}
						}
						});
					});
				</script>
			</div>
			<input type="submit" name="add" value="Save">
			<input type="submit" name="cancel" value="Cancel">
		</form>
	</div>
<!-- Resources -->
</body>
</html>