<?php 
session_start();
require_once "pdo.php";
//Cancel
if (isset($_POST['cancel'])) {
	header("Location: index.php");
	return;
}
//Log Protection
if (! isset($_SESSION['user_id']) && ! isset($_SESSION['name'])) {
	die('<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><h1 class="container" style="color: red; text-align: center;">UNAUTHORIZED</h1>');
}
//Fields validation
if (isset($_POST['add']) && isset($_SESSION['user_id']) && isset($_SESSION['name'])) {
	//Fields Protection 
	if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1|| strlen($_POST['email'])
    < 1 || strlen($_POST['headline']) < 1|| strlen($_POST['summary']) < 1) {
    	$_SESSION['error'] = "All fields are required";
    	header("Location: add.php");
    	return;
	}
	//Email Validation
	$emai_check = $_POST['email'];
	if (! filter_var($emai_check, FILTER_VALIDATE_EMAIL) ) {
		$_SESSION['error'] = "Invalid email adress";
		header("Location: add.php");
		return;
	}
	//Dynamic fields validation
	for ($i=0; $i<=8; $i++) {
      if ( ! isset($_POST['year'.$i]) ) continue;
      if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];
        if ( strlen($year) < 1 || strlen($desc) < 1) {
            $_SESSION['error'] = "All fields are required";
			header("Location: add.php");
			return;
        }
        if ( ! is_numeric($year)) {
            $_SESSION['error'] = "Year must be numeric";
			header("Location: add.php");
			return;
        }
    }

    //Second-time dynamic fields validation
    for ($j = 0; $j <=8; $j++) {
    	if (! isset($_POST['edu_year'.$j])) continue;
      if (! isset($_POST['edu_school'.$j]))  continue;
        $edu_year = $_POST['edu_year'.$j];
        $edu_school = $_POST['edu_school'.$j]; 
        if (strlen($edu_year) < 1 || strlen($edu_school) < 1) {
        	$_SESSION['error'] = "All fields are required";
			header("Location: add.php");
			return;
        }
        if (! is_numeric($edu_year)) {
        	$_SESSION['error'] = "Year must be numeric";
			header("Location: add.php");
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

	//first insertion
	$sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES (:uid, :fn, :ln, :em, :he, :su)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(
		':uid' => $_SESSION['user_id'],
		':fn' => $_POST['first_name'],
		':ln' => $_POST['last_name'],
		':em' => $_POST['email'],
		':he' => $_POST['headline'],
		':su' => $_POST['summary']));
	$profile_id = $pdo->lastInsertId();








	//second insertion
  $rank_2 = 1;
  for ($j = 0; $j <= 8; $j++) {
  	if (! isset($_POST['edu_year'.$j])) continue;
  	if (! isset($_POST['edu_school'.$j])) continue;
  	$edu_year = $_POST['edu_year'.$j];
  	$edu_school = $_POST['edu_school'.$j];
  	//School Selection
  	$var_stmt =$pdo->prepare("SELECT * FROM institution WHERE name = :name");
 		$var_stmt->execute(array(
  	':name' => $edu_school));
  		while ($thisrow = $var_stmt->fetch(PDO::FETCH_ASSOC)) {
  		$edu_id =  $thisrow['institution_id'];
  	}  
  	$stmt = $pdo->prepare("INSERT INTO education (profile_id, institution_id, rank, year) VALUES (:pid, :inst, :rank, :year)");
		$stmt->execute(array(
			':pid' => $profile_id,
			':inst' => $edu_id,
			':rank' => $rank_2,
			':year' => $edu_year));
		$rank_2++; 

  	if (!isset($edu_id)){
  		$_SESSION['error'] = "Institution not recognized";
  		header("Location: add.php");
  		return;
  	}
  }





	//Second-time data
	$rank = 1;
	for($i=0; $i<=8; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];
        //Second-time Insertion
        $stmt = $pdo->prepare('INSERT INTO position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
        $stmt->execute(array(
            ':pid' => $profile_id,
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc)
        );
        $rank++;
    }

    $_SESSION['success'] = "Record added";
    header("Location: index.php");
    return;

   }

?>

<!-- End of the model -->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Add new entry</title>

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
		<h1 class="text-center">Adding Profile</h1>
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
		<form method="post" class="needs-validation add-form m-auto shadow rounded-3">
          <div class="row">

            <div class="col-6">
              <label for="firstName" class="form-label">First name:</label>
              <input id="firstName" type="text" class="form-control" name="first_name" placeholder="">
            </div>

            <div class="col-sm-6">
              <label for="lastName" class="form-label">Last name:</label>
              <input class="form-control" id="lastName" type="text" name="last_name" placeholder="">
            </div>

            <div class="col-sm-12">
              <label for="email" class="form-label">Email:</label>
              <input id="email" class="form-control" type="text" name="email" placeholder="">
            </div>

            <div class="col-sm-12">
              <label for="headline" class="form-label">Headline:</label>
              <input class="form-control" id="headline" type="text" name="headline" placeholder="">
            </div>

            <div class="col-sm-12">
              <label for="summary" class="form-label">Summary:</label>
              <textarea id="summary" class="form-control" name="summary" rows="8" cols="80"></textarea>
            </div>

            <div class="col-sm-2">
              <label for="addEdu" class="form-label">Education:</label>

              <input class="form-control btn btn-success btn-sm" id="addEdu" type="submit" name="add_education" value="+">
            </div>

            <div class="col-sm-12" id="edu_fields">
							<script type="text/javascript" src="js/edu.js"></script>
							<script type="text/javascript" src="js/ajax.js"></script>
						</div>

            <div class="col-sm-2">
              <label for="addPost" class="form-label">Position:</label>
              <input class="form-control btn btn-success btn-sm" id="addPost" type="submit" name="addPost" value="+">
            </div>

            <div class="col-sm-12" id="position_fields">
							<script type="text/javascript" src="js/position.js"></script>
						</div>

			<div class="row justify-content-around m-auto mt-5">
				<input class="col-6 mb-3 btn btn-primary" type="submit" name="add" value="Add">
				<input class="col-6 btn btn-danger" type="submit" name="cancel" value="Cancel">
			</div>
			<br>
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