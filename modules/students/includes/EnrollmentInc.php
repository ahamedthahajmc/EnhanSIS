<?php

include('../../../RedirectIncludes.php');
include_once('modules/students/includes/FunctionsInc.php');

if(($_REQUEST['month_values'] && ($_POST['month_values'] || $_REQUEST['ajax'])) || ($_REQUEST['values']['student_enrollment'] && ($_POST['values']['student_enrollment'] || $_REQUEST['ajax'])))
{
	if(!$_REQUEST['values']['student_enrollment']['new']['ENROLLMENT_CODE'] && !$_REQUEST['month_values']['student_enrollment']['new']['START_DATE'])
	{
		unset($_REQUEST['values']['student_enrollment']['new']);
		unset($_REQUEST['day_values']['student_enrollment']['new']);
		unset($_REQUEST['month_values']['student_enrollment']['new']);
		unset($_REQUEST['year_values']['student_enrollment']['new']);
	}
	else
	{
		$date = $_REQUEST['day_values']['student_enrollment']['new']['START_DATE'].'-'.$_REQUEST['month_values']['student_enrollment']['new']['START_DATE'].'-'.$_REQUEST['year_values']['student_enrollment']['new']['START_DATE'];
		$found_RET = DBGet(DBQuery("SELECT ID FROM student_enrollment WHERE STUDENT_ID='".UserStudentID()."' AND SYEAR='".UserSyear()."' AND '" . date("Y-m-d",strtotime($date))."' BETWEEN START_DATE AND END_DATE"));
		if(count($found_RET))
		{
			unset($_REQUEST['values']['student_enrollment']['new']);
			unset($_REQUEST['day_values']['student_enrollment']['new']);
			unset($_REQUEST['month_values']['student_enrollment']['new']);
			unset($_REQUEST['year_values']['student_enrollment']['new']);
			echo ErrorMessage(array(_theStudentIsAlreadyEnrolledOnThatDateAndCouldNotBeEnrolledASecondTimeOnTheDateYouSpecifiedPleaseFixAndTryEnrollingTheStudentAgain));
		}
	}

	$iu_extra['student_enrollment'] = "STUDENT_ID='".UserStudentID()."' AND ID='__ID__'";
	$iu_extra['fields']['student_enrollment'] = 'SYEAR,STUDENT_ID,';
	$iu_extra['values']['student_enrollment'] = "'".UserSyear()."','".UserStudentID()."',";
	if(!$new_student)
		SaveData($iu_extra,'',$field_names);
}

$functions = array('START_DATE'=>'_makeStartInput','END_DATE'=>'_makeEndInput','INSTITUTE_ID'=>'_makeInstituteInput');
unset($THIS_RET);
$RET = DBGet(DBQuery('SELECT e.ID,e.ENROLLMENT_CODE,e.START_DATE,e.DROP_CODE,e.END_DATE,e.END_DATE AS END,e.INSTITUTE_ID,e.NEXT_INSTITUTE,e.CALENDAR_ID FROM student_enrollment e WHERE e.STUDENT_ID=\''.UserStudentID().'\' AND e.SYEAR=\''.UserSyear().'\' ORDER BY e.START_DATE'),$functions);

$add = true;
if(count($RET))
{
	foreach($RET as $value)
	{
		if($value['DROP_CODE']=='' || !$value['DROP_CODE'])
			$add = false;
	}
}
if($add)
	$link['add']['html'] = array('START_DATE'=>_makeStartInput('','START_DATE'),'INSTITUTE_ID'=>_makeInstituteInput('','INSTITUTE_ID'));

$columns = array('START_DATE'=>'Attendance Start Date this Institute Year','END_DATE'=>'Dropped','INSTITUTE_ID'=>'Institute');

$institutes_RET = DBGet(DBQuery('SELECT ID,TITLE FROM institutes WHERE ID!=\''.UserInstitute().'\''));
$next_institute_options = array(UserInstitute()=>_nextGradeAtCurrentInstitute,
'0'=>_retain,
'-1'=>_doNotEnrollAfterThisInstituteYear,
);
if(count($institutes_RET))
{
	foreach($institutes_RET as $institute)
		$next_institute_options[$institute['ID']] = $institute['TITLE'];
}

$calendars_RET = DBGet(DBQuery('SELECT CALENDAR_ID,DEFAULT_CALENDAR,TITLE FROM institute_calendars WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\' ORDER BY DEFAULT_CALENDAR ASC'));
if(count($calendars_RET))
{
	foreach($calendars_RET as $calendar)
		$calendar_options[$calendar['CALENDAR_ID']] = $calendar['TITLE'];
}
if($_REQUEST['student_id']!='new')
{
	if(count($RET))
		$id = $RET[count($RET)]['ID'];
	else
		$id = 'new';

	ListOutput($RET,$columns,_enrollmentRecord,_enrollmentRecords,$link);
	if($id!='new')
		$next_institute = $RET[count($RET)]['NEXT_INSTITUTE'];
	if($id!='new')
		$calendar = $RET[count($RET)]['CALENDAR_ID'];
	$div = true;
}
else
{
 	$id = 'new';
	ListOutputMod($RET,$columns,enrollmentRecord,enrollmentRecords,$link,array(),array('count'=>false));
	$next_institute = UserInstitute();
	$calendar = $calendars_RET[1]['CALENDAR_ID'];
	$div = false;
}
echo '<CENTER><TABLE><TR><TD>'.SelectInput($calendar,"values[student_enrollment][$id][CALENDAR_ID]",(!$calendar||!$div?'<FONT color=red>':'').'Calendar'.(!$calendar||!$div?'</FONT>':''),$calendar_options,false,'',$div).'</TD><TD width=30></TD><TD>'.SelectInput($next_institute,"values[student_enrollment][$id][NEXT_INSTITUTE]",(!$next_institute||!$div?'<FONT color=red>':'').'Rolling / Retention Options'.(!$next_institute||!$div?'</FONT>':''),$next_institute_options,false,'',$div).'</TD></TR></TABLE></CENTER>';
 
?>