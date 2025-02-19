<?php 
 

error_reporting(0);
session_start();
      if(!$_SESSION['STAFF_ID'] && !$_SESSION['STUDENT_ID'] && (strpos($_SERVER['PHP_SELF'],'index.php'))===false)
	{
		header('Location: index.php');
		exit;
	}
