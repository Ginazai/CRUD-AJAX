<?php 
session_start();
require_once "pdo.php";

$stmt = $pdo->query("SELECT * FROM profile");
$stmt_2 = $pdo->query("SELECT * FROM profile");
$comprobation = $stmt_2->fetch(PDO::FETCH_ASSOC);
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
		<h1 class="text-center">Resume Registry</h1>
		<!-- Log Check */ -->
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
 		//Log Comprobation
 		if (! isset($_SESSION['name']) && ! isset($_SESSION['user_id'])) {
 			echo('<div class="text-center"><a href="login.php">Please log in</a></div>');
		}
		//SESSION Validation
		if (isset($_SESSION['name']) && isset($_SESSION['user_id'])) {
			echo('<h4 class="text-center">Welcome '.$_SESSION['name'].'</h4>');
 			echo('<div class="text-center"><a href="logout.php">Log out</a>'.' | '.'<a href="add.php">Add New Entry</a></div>');
 			//Data Validation
 			if ($comprobation == false) {
 				echo('<div class="text-center">No rows found</div>');
 			}
			if ($comprobation == true) {
				echo("<table style='margin-top: 15px; margin-left: auto; margin-right: auto;' class='text-center' border='1px'><tr><th>Name</th><th>Headline</th><th>Action</th><tr>");
				while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					echo("<tr><td>");
					echo("<a href='view.php?profile_id=".$row['profile_id']."'>".htmlentities($row['first_name'])." ".htmlentities($row['last_name'])."</a>");
					echo("</td><td>");
					echo(htmlentities($row['headline']));
					echo("</td><td>");
					echo("<a href='edit.php?profile_id=".urlencode(htmlentities($row['profile_id']))."''>Edit</a>"." / "."<a href='delete.php?profile_id=".urldecode(htmlentities($row['profile_id']))."''>Delete</a></td></tr>");
				}
				echo('</table>');
			}
		}
		?>
	</div>
<!-- Resources -->
</body>
</html>