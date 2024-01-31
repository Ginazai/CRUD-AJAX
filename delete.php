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
	<title>Delete registry</title>

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
	<div class="container">
		<main class="delete-content m-auto text-center shadow rounded-3">
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
			<div class="row justify-content-center">
				<input class="btn btn-danger col-2 btn-sm" type="hidden" name="user_id">
				<input class="btn btn-danger col-2 btn-sm" type="submit" name="delete" value="Delete">
			</div>
			<div class="row justify-content-center">
				<input class="btn btn-secondary col-2 btn-sm" type="submit" name="cancel" value="Cancel">
			</div>
		</form>

	</div>
	<main>
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
	href="css/stylesheet.css">
</body>
</html>