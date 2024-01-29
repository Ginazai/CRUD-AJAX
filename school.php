<?php 
session_start();
require_once "pdo.php";

if (! isset($_SESSION['user_id']) || ! isset($_SESSION['name'])) {
	die("UNAUTHORIZED");
}

if (isset($_GET['term'])) {
	$stmt = $pdo->prepare('SELECT name FROM Institution
    WHERE name LIKE :prefix');
	$stmt->execute(array( ':prefix' => $_REQUEST['term']."%"));
	$retval = array();
	while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
   	 $retval[] = $row['name'];
	}
	$save = json_encode($retval, JSON_PRETTY_PRINT); 
	echo($save);
}
?> 
