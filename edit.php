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
		$sql = "UPDATE profile SET first_name = :fn, last_name = :ln, email = :em, headline = :he, summary = :su
		WHERE profile_id = :pid";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':pid' => $_REQUEST['profile_id'],
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
		<form class="needs-validation add-form m-auto shadow rounded-3" method="post">
		<form method="post" class="needs-validation add-form m-auto shadow rounded-3">
          <div class="row">

            <div class="col-6">
              <label for="firstName" class="form-label">First name:</label>
              <input id="firstName" type="text" class="form-control" name="first_name" placeholder="" value="<?= $fn ?>">
            </div>

            <div class="col-sm-6">
              <label for="lastName" class="form-label">Last name:</label>
              <input class="form-control" id="lastName" type="text" name="last_name" placeholder="" value="<?= $ln ?>">
            </div>

            <div class="col-sm-12">
              <label for="email" class="form-label">Email:</label>
              <input id="email" class="form-control" type="text" name="email" placeholder="" value="<?= $em ?>">
            </div>

            <div class="col-sm-12">
              <label for="headline" class="form-label">Headline:</label>
              <input class="form-control" id="headline" type="text" name="headline" placeholder="" value="<?= $he ?>">
            </div>

            <div class="col-sm-12">
              <label for="summary" class="form-label">Summary:</label>
              <textarea id="summary" class="form-control" name="summary" rows="8" cols="80"><?= $su ?></textarea>
            </div>

            <div class="col-sm-2">
              <label for="addEdu" class="form-label">Education:</label>

              <input class="form-control btn btn-success btn-sm" id="addEdu" type="submit" name="add_education" value="+">
            </div>

            <div class="col-sm-12" id="edu_fields">
				<script type="text/javascript" src="js/edu.js"></script>
				<script type="text/javascript" src="js/ajax.js"></script>
				<?php
				
				?>
			</div>

            <div class="col-sm-2">
              <label for="addPost" class="form-label">Position:</label>
              <input class="form-control btn btn-success btn-sm" id="addPost" type="submit" name="addPost" value="+">
            </div>

            <div class="col-sm-12" id="position_fields">
				<script type="text/javascript" src="js/position.js"></script>
				<?php

				?>
			</div>

			<div class="row justify-content-around m-auto mt-5">
				<input class="col-6 mb-3 btn btn-primary" type="submit" name="add" value="Add">
				<input class="col-6 btn btn-danger" type="submit" name="cancel" value="Cancel">
			</div>
			<br>
        </form>
		</form>
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
	href="css/stylesheet.css">
</body>
</html>