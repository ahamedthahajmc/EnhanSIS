<?php
 
include('../../RedirectModulesInc.php');
DrawHeader(ProgramTitle());
Widgets('request');
if(!UserStudentID())
	echo '<BR>';
Search('student_id',$extra);
if(!$_REQUEST['modfunc'] && UserStudentID())
	$_REQUEST['modfunc'] = 'choose';
if($_REQUEST['modfunc']=='verify')
{
	$QI = DBQuery('SELECT TITLE,COURSE_ID,SUBJECT_ID FROM courses WHERE INSTITUTE_ID=\''.UserInstitute().'\' AND SYEAR=\''.UserSyear().'\'');
	$courses_RET = DBGet($QI,array(),array('COURSE_ID'));
	DBQuery('DELETE FROM schedule_requests WHERE STUDENT_ID=\''.UserStudentID().'\' AND SYEAR=\''.UserSyear().'\'');
	foreach($_REQUEST['courses'] as $subject=>$courses)
	{
		$courses_count = count($courses);
		for($i=0;$i<$courses_count;$i++)
		{
			$course = $courses[$i];
			if(!$course)
				continue;
			$sql = 'INSERT INTO schedule_requests (SYEAR,INSTITUTE_ID,STUDENT_ID,SUBJECT_ID,COURSE_ID,MARKING_PERIOD_ID,WITH_TEACHER_ID,NOT_TEACHER_ID,WITH_PERIOD_ID,NOT_PERIOD_ID)
						values(\''.UserSyear().'\',\''.UserInstitute().'\',\''.UserStudentID().'\',\''.$courses_RET[$course][1]['SUBJECT_ID'].'\',\''.$course.'\',NULL,\''.$_REQUEST['with_teacher'][$subject][$i].'\',\''.$_REQUEST['without_teacher'][$subject][$i].'\',\''.$_REQUEST['with_period'][$subject][$i].'\',\''.$_REQUEST['without_period'][$subject][$i].'\')';
			DBQuery($sql);
		}
	}
	echo ErrorMessage($error,'Error');
	$_SCHEDULER['student_id'] = UserStudentID();
	$_SCHEDULER['dont_run'] = true;
	include('modules/scheduling/Scheduler.php');
	$_REQUEST['modfunc'] = 'choose';
}
if($_REQUEST['modfunc']=='choose')
{
	$functions = array('WITH_PERIOD_ID'=>'_makeWithSelects','NOT_PERIOD_ID'=>'_makeWithoutSelects');
	$requests_RET = DBGet(DBQuery('SELECT sr.COURSE_ID,c.COURSE_TITLE,sr.WITH_PERIOD_ID,sr.NOT_PERIOD_ID,sr.WITH_TEACHER_ID,
										sr.NOT_TEACHER_ID FROM schedule_requests sr,courses c
									WHERE sr.SYEAR=\''.UserSyear().'\' AND sr.STUDENT_ID=\''.UserStudentID().'\' AND sr.COURSE_ID=c.COURSE_ID'),$functions);

	echo "<FORM name=vary id=vary action=Modules.php?modname=".strip_tags(trim($_REQUEST[modname]))."&modfunc=verify method=POST>";
	DrawHeader('',SubmitButton(_save,'','class="btn btn-primary" onclick=\'formload_ajax("vary");\''));

	$columns = array('');
	ListOutput($requests_RET,$columns,_request,_requests);

	echo '<CENTER>'.SubmitButton(_save,'','class="btn btn-primary" onclick=\'formload_ajax("vary");\'').'</CENTER></FORM>';
}
?>
