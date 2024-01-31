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
	<title>Resume</title>

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>

<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

<link 
	href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
	rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
	crossorigin="anonymous">

<link rel="stylesheet" 
href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"
	integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc"
	crossorigin="anonymous"></script>

</head>
<body class="d-flex align-items-center">
	<div class="container r-view">
		<h1>Profile Information</h1>
		<ul class="list-group list-group-flush rounded-3 shadow">
		<?php 
		//Data Validation
		$sql = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :pid");
		$stmt = $sql->execute(array(
			':pid' => $_GET['profile_id']));
		$row = $sql->fetch(PDO::FETCH_ASSOC);
		echo ("<li class='list-group-item'>First Name: ".$row['first_name']."</li>");
		echo ("<li class='list-group-item'>Last Name: ".$row['last_name']."</li>");
		echo ("<li class='list-group-item'>Email: ".$row['email']."</li>");
		echo ("<li class='list-group-item'>Headline: ".$row['headline']."</li>");
		echo ("<li class='list-group-item'>Summary: ".$row['summary']."</li>");

		//Second SQL
		$sql_2 = "SELECT * FROM position WHERE profile_id = :pid_2";
		$stmt_2 = $pdo->prepare($sql_2);
		$stmt_2->execute(array(
			':pid_2' => $_GET['profile_id']));

		echo ("<li class='list-group-item list-group-item-secondary rounded-0 border border-bottom-0'>Position</li><ol class='list-group list-group-numbered'>");
		while($row_2 = $stmt_2->fetch(PDO::FETCH_ASSOC)) {
			$inst_year = $row_2['year'];
			$inst_desc = $row_2['description'];
			if (isset($inst_year)) {
				echo ("<li class='list-group-item border border-0'>".$inst_year.":"." ".$inst_desc."</li>");
			}
		}
		echo("</ol>");

		//Third SQL-Schools
		$stmt_3 = $pdo->prepare("SELECT * FROM education WHERE profile_id =  :pid_3");
		$stmt_3->execute(array(
			':pid_3' => $_GET['profile_id']));
		echo("<li class='list-group-item list-group-item-secondary rounded-0 border-bottom-0'>Education</li><ol class='list-group list-group-numbered'>");
		while ($row_3 = $stmt_3->fetch(PDO::FETCH_ASSOC)) {
			$inst_id = $row_3['institution_id'];
			$edu_year = $row_3['year']; 
			if (isset($inst_id)) {
				$stmt_4 = $pdo->prepare("SELECT name FROM institution WHERE institution_id = :inst");
				$stmt_4->execute(array(
						':inst' => $inst_id));
					while ($row_4 = $stmt_4->fetch(PDO::FETCH_ASSOC)) {
						$inst_name = $row_4['name'];
						echo("<li class='list-group-item border-0'>".$edu_year.":"." ".$inst_name."</li>");
					}	
			}
		}
			echo("</ol>");
		//Third
		
		echo ("</ul><a class='btn btn-primary mt-3 shadow-sm' href='index.php'>Done</a>");

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
<script
	src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
	integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
	crossorigin="anonymous"></script>
<script
	src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
	integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
	crossorigin="anonymous"></script>
<link rel="stylesheet"
	type="text/css"
	href="css/stylesheet.css"></body>
</html>