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

       //data insertion
    //School Selection
    //
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

    	if (!isset($edu_id)){
    		$_SESSION['error'] = "Institution not recognized";
    		header("Location: add.php");
    		return;
    	}
    	  
    
    	}
    
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
	if (isset($edu_id)) {
    		$stmt = $pdo->prepare("INSERT INTO education (profile_id, institution_id, rank, year) VALUES (:pid, :inst, :rank, :year)");
    		$stmt->execute(array(
    			':pid' => $profile_id,
    			':inst' => $edu_id,
    			':rank' => $rank_2,
    			':year' => $edu_year));
    		$rank_2++;  
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
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>

<script
  src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"
  integrity="sha256-xLD7nhI62fcsEZK2/v8LsBcb4lG7dgULkuXoXB/j91c="
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
		<form method="post">
			<p>First Name: <input type="text" name="first_name"></p>
			<p>Last Name: <input type="text" name="last_name"></p>
			<p>Email: <input type="text" name="email"></p>
			<p>Headline: <input type="text" name="headline"></p>
			<p>Summary:<br><textarea name="summary" rows="8" cols="80"></textarea></p>
			<p>Education: <input id="addEdu" type="submit" name="add_education" value="+"></p>
			<!-- Add Edu-Fields-->
			<div id="edu_fields">
				<script type="text/javascript" src="js/edu.js"></script>
				<script type="text/javascript" src="js/ajax.js"></script>
			</div>
			<!-- -->
			<p>Position: <input id="addPost" type="submit" name="addPost" value="+"></p>
			<!-- Position Fields -->
			<div id="position_fields">
				<script type="text/javascript" src="js/position.js"></script>
			</div>
			<!-- -->
			<input type="submit" name="add" value="Add">
			<input type="submit" name="cancel" value="Cancel">
			<br>
			<!-- Add Post -->

		</form>
	</div>
<!-- Resources -->
</body>
</html>