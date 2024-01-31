<?php 
session_start();
require_once "pdo.php";

if (isset($_POST['cancel'])) {
	header("Location: index.php");
	return;
}

//Data Validation
if (isset($_POST['log']) && isset($_POST['email']) && isset($_POST['password'])) {
	$salt = 'XyZzy12*_';
	$email_check = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
	$check = $_POST['password'];
	$check = hash('sha256', $salt.$_POST['password']);
	//Empty field check
	if (strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1) {
		$_SESSION['error'] = "All fields are required";
		header("Location: login.php");
		return;
	}
	//Email error
	if ($email_check == false) {
		$_SESSION['error'] = "Invalid email Adress";
		header("Location: login.php");
		return;
	}
	//Data Selection
		$sql = "SELECT user_id, name FROM users WHERE email = :em AND password = :pw";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':pw' => $check,
			':em' => $email_check));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		//Data Validation
		if ($row !== false) {
			$_SESSION['name'] = $row['name'];
			$_SESSION['user_id'] = $row['user_id'];
			header("Location: index.php");
			return;
		} 
		if ($row == false) {
			$_SESSION['error'] = "Invalid credentials";
			header("Location: login.php");
			return;
		}
	}
?>
<!-- End of the model -->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Login</title>

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
<!-- alternative -->
<main class="form-signin w-100 m-auto">
	<?php
	if (isset($_SESSION['error'])) {
		echo("<div style='color: red;' class='text-center'>".$_SESSION['error']."</div>");
		unset($_SESSION['error']);
	} elseif (isset($_SESSION['succes'])) {
		echo("<div style='color: green;' class='text-center'>".$_SESSION['succes']."</div>");
		unset($_SESSION['succes']);
	}
	?>
	<form class="shadow rounded-3 p-3" method="post">
	<!-- <img class="mb-4" src="/docs/5.3/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> -->
	<h1 class="h3 fw-normal">Resume Registry</h1>
	<p><strong>Please Login</strong></p>

	<div class="form-floating mb-1">
	  <input type="text" class="form-control" id="e-mail" name="email" placeholder="name@example.com">

	  <label for="e-mail">Email address</label>
	</div>

	<div class="form-floating">
	  <input type="password" class="form-control" id="id_1723" name="password" placeholder="Password">
	  <label for="id_1723">Password</label>
	</div>

	<div class="form-check text-start my-3">
	  <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault" disabled>
	  <label class="form-check-label" for="flexCheckDefault">
	    Remember me
	  </label>
	</div>
	<div class="row ms-1 mb-1">
		<input class="btn btn-primary py-2 col-5 btn-sm" type="submit" onclick="return doValidate();" name="log" value="Log In">
	</div>
	<div class="row ms-1">
	<input class="btn btn-danger py-2 col-5 btn-sm" type="submit" name="cancel" value="Cancel">
</div>
	</form>
</main>
<!-- alternative -->

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