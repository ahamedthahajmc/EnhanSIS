<?php
 
// config variables for InputFinalGrades.php
include('../../RedirectModulesInc.php');
if(1)
$commentsA_select = array('+'=>array('+','<img src=assets/plus.gif>',_exceptional),
			  ' '=>array('&middot;','<img src=assets/dot.gif>',_satisfactory),
			  '-'=>array('-','<img src=assets/minus.gif>',_needsImprovement));
else
$commentsA_select = array('1'=>array('1 - '._excellent.'',_excellent,_excellent),
			  '2'=>array('2 - '._veryGood.'',_veryGood,_veryGood),
			  '3'=>array('3 - '._good.'',_good,_good),
			  '4'=>array('4 - '._fair.'',_fair,_fair),
			  '5'=>array('5 - '._poor.'',_poor,_poor),
			  '6'=>array('6 - '._failure.'...',''._failure.'...',f_ailureDueToPoorAttendance));

// config variables for StudentGrades.php
// set this to false to disable anonamous grade statistics for parents and students
$do_stats = true;
// remove this line if you don't want teachers to have 'em either
$do_stats |= User('PROFILE')=='teacher';
?>