<?php
 
error_reporting(0);

include('RedirectRootInc.php');
include 'Warehouse.php';
include 'Data.php';
$syear = $_SESSION['UserSyear'];
$flag= FALSE;
$RET=DBGet(DBQuery('SELECT INSTITUTE_ID,INSTITUTE_DATE,COURSE_PERIOD_ID,TEACHER_ID,SECONDARY_TEACHER_ID FROM missing_attendance WHERE SYEAR=\''.  UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\' LIMIT 0,1'));
 if (count($RET))
{
     $flag= TRUE;
 }
$last_update=DBGet(DBQuery('SELECT VALUE FROM program_config WHERE PROGRAM=\'MissingAttendance\' AND TITLE=\'LAST_UPDATE\' AND SYEAR=\''.$syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
$last_update=trim($last_update[1]['VALUE']);

DBQuery("INSERT INTO missing_attendance(INSTITUTE_ID,SYEAR,INSTITUTE_DATE,COURSE_PERIOD_ID,PERIOD_ID,TEACHER_ID,SECONDARY_TEACHER_ID) 
        SELECT s.ID AS INSTITUTE_ID,acc.SYEAR,acc.INSTITUTE_DATE,cp.COURSE_PERIOD_ID,cpv.PERIOD_ID, IF(tra.course_period_id=cp.course_period_id AND acc.institute_date<tra.assign_date =true,tra.pre_teacher_id,cp.teacher_id) AS TEACHER_ID,
        cp.SECONDARY_TEACHER_ID FROM attendance_calendar acc INNER JOIN course_periods cp ON cp.CALENDAR_ID=acc.CALENDAR_ID INNER JOIN course_period_var cpv ON cp.COURSE_PERIOD_ID=cpv.COURSE_PERIOD_ID 
        AND (cpv.COURSE_PERIOD_DATE IS NULL AND position(substring('UMTWHFS' FROM DAYOFWEEK(acc.INSTITUTE_DATE) FOR 1) IN cpv.DAYS)>0 OR cpv.COURSE_PERIOD_DATE IS NOT NULL AND cpv.COURSE_PERIOD_DATE=acc.INSTITUTE_DATE)
        INNER JOIN institutes s ON s.ID=acc.INSTITUTE_ID LEFT JOIN teacher_reassignment tra ON (cp.course_period_id=tra.course_period_id) INNER JOIN schedule sch ON sch.COURSE_PERIOD_ID=cp.COURSE_PERIOD_ID 
        AND sch.student_id IN(SELECT student_id FROM student_enrollment se WHERE sch.institute_id=se.institute_id AND sch.syear=se.syear AND start_date<=acc.institute_date AND (end_date IS NULL OR end_date>=acc.institute_date))
        AND (cp.MARKING_PERIOD_ID IN (SELECT MARKING_PERIOD_ID FROM institute_years WHERE INSTITUTE_ID=acc.INSTITUTE_ID AND acc.INSTITUTE_DATE BETWEEN START_DATE AND END_DATE UNION SELECT MARKING_PERIOD_ID FROM institute_semesters WHERE INSTITUTE_ID=acc.INSTITUTE_ID AND acc.INSTITUTE_DATE BETWEEN START_DATE AND END_DATE UNION SELECT MARKING_PERIOD_ID FROM institute_quarters WHERE INSTITUTE_ID=acc.INSTITUTE_ID AND acc.INSTITUTE_DATE BETWEEN START_DATE AND END_DATE) or cp.MARKING_PERIOD_ID is NULL OR acc.institute_date BETWEEN cp.begin_date AND cp.end_date)
        AND sch.START_DATE<=acc.INSTITUTE_DATE AND (sch.END_DATE IS NULL OR sch.END_DATE>=acc.INSTITUTE_DATE ) AND cpv.DOES_ATTENDANCE='Y' AND acc.INSTITUTE_DATE<=CURDATE() AND acc.INSTITUTE_DATE > '".$last_update."' AND acc.syear=$syear AND acc.INSTITUTE_ID='".UserInstitute()."' 
        AND NOT EXISTS (SELECT '' FROM  attendance_completed ac WHERE ac.INSTITUTE_DATE=acc.INSTITUTE_DATE AND ac.COURSE_PERIOD_ID=cp.COURSE_PERIOD_ID AND ac.PERIOD_ID=cpv.PERIOD_ID)  AND isDateInMarkingPeriodWorkingDates(cp.marking_period_id, acc.INSTITUTE_DATE) 
        GROUP BY acc.INSTITUTE_DATE,cp.COURSE_PERIOD_ID,cpv.PERIOD_ID");

DBQuery("UPDATE program_config SET VALUE=CURDATE() WHERE PROGRAM='MissingAttendance' AND TITLE='LAST_UPDATE'");

$RET=DBGet(DBQuery("SELECT INSTITUTE_ID,INSTITUTE_DATE,COURSE_PERIOD_ID,TEACHER_ID,SECONDARY_TEACHER_ID FROM missing_attendance WHERE SYEAR='".  UserSyear()."' LIMIT 0,1"));
 if (count($RET) && $flag==FALSE)
{
     echo '<span style="display:none">NEW_MI_YES</span>';
 }
if(count($RET))
echo '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>'._missingAttendanceDataListCreated.'.</div>';

?>
