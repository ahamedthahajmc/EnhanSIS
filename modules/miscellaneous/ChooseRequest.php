<?php
 
include('../../RedirectModulesInc.php');
$_REQUEST['modfunc'] = 'choose_course';
if(!$_REQUEST['course_id'])
	include 'modules/scheduling/CoursesforWindow.php';
else
{
	$course_title = DBGet(DBQuery('SELECT TITLE FROM courses WHERE COURSE_ID=\''.$_REQUEST['course_id'].'\''));
	$course_title = $course_title[1]['TITLE']. '<INPUT type=hidden name=request_course_id value='.$_REQUEST['course_id'].'>';

	echo "<script language=javascript>opener.document.getElementById(\"request_div\").innerHTML = \"$course_title<div class=mb-10><label class=checkbox-inline><INPUT class=styled type=checkbox name=not_request_course value=Y>"._notRequested."</label></div>\"; window.close(); opener.styledCheckboxRadioInit();</script>";
}

echo '<script src="assets/js/core/libraries/jquery.min.js"></script>';
?>