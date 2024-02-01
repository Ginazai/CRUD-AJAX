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

/************************************************************************************************
 ***********************************************************************************************
 ***      ******* *****        ***** **********************************************************
 *** ****** **** * *******  ******* * *******************************************************
 *** ****** *** *** ******  ****** *** ****************************************************
 *** ****** **       *****  *****       **************************************************
 ***       ** ******* ****  **** ******* ***********************************************
 **************************************************************************************/

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
		// Clear out the old position entries
		$stmt = $pdo->prepare('DELETE FROM position WHERE profile_id = :pid');
		$stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
		// Insert the position entries
		$rank = 0;
		for($i=0; $i<=9; $i++) {
  		  if ( ! isset($_POST['year'.$i]) ) continue;
  		  if ( ! isset($_POST['desc'.$i]) ) continue;
   		 $year = $_POST['year'.$i];
   		 $desc = $_POST['desc'.$i];
   		 $stmt = $pdo->prepare('INSERT INTO position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc )');
   		 $stmt->execute(array(
    		    ':pid' => $_REQUEST['profile_id'],
    		    ':rank' => $rank,
    		    ':year' => $year,
    		    ':desc' => $desc));
   		 $rank++;
		}
		//For education table
		$e_stmt = $pdo->prepare('DELETE FROM education WHERE profile_id = :pid');
		$e_stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

		$r = 0;
		for($j=0; $j<=9; $j++) {
			if ( ! isset($_POST['edu_year'.$j]) ) continue;
		  if ( ! isset($_POST['edu_school'.$j]) ) continue;
 		 $e_year = $_POST['edu_year'.$j];
 		 $e_inst = $_POST['edu_school'.$j];

 		 $i_stmt = "SELECT * FROM institution WHERE name = :inst";
 		 $i_stmt = $pdo->prepare($i_stmt);
 		 $i_stmt->execute(array(
 		 	':inst'=> $e_inst
 		 ));
 		 while ($i_row = $i_stmt->fetch(PDO::FETCH_ASSOC)) {
 		 	$inst_id = $i_row['institution_id'];

 		 	$e_stmt = $pdo->prepare('INSERT INTO education (profile_id, institution_id, rank, year) VALUES ( :pid, :inst, :rank, :year )');
 		 	$e_stmt->execute(array(
  		    	':pid' => $_REQUEST['profile_id'],
  		    	':inst' => $inst_id,
  		    	':rank' => $r,
  		    	':year' => $e_year));
 		 	$r++;
 		 	}
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

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Edit Registry</title>

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
				$edu_query = "SELECT * FROM education WHERE profile_id = :pid";
				$edu_stmt = $pdo->prepare($edu_query);
				$edu_stmt->execute(array(
				':pid' => $_GET['profile_id']));
				$j = 0;
				while ($edu_row = $edu_stmt->fetch(PDO::FETCH_ASSOC)) {
					$inst = $edu_row['institution_id'];
					$inst_year = $edu_row['year'];

					$inst_query = "SELECT * FROM institution WHERE institution_id = :iid";
					$inst_stmt = $pdo->prepare($inst_query);
					$inst_stmt->execute(array(
						':iid' => $inst
					));
					while ($inst_row = $inst_stmt->fetch(PDO::FETCH_ASSOC)) {
						$inst_name = $inst_row['name'];
						echo "<div class='edu_field row'><div class='col-6'><label for='edu_year{$j}' class='form-label'>Year:</label><input class='form-control' id='edu_year{$j}' maxlength='4' type='text' name='edu_year{$j}' value='{$inst_year}'></div><div class='col-6'><label for='edu_school{$j}' class='form-label'>Institution:</label><input class='school form-control' type='text' name='edu_school{$j}' rows='1' cols='60' value='{$inst_name}'></div><div class='col-12'><button class='edu_rm form-control btn btn-sm btn-danger mt-3' type='button'><span class='fas fa-trash'></span></button></div></div>";
						$j++;
					}
				}
				?>

			</div>

        <div class="col-sm-2">
          <label for="addPost" class="form-label">Position:</label>
          <input class="form-control btn btn-success btn-sm" id="addPost" type="submit" name="addPost" value="+">
        </div>

      <div class="col-sm-12" id="position_fields">
				<script type="text/javascript" src="js/position.js"></script>
				<?php
				$query = "SELECT * FROM position WHERE profile_id = :pid";
				$stmt = $pdo->prepare($query);
				$stmt->execute(array(
				':pid' => $_GET['profile_id']));
				$i = 0;
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$year = $row['year'];
					$desc = $row['description'];
					echo "<div class='position_field'><label for='year{$i}' class='form-label'>Year:</label><input id='year{$i}' class='form-control col-6' maxlength='4' type='text' name='year{$i}' value='{$year}'><textarea class='form-control' name='desc{$i}' rows='8' cols='80'>{$desc}</textarea><button class='col-6 form-control position_remove btn btn-sm btn-danger mt-2' type='button'><span class='fas fa-trash'></span></button></div>";
					$i++;
				}
				?>
			</div>
			<div class="row justify-content-around m-auto mt-5">
				<input class="col-6 mb-3 btn btn-primary" type="submit" name="add" value="Update">
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