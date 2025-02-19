<?php

include('../../RedirectModulesInc.php');
$next_syear = UserSyear()+1;
$_SESSION['DT'] = $DatabaseType; 
$_SESSION['DS'] = $DatabaseServer; 
$_SESSION['DU'] = $DatabaseUsername; 
$_SESSION['DP'] = $DatabasePassword; 
$_SESSION['DB'] = $DatabaseName; 
$_SESSION['DBP'] = $DatabasePort; 
$_SESSION['NY'] = $next_syear;


$tables = array('staff'=>_users,
'institute_periods'=>_institutePeriods,
'institute_years'=>_markingPeriods,
'institute_calendars'=>_calendars,
'report_card_grade_scales'=>_reportCardGradeCodes,
'courses'=>_coursesBB,
'student_enrollment'=>_students,
'report_card_comments'=>_reportCardCommentCodes,
'eligibility_activities'=>_eligibilityActivityCodes,
'attendance_codes'=>_attendanceCodes,
'student_enrollment_codes'=>_studentEnrollmentCodes,
);
$no_institute_tables = array('student_enrollment_codes'=>true,'staff'=>true);

$table_list = '<TABLE align=center>';
$table_list .= '<tr><td colspan=3 class=clear></td></tr>';
$table_list .= '<tr><td colspan=3>* You <i>must</i> roll users, institute periods, marking periods, calendars, and report card<br>codes at the same time or before rolling courses<BR><BR>* You <i>must</i> roll courses at the same time or before rolling report card comments<BR><BR>Red items have already have data in the next institute year (They might have been rolled).<BR><BR>Rolling red items will delete already existing data in the next institute year.</td></tr>';
foreach($tables as $table=>$name)
{
	$exists_RET[$table] = DBGet(DBQuery('SELECT count(*) AS COUNT from '.$table.' WHERE SYEAR=\''.$next_syear.'\''.(!$no_institute_tables[$table]?' AND INSTITUTE_ID=\''.UserInstitute().'\'':'')));
	if($exists_RET[$table][1]['COUNT']>0)
		$table_list .= '<TR><td width=1%></td><TD width=5%><INPUT type=checkbox value=Y name=tables['.$table.']></TD><TD width=94%>'.$name.' ('.$exists_RET[$table][1]['COUNT'].')</TD></TR>';
	else
		$table_list .= '<TR><td width=1%></td><TD width=5%><INPUT type=checkbox value=Y name=tables['.$table.'] CHECKED></TD><TD width=94%>'.$name.'</TD></TR>';
}
$table_list .= '</TABLE></CENTER><CENTER>';

DrawBC(""._instituteSetup." > ".ProgramTitle());

if(Prompt_rollover('Confirm Rollover',''._areYouSureYouWantToRollTheDataFor.' '.UserSyear().'-'.(UserSyear()+1).' '._toTheNextInstituteYear.'?',$table_list))
{
	if($_REQUEST['tables']['courses'] && ((!$_REQUEST['tables']['staff'] && $exists_RET['staff'][1]['COUNT']<1) || (!$_REQUEST['tables']['institute_periods'] && $exists_RET['institute_periods'][1]['COUNT']<1) || (!$_REQUEST['tables']['institute_years'] && $exists_RET['institute_years'][1]['COUNT']<1) || (!$_REQUEST['tables']['institute_calendars'] && $exists_RET['institute_calendars'][1]['COUNT']<1) || (!$_REQUEST['tables']['report_card_grade_scales'] && $exists_RET['report_card_grade_scales'][1]['COUNT']<1)))
		BackPrompt('You must roll users, institute periods, marking periods, calendars, and report card codes at the same time or before rolling courses.');
	if($_REQUEST['tables']['report_card_comments'] && ((!$_REQUEST['tables']['courses'] && $exists_RET['courses'][1]['COUNT']<1)))
		BackPrompt('You must roll  courses at the same time or before rolling report card comments.');
	if(count($_REQUEST['tables']))
	{
		foreach($_REQUEST['tables'] as $table=>$value)
		{
			
			Rollover($table);
		}
	}
	
	
	DrawHeaderHome('<IMG SRC=assets/check.gif>The data have been rolled.','<input type=button onclick=document.location.href="index.php?modfunc=logout" value="Please login again" class=btn_large >');

	unset($_SESSION['_REQUEST_vars']['tables']);
	unset($_SESSION['_REQUEST_vars']['delete_ok']);	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
}

