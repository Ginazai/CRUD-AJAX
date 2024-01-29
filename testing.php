<?php
require_once "pdo.php";
$var_stmt =$pdo->prepare("SELECT * FROM institution WHERE name = 'Duke University'");
$var_stmt->execute(array());
while ($thisrow = $var_stmt->fetch(PDO::FETCH_ASSOC)) {
    $edu_id =  $thisrow['institution_id'];
 }
 echo "$edu_id";
?>