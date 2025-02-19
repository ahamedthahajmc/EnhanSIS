<?php
 
error_reporting(0);
session_start();
if (( !isset($_SESSION['STAFF_ID']) || !isset($_SESSION['STUDENT_ID'])) && strpos($_SERVER['PHP_SELF'], 'index.php') === false) {
	header('Location: index.php');
	exit;
}
