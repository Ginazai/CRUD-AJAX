<?php 
session_start();
require_once "pdo.php";
//Protection
if (! isset($_SESSION['user_id']) && ! isset($_SESSION['name'])) {
	die('<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><h1 class="container" style="color: red; text-align: center;">UNAUTHORIZED</h1>');
}
//ID Protection 
if (! isset($_GET['profile_id'])) {
	$_SESSION['error'] = "Bad value for profile";
	header("Location: index.php");
	return;
}
?>
<!-- View --> 
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Rafael Caballero</title>
	<link rel="stylesheet" type="text/css" href="crud_stylesheet.css">
	<link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">
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
</head>
<body>
	<div class="container">
		<h1>Profile Information</h1>
		<?php 
		//Data Validation
		$sql = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :pid");
		$stmt = $sql->execute(array(
			':pid' => $_GET['profile_id']));
		$row = $sql->fetch(PDO::FETCH_ASSOC);
		echo ("<p>First Name: ".$row['first_name']."</p>");
		echo ("<p>Last Name: ".$row['last_name']."</p>");
		echo ("<p>Email: ".$row['email']."</p>");
		echo ("<p>Headline: ".$row['headline']."</p>");
		echo ("<p>Summary: ".$row['summary']."</p>");
		//Third SQL-Schools
		$stmt_3 = $pdo->prepare("SELECT * FROM education WHERE profile_id =  :pid_3");
		$stmt_3->execute(array(
			':pid_3' => $_GET['profile_id']));
		while ($row_3 = $stmt_3->fetch(PDO::FETCH_ASSOC)) {
			$inst_id = $row_3['institution_id'];
			$edu_year = $row_3['year']; 
			//Institution Relation
			
		}


		if (isset($inst_id)) {
			$stmt_4 = $pdo->prepare("SELECT name FROM institution WHERE institution_id = :inst");
			$stmt_4->execute(array(
					':inst' => $inst_id));
				while ($row_4 = $stmt_4->fetch(PDO::FETCH_ASSOC)) {
					$inst_name = $row_4['name'];
				}	
			echo("<p>Education</p><ul>");
			echo("<li>".$edu_year.":"." ".$inst_name."</li></ul>");
			}


		
		

		//Second SQL
		$sql_2 = "SELECT * FROM position WHERE profile_id = :pid_2";
		$stmt_2 = $pdo->prepare($sql_2);
		$stmt_2->execute(array(
			':pid_2' => $_GET['profile_id']));
		while($row_2 = $stmt_2->fetch(PDO::FETCH_ASSOC)) {
			$inst_year = $row_2['year'];
			$inst_desc = $row_2['description'];
			
		}

		if (isset($inst_year) && isset($inst_year)) {
				echo ("<p>Position</p><ul>");
				echo ("<li>".$inst_year.":"." ".$inst_desc."</li>");
				echo("</ul>");
		}
		
		echo ("<a href='index.php'>Done</a>");

	/*	for ($i = 0; $i<=8; $i++) {
			if (! isset($row_2['desc'.$i])) continue;
			if (! isset($row_2['year'.$i])) continue;
			$year = $row_2['year'.$i];
			$desc = $row_2['desc'.$i];
			echo("<ul>Position: <li>".$year.":"." ".$desc."</li></ul>");
		}*/

		?>
	</div>
<!-- Resources -->
</body>
</html>