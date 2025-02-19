<?php
 
	include('RedirectRootInc.php'); 
	include 'Warehouse.php';
	include 'Data.php';
	$v_year = $_SESSION['UserSyear'];

	$flag = $_GET['u'];
	$usr = substr($flag, -4);
	$un = substr($flag, 0, -4);
	if($usr == 'stid')
	{
		$result = DBGet(DBQuery("select s.student_id from students s, student_enrollment se where s.student_id = se.student_id and se.syear = $v_year"));
		
		$xyz = 0;
		foreach ($result as $row)  
		{
		  $unames[$xyz] = $row[0];
		  $xyz++;
		}
	
		if ($un != '') 
		{
			if (in_array ($un, $unames)) 
			{
				echo '0';
			} 
			else 
			{
				echo '1';
			}
			exit;
		}
	}
?>