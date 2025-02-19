<?php
 
include('../../RedirectModulesInc.php');
$_REQUEST['modfunc'] = 'choose_course';
if(!$_REQUEST['course_period_id'])
	include 'modules/scheduling/CoursesforWindow.php';
else
{
	$course_title = DBGet(DBQuery('SELECT TITLE FROM course_periods WHERE COURSE_PERIOD_ID=\''.$_REQUEST['course_period_id'].'\''));
	$course_title = $course_title[1]['TITLE'] . '<INPUT type=hidden name=w_course_period_id value='.$_REQUEST['course_period_id'].'>';
        echo "<script language=javascript>opener.document.getElementById(\"course_div\").innerHTML = \"$course_title<BR><div class=mb-10><label class=radio-inline><INPUT class=styled type=radio name=w_course_period_id_which value=course_period CHECKED> "._coursePeriod."</label><label class=radio-inline><INPUT class=styled type=radio name=w_course_period_id_which value=course>"._course."</label></div>\"; window.close(); opener.styledCheckboxRadioInit();</script>";
}

?>