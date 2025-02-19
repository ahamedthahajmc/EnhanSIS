<?php

include('../../RedirectModulesInc.php');
echo "<FORM name=add id=add action=".PreparePHP_SELF()." method=POST>";
DrawBC(""._students." > ".ProgramTitle());
if($_REQUEST['day__start'] && $_REQUEST['month__start'] && $_REQUEST['year__start'])
{
	while(!VerifyDate($start_date = $_REQUEST['day__start'].'-'.$_REQUEST['month__start'].'-'.$_REQUEST['year__start']))
		$_REQUEST['day__start']--;
        
        
}
else
	$start_date = date('Y-m').'-01';
if($_REQUEST['day__end'] && $_REQUEST['month__end'] && $_REQUEST['year__end'])
{
	while(!VerifyDate($end_date = $_REQUEST['day__end'].'-'.$_REQUEST['month__end'].'-'.$_REQUEST['year__end']))
		$_REQUEST['day__end']--;
}
else
	$end_date = DBDate('mysql');


$start_date=date('Y-m-d',strtotime($start_date));
$end_date=date('Y-m-d',strtotime($end_date));
echo '<div class="panel panel-default">';
echo '<div class="panel-body"><div class="form-inline"><div class="row"><div class="col-md-12">'.PrepareDateSchedule($start_date,'_start').' &nbsp; <label class="control-label"> &nbsp; - &nbsp; </label> &nbsp; '.PrepareDateSchedule($end_date,'_end'),' &nbsp; <INPUT type=submit class="btn btn-primary" value='._go.'></div></div></div></div>';
echo '</div>';
echo '</FORM>';

$enrollment_RET = DBGet(DBQuery('SELECT se.START_DATE,se.END_DATE,se.START_DATE AS DATE,se.INSTITUTE_ID,se.STUDENT_ID,CONCAT(s.LAST_NAME,\', \',s.FIRST_NAME) AS FULL_NAME,(SELECT TITLE FROM student_enrollment_codes seci WHERE se.enrollment_code=seci.id AND se.START_DATE>=\''.$start_date.'\') AS ENROLLMENT_CODE,(SELECT TITLE FROM student_enrollment_codes seci WHERE se.drop_code=seci.id) AS DROP_CODE FROM student_enrollment se, students s WHERE s.STUDENT_ID=se.STUDENT_ID AND ((se.START_DATE>=\''.$start_date.'\' AND se.END_DATE<=\''.$end_date.'\') OR (se.START_DATE BETWEEN \''.$start_date.'\' AND \''.$end_date.'\') OR (se.END_DATE BETWEEN \''.$start_date.'\' AND \''.$end_date.'\')) AND se.INSTITUTE_ID = ' . UserInstitute() . ' ORDER BY DATE DESC'),array('START_DATE'=>'ProperDate'
								,'END_DATE'=>'ProperDate'
								,'INSTITUTE_ID'=>'GetInstitute'));

$columns = array('FULL_NAME'=>_student,
'STUDENT_ID'=>_studentId,
'INSTITUTE_ID'=>_institute,
'START_DATE'=>_enrolled,
'ENROLLMENT_CODE'=>_enrollmentCode,
'END_DATE'=>_dropped,
'DROP_CODE'=>_dropCode,
);

echo '<div class="panel panel-default">';
ListOutput($enrollment_RET,$columns,_enrollmentRecord,_enrollmentRecords);
echo '</div>';
?>