function Rollover($table)
{	global $next_syear;

	switch($table)
	{
		case 'staff':
			$user_custom='';
			$fields_RET = DBGet(DBQuery("SELECT ID FROM staff_fields"));
			foreach($fields_RET as $field)
				$user_custom .= ',CUSTOM_'.$field['ID'];
			DBQuery('DELETE FROM students_join_users WHERE STAFF_ID IN (SELECT STAFF_ID FROM staff WHERE SYEAR='.$next_syear.')');
			
			DBQuery('DELETE FROM program_user_config WHERE USER_ID IN (SELECT STAFF_ID FROM staff WHERE SYEAR='.$next_syear.')');
			DBQuery('DELETE FROM staff WHERE SYEAR=\''.$next_syear.'\'');

			DBQuery('INSERT INTO staff (SYEAR,CURRENT_INSTITUTE_ID,TITLE,FIRST_NAME,LAST_NAME,MIDDLE_NAME,USERNAME,PASSWORD,PHONE,EMAIL,PROFILE,HOMEROOM,LAST_LOGIN,INSTITUTES,PROFILE_ID,ROLLOVER_ID'.$user_custom.') SELECT SYEAR+1,CURRENT_INSTITUTE_ID,TITLE,FIRST_NAME,LAST_NAME,MIDDLE_NAME,USERNAME,PASSWORD,PHONE,EMAIL,PROFILE,HOMEROOM,NULL,INSTITUTES,PROFILE_ID,STAFF_ID'.$user_custom.' FROM staff WHERE SYEAR=\''.UserSyear().'\'');

			DBQuery('INSERT INTO program_user_config (USER_ID,PROGRAM,TITLE,VALUE) SELECT s.STAFF_ID,puc.PROGRAM,puc.TITLE,puc.VALUE FROM staff s,program_user_config puc WHERE puc.USER_ID=s.ROLLOVER_ID AND puc.PROGRAM=\'Preferences\' AND s.SYEAR=\''.$next_syear.'\'');

			

			DBQuery('INSERT INTO students_join_users (STUDENT_ID,STAFF_ID) SELECT j.STUDENT_ID,s.STAFF_ID FROM staff s,students_join_users j WHERE j.STAFF_ID=s.ROLLOVER_ID AND s.SYEAR=\''.$next_syear.'\'');
		break;

		case 'institute_periods':
			DBQuery('DELETE FROM institute_periods WHERE INSTITUTE_ID=\''.UserInstitute().'\' AND SYEAR=\''.$next_syear.'\'');
			DBQuery('INSERT INTO institute_periods (SYEAR,INSTITUTE_ID,SORT_ORDER,TITLE,SHORT_NAME,LENGTH,ATTENDANCE,ROLLOVER_ID) SELECT SYEAR+1,INSTITUTE_ID,SORT_ORDER,TITLE,SHORT_NAME,LENGTH,ATTENDANCE,PERIOD_ID FROM institute_periods WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
		break;

		case 'institute_calendars':
			DBQuery('DELETE FROM institute_calendars WHERE INSTITUTE_ID=\''.UserInstitute().'\' AND SYEAR=\''.$next_syear.'\'');
			DBQuery('INSERT INTO institute_calendars (SYEAR,INSTITUTE_ID,TITLE,DEFAULT_CALENDAR,ROLLOVER_ID) SELECT SYEAR+1,INSTITUTE_ID,TITLE,DEFAULT_CALENDAR,CALENDAR_ID FROM institute_calendars WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
		break;

		case 'institute_years':
			DBQuery('DELETE FROM institute_progress_periods WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			DBQuery('DELETE FROM institute_quarters WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			DBQuery('DELETE FROM institute_semesters WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			DBQuery('DELETE FROM institute_years WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');

			$r = DBGet(DBQuery('select max(m.marking_period_id) as marking_period_id from (select max(marking_period_id) as marking_period_id from institute_years union select max(marking_period_id) as marking_period_id from institute_semesters union select max(marking_period_id) as marking_period_id from institute_quarters) m'));
			$mpi = $r[1]['MARKING_PERIOD_ID'] + 1;
		        DBQuery('ALTER TABLE marking_period_id_generator AUTO_INCREMENT = '.$mpi.'');
                         
			DBQuery('INSERT INTO institute_years (MARKING_PERIOD_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT '.db_seq_nextval('marking_period_seq').',SYEAR+1,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,date_add(START_DATE,INTERVAL 365 DAY),date_add(END_DATE,INTERVAL 365 DAY),date_add(POST_START_DATE,INTERVAL 365 DAY),date_add(POST_END_DATE,INTERVAL 365 DAY),DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM institute_years WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');

                                                   
                        DBQuery('INSERT INTO institute_semesters (MARKING_PERIOD_ID,YEAR_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT '.db_seq_nextval('marking_period_seq').',(SELECT MARKING_PERIOD_ID FROM institute_years y WHERE y.SYEAR=s.SYEAR+1 AND y.ROLLOVER_ID=s.YEAR_ID),SYEAR+1,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,date_add(START_DATE, INTERVAL 365 DAY),date_add(END_DATE,INTERVAL 365 DAY),date_add(POST_START_DATE,INTERVAL 365 DAY),date_add(POST_END_DATE,INTERVAL 365 DAY),DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM institute_semesters s WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');

                        DBQuery('INSERT INTO institute_quarters (MARKING_PERIOD_ID,SEMESTER_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT '.db_seq_nextval('marking_period_seq').',(SELECT MARKING_PERIOD_ID FROM institute_semesters s WHERE s.SYEAR=q.SYEAR+1 AND s.ROLLOVER_ID=q.SEMESTER_ID),SYEAR+1,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE+365,END_DATE+365,POST_START_DATE+365,POST_END_DATE+365,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM institute_quarters q WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');

                        DBQuery('INSERT INTO institute_progress_periods (MARKING_PERIOD_ID,QUARTER_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT '.db_seq_nextval('marking_period_seq').',(SELECT MARKING_PERIOD_ID FROM institute_quarters q WHERE q.SYEAR=p.SYEAR+1 AND q.ROLLOVER_ID=p.QUARTER_ID),SYEAR+1,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,date_add(START_DATE,INTERVAL 365 DAY),date_add(END_DATE,INTERVAL 365 DAY),date_add(POST_START_DATE,INTERVAL 365 DAY),date_add(POST_END_DATE,INTERVAL 365 DAY),DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM institute_progress_periods p WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
		break;

		case 'courses':
			DBQuery('DELETE FROM course_subjects WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			
			DBQuery('DELETE FROM courses WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			DBQuery('DELETE FROM course_periods WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');

			// ROLL course_subjects
			DBQuery('INSERT INTO course_subjects (SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,ROLLOVER_ID) SELECT SYEAR+1,INSTITUTE_ID,TITLE,SHORT_NAME,SUBJECT_ID FROM course_subjects WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');

			// ROLL COURSE WEIGHTS
			DBQuery('INSERT INTO courses (SYEAR,SUBJECT_ID,INSTITUTE_ID,GRADE_LEVEL,TITLE,SHORT_NAME,ROLLOVER_ID) SELECT SYEAR+1,(SELECT SUBJECT_ID FROM course_subjects s WHERE s.SYEAR=c.SYEAR+1 AND s.ROLLOVER_ID=c.SUBJECT_ID),INSTITUTE_ID,GRADE_LEVEL,TITLE,SHORT_NAME,COURSE_ID FROM courses c WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');

			

			// ROLL course_periods
	
			DBQuery('INSERT INTO course_periods (SYEAR,INSTITUTE_ID,COURSE_ID,COURSE_WEIGHT,TITLE,
SHORT_NAME,PERIOD_ID,MP,MARKING_PERIOD_ID,TEACHER_ID,ROOM,
TOTAL_SEATS,FILLED_SEATS,DOES_ATTENDANCE,GRADE_SCALE_ID,DOES_HONOR_ROLL,
DOES_CLASS_RANK,DOES_BREAKOFF,GENDER_RESTRICTION,HOUSE_RESTRICTION,CREDITS,
AVAILABILITY,DAYS,HALF_DAY,PARENT_ID,CALENDAR_ID,
ROLLOVER_ID) SELECT SYEAR+1,INSTITUTE_ID,
(SELECT COURSE_ID FROM courses c WHERE c.SYEAR=p.SYEAR+1 AND c.ROLLOVER_ID=p.COURSE_ID),
COURSE_WEIGHT,TITLE,SHORT_NAME,(SELECT PERIOD_ID FROM institute_periods n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.PERIOD_ID),MP,'.db_case(array('MP',"'FY'",'(SELECT MARKING_PERIOD_ID FROM institute_years n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)',"'SEM'",'(SELECT MARKING_PERIOD_ID FROM institute_semesters n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)',"'QTR'",'(SELECT MARKING_PERIOD_ID FROM institute_quarters n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)')).',(SELECT STAFF_ID FROM staff n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.TEACHER_ID),ROOM,TOTAL_SEATS,0 AS FILLED_SEATS,DOES_ATTENDANCE,(SELECT ID FROM report_card_grade_scales n WHERE n.ROLLOVER_ID=p.GRADE_SCALE_ID AND n.INSTITUTE_ID='.UserInstitute().'),DOES_HONOR_ROLL,DOES_CLASS_RANK,DOES_BREAKOFF,GENDER_RESTRICTION,HOUSE_RESTRICTION,CREDITS,AVAILABILITY,DAYS,HALF_DAY,PARENT_ID,(SELECT CALENDAR_ID FROM institute_calendars n WHERE n.ROLLOVER_ID=p.CALENDAR_ID),COURSE_PERIOD_ID FROM course_periods p WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');

			$rowq=DBQUERY('SELECT * FROM course_periods  WHERE ROLLOVER_ID=PARENT_ID');
			DBQuery('UPDATE course_periods SET PARENT_ID=\''.$rowq['course_period_id'].'\' WHERE PARENT_ID IS NOT NULL AND SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
		break;

		case 'student_enrollment':
			$next_start_date = DBDate();
			DBQuery('DELETE FROM student_enrollment WHERE SYEAR=\''.$next_syear.'\' AND LAST_INSTITUTE=\''.UserInstitute().'\'');
			// ROLL STUDENTS TO NEXT GRADE
			DBQuery('INSERT INTO student_enrollment (SYEAR,INSTITUTE_ID,STUDENT_ID,GRADE_ID,START_DATE,END_DATE,ENROLLMENT_CODE,DROP_CODE,CALENDAR_ID,LAST_INSTITUTE) SELECT SYEAR+1,INSTITUTE_ID,STUDENT_ID,(SELECT NEXT_GRADE_ID FROM institute_gradelevels g WHERE g.ID=e.GRADE_ID),\''.$next_start_date.'\' AS START_DATE,NULL AS END_DATE,NULL AS ENROLLMENT_CODE,NULL AS DROP_CODE,(SELECT CALENDAR_ID FROM institute_calendars WHERE ROLLOVER_ID=e.CALENDAR_ID),INSTITUTE_ID FROM student_enrollment e WHERE e.SYEAR=\''.UserSyear().'\' AND e.INSTITUTE_ID=\''.UserInstitute().'\' AND ((\''.DBDate().'\' BETWEEN e.START_DATE AND e.END_DATE OR e.END_DATE IS NULL) AND \''.DBDate().'\'>=e.START_DATE) AND e.NEXT_INSTITUTE=\''.UserInstitute().'\'');

			// ROLL STUDENTS WHO ARE TO BE RETAINED
			DBQuery('INSERT INTO student_enrollment (SYEAR,INSTITUTE_ID,STUDENT_ID,GRADE_ID,START_DATE,END_DATE,ENROLLMENT_CODE,DROP_CODE,CALENDAR_ID,LAST_INSTITUTE) SELECT SYEAR+1,INSTITUTE_ID,STUDENT_ID,GRADE_ID,\''.$next_start_date.'\' AS START_DATE,NULL AS END_DATE,NULL AS ENROLLMENT_CODE,NULL AS DROP_CODE,(SELECT CALENDAR_ID FROM institute_calendars WHERE ROLLOVER_ID=e.CALENDAR_ID),INSTITUTE_ID FROM student_enrollment e WHERE e.SYEAR=\''.UserSyear().'\' AND e.INSTITUTE_ID=\''.UserInstitute().'\' AND ((\''.DBDate().'\' BETWEEN e.START_DATE AND e.END_DATE OR e.END_DATE IS NULL) AND \''.DBDate().'\'>=e.START_DATE) AND e.NEXT_INSTITUTE=\'0\'');

			// ROLL STUDENTS TO NEXT INSTITUTE
			DBQuery('INSERT INTO student_enrollment (SYEAR,INSTITUTE_ID,STUDENT_ID,GRADE_ID,START_DATE,END_DATE,ENROLLMENT_CODE,DROP_CODE,CALENDAR_ID,LAST_INSTITUTE) SELECT SYEAR+1,NEXT_INSTITUTE,STUDENT_ID,(SELECT g.ID FROM institute_gradelevels g WHERE g.SORT_ORDER=1 AND g.INSTITUTE_ID=e.NEXT_INSTITUTE),\''.$next_start_date.'\' AS START_DATE,NULL AS END_DATE,NULL AS ENROLLMENT_CODE,NULL AS DROP_CODE,(SELECT CALENDAR_ID FROM institute_calendars WHERE ROLLOVER_ID=e.CALENDAR_ID),INSTITUTE_ID FROM student_enrollment e WHERE e.SYEAR=\''.UserSyear().'\' AND e.INSTITUTE_ID=\''.UserInstitute().'\' AND ((\''.DBDate().'\' BETWEEN e.START_DATE AND e.END_DATE OR e.END_DATE IS NULL) AND \''.DBDate().'\'>=e.START_DATE) AND e.NEXT_INSTITUTE NOT IN (\''.UserInstitute().'\',\'0\',\'-1\')');
		break;

		case 'report_card_grade_scales':
			DBQuery('DELETE FROM report_card_grade_scales WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			DBQuery('DELETE FROM report_card_grades WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			
                        DBQuery('INSERT INTO report_card_grade_scales (SYEAR,INSTITUTE_ID,TITLE,COMMENT,SORT_ORDER,ROLLOVER_ID) SELECT SYEAR+1,INSTITUTE_ID,TITLE,COMMENT,SORT_ORDER,ID FROM report_card_grade_scales WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			DBQuery('INSERT INTO report_card_grades (SYEAR,INSTITUTE_ID,TITLE,COMMENT,BREAK_OFF,GPA_VALUE,GRADE_SCALE_ID,SORT_ORDER) SELECT SYEAR+1,INSTITUTE_ID,TITLE,COMMENT,BREAK_OFF,GPA_VALUE,(SELECT ID FROM report_card_grade_scales WHERE ROLLOVER_ID=GRADE_SCALE_ID AND INSTITUTE_ID=report_card_grades.INSTITUTE_ID),SORT_ORDER FROM report_card_grades WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
		break;

		case 'report_card_comments':
			DBQuery('DELETE FROM report_card_comments WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			DBQuery('INSERT INTO report_card_comments (SYEAR,INSTITUTE_ID,TITLE,SORT_ORDER,COURSE_ID) SELECT SYEAR+1,INSTITUTE_ID,TITLE,SORT_ORDER,'.db_case(array('COURSE_ID',"''",'NULL',"(SELECT COURSE_ID FROM courses WHERE ROLLOVER_ID=rc.COURSE_ID)")).' FROM report_card_comments rc WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
		break;

		case 'eligibility_activities':
		case 'attendance_codes':
			DBQuery('DELETE FROM '.$table.' WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			$table_properties = db_properties($table);
			$columns = '';
			foreach($table_properties as $column=>$values)
			{
				if($column!='ID' && $column!='SYEAR')
					$columns .= ','.$column;
			}
			DBQuery('INSERT INTO '.$table.' (SYEAR'.$columns.') SELECT SYEAR+1'.$columns.' FROM '.$table.' WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
		break;

		// DOESN'T HAVE A INSTITUTE_ID
		case 'student_enrollment_codes':
			DBQuery('DELETE FROM '.$table.' WHERE SYEAR=\''.$next_syear.'\'');
			$table_properties = db_properties($table);
			$columns = '';
			foreach($table_properties as $column=>$values)
			{
				if($column!='ID' && $column!='SYEAR')
					$columns .= ','.$column;
			}
			DBQuery('INSERT INTO '.$table.' (SYEAR'.$columns.') SELECT SYEAR+1'.$columns.' FROM '.$table.' WHERE SYEAR=\''.UserSyear().'\'');
		break;
	}
	

		// ---------------------------------------------------------------------- data write start ----------------------------------------------------------------------- //
			$string .= "<"."?php \n";
			$string .= "$"."DatabaseType = '".$_SESSION['DT']."'; \n"	;
			$string .= "$"."DatabaseServer = '".$_SESSION['DS']."'; \n"	;
			$string .= "$"."DatabaseUsername = '".$_SESSION['DU']."'; \n" ;
			$string .= "$"."DatabasePassword = '".$_SESSION['DP']."'; \n";
			$string .= "$"."DatabaseName = '".$_SESSION['DB']."'; \n";
			$string .= "$"."DatabasePort = '".$_SESSION['DBP'] ."'; \n";
			$string .= "$"."DefaultSyear = '".$_SESSION['NY']."'; \n";
			$string .="?".">";
			
			$err = "Can't write to file";
			
			$myFile = "Data.php";
			$fh = fopen($myFile, 'w') or exit($err);
			fwrite($fh, $string);
			fclose($fh);
		// ---------------------------------------------------------------------- data write end ------------------------------------------------------------------------ //
		
	
}

?>