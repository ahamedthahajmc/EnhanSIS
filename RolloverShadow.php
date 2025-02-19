<?php
 
include('RedirectRootInc.php');
include('Warehouse.php');
$next_syear=$_SESSION['NY'];
$table=$_REQUEST['table_name'];
$next_start_date=$_SESSION['roll_start_date'];
$next_s_start_date=$_SESSION['roll_s_start_date'];
$next_s_end_date=$_SESSION['roll_s_end_date'];
//exit;
$tables = array('staff'=>'users','institute_periods'=>'Institute Periods','institute_years'=>'Marking Periods','institute_calendars'=>'Calendars','report_card_grade_scales'=>'Report Card Grade Codes','course_subjects'=>'Subjects','courses'=>'Courses','course_periods'=>'Course Periods','student_enrollment'=>'Students','honor_roll'=>'Honor Roll Setup','attendance_codes'=>'Attendance Codes','student_enrollment_codes'=>'Student Enrollment Codes','report_card_comments'=>'Report Card Comment Codes','NONE'=>'none');
$tablesDisplay = array(
    'staff'=>_users,
    'institute_periods'=>_institutePeriods,
    'institute_years'=>_markingPeriods,
    'institute_calendars'=>_calendars,
    'report_card_grade_scales'=>_reportCardGradeCodes,
    'course_subjects'=>_subjects,
    'courses'=>_courses,
    'course_periods'=>_coursePeriods,
    'student_enrollment'=>_students,
    'honor_roll'=>_honorRollSetup,
    'attendance_codes'=>_attendanceCodes,
    'student_enrollment_codes'=>_studentEnrollmentCodes,
    'report_card_comments'=>_reportCardCommentCodes,
    'NONE'=>_none,
);
$no_institute_tables = array('student_enrollment_codes'=>true,'staff'=>true);
switch($table)
{
		case 'staff':
		

                        DBQuery('DELETE FROM staff_institute_relationship WHERE institute_id=\''.  UserInstitute().'\' AND syear=\''.$next_syear.'\'');
                        DBQuery('INSERT INTO staff_institute_relationship (staff_id,institute_id,syear,start_date) SELECT staff_id,institute_id,syear+1,start_date +INTERVAL 1 YEAR FROM staff_institute_relationship WHERE institute_id=\''.  UserInstitute().'\' AND syear=\''. UserSyear().'\'');
                        $exists_RET[$table] = DBGet(DBQuery('SELECT count(*) AS COUNT from staff_institute_relationship WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
                        $total_rolled_data=$exists_RET[$table][1]['COUNT'];
                        echo $tables['staff'].'|'.'(|'.$total_rolled_data.'|)|'.$tablesDisplay[$table];
                    break;

		case 'institute_periods':
                            
                        DBQuery('DELETE FROM institute_periods WHERE INSTITUTE_ID=\''.UserInstitute().'\' AND SYEAR=\''.$next_syear.'\'');
                        DBQuery('INSERT INTO institute_periods (SYEAR,INSTITUTE_ID,SORT_ORDER,TITLE,SHORT_NAME,LENGTH,ATTENDANCE,ROLLOVER_ID,START_TIME,END_TIME) SELECT SYEAR+1,INSTITUTE_ID,SORT_ORDER,TITLE,SHORT_NAME,LENGTH,ATTENDANCE,PERIOD_ID,START_TIME,END_TIME FROM institute_periods WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
                        $exists_RET[$table] = DBGet(DBQuery('SELECT count(*) AS COUNT from '.$table.' WHERE SYEAR=\''.$next_syear.'\''.(!$no_institute_tables[$table]?' AND INSTITUTE_ID=\''.UserInstitute().'\'':'')));
                        $total_rolled_data=$exists_RET[$table][1]['COUNT'];
                        echo $tables['institute_periods'].'|'.'(|'.$total_rolled_data.'|)|'.$tablesDisplay[$table];
                    break;
		
		case 'institute_calendars':
                        
			DBQuery('DELETE FROM institute_calendars WHERE INSTITUTE_ID=\''.UserInstitute().'\' AND SYEAR=\''.$next_syear.'\'');

                        DBQuery("INSERT INTO institute_calendars (SYEAR,INSTITUTE_ID,TITLE,DEFAULT_CALENDAR,DAYS,ROLLOVER_ID) SELECT SYEAR+1,INSTITUTE_ID,CONCAT(TITLE,'_',SYEAR+1),DEFAULT_CALENDAR,DAYS,CALENDAR_ID FROM institute_calendars WHERE SYEAR='".UserSyear()."' AND INSTITUTE_ID='".UserInstitute()."'");
                      
                        //------------------newly added-------------------
                        DBQuery('DELETE FROM attendance_calendar WHERE INSTITUTE_ID=\''.UserInstitute().'\' AND SYEAR=\''.$next_syear.'\'');
                       
                        DBQuery('DELETE FROM calendar_events WHERE INSTITUTE_ID=\''.UserInstitute().'\' AND SYEAR=\''.$next_syear.'\'');

         
$calendars_RET = DBGet(DBQuery('SELECT CALENDAR_ID,ROLLOVER_ID FROM institute_calendars WHERE INSTITUTE_ID=\''.UserInstitute().'\' AND SYEAR=\''.$next_syear.'\''));
                      
                       foreach($calendars_RET as $calendar)
                        {

                            roll_given_date('attendance_calendar',$calendar['CALENDAR_ID'],$calendar['ROLLOVER_ID']);
                            roll_given_date('calendar_events',$calendar['CALENDAR_ID'],$calendar['ROLLOVER_ID']);

                        }

                        $exists_RET[$table] = DBGet(DBQuery('SELECT count(*) AS COUNT from '.$table.' WHERE SYEAR=\''.$next_syear.'\''.(!$no_institute_tables[$table]?' AND INSTITUTE_ID=\''.UserInstitute().'\'':'')));
                        $total_rolled_data=$exists_RET[$table][1]['COUNT'];
                        
                        echo $tables['institute_calendars'].'|'.'(|'.$total_rolled_data.'|)|'.$tablesDisplay[$table];                                      //-------------------end--------------------------------
                    break;

		case 'institute_years':
                       $rollover_shadow_institute_yr= "";
			DBQuery('DELETE FROM institute_progress_periods WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			DBQuery('DELETE FROM institute_quarters WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			DBQuery('DELETE FROM institute_semesters WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			DBQuery('DELETE FROM institute_years WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			$r = DBGet(DBQuery('select max(m.marking_period_id) as marking_period_id from (select max(marking_period_id) as marking_period_id from institute_years union select max(marking_period_id) as marking_period_id from institute_semesters union select max(marking_period_id) as marking_period_id from institute_quarters) m'));
			$mpi = $r[1]['MARKING_PERIOD_ID'] + 1;
		        DBQuery('ALTER TABLE marking_period_id_generator AUTO_INCREMENT = '.$mpi.'');
                         
			if($_SESSION['custom_date']=='Y')
                        {
                            $get_sch_yr=DBGet(DBQuery('SELECT * FROM institute_years WHERE INSTITUTE_ID=\''.UserInstitute().'\' AND SYEAR=\''.UserSyear().'\' '));
                            $next_mp_id=DBGet(DBQuery('SELECT '.db_seq_nextval('marking_period_seq').' as SEQ'));
                            DBQuery('INSERT INTO institute_years (MARKING_PERIOD_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) VALUES (\''.$next_mp_id[1]['SEQ'].'\',\''.(UserSyear()+1).'\',\''.UserInstitute().'\',\''. $get_sch_yr[1]['TITLE'].'\',\''. $get_sch_yr[1]['SHORT_NAME'].'\',\''. $get_sch_yr[1]['SORT_ORDER'].'\',\''.$next_s_start_date.'\',\''.$next_s_end_date.'\',\''.$next_s_start_date.'\',\''.$next_s_end_date.'\',\''. $get_sch_yr[1]['DOES_GRADES'].'\',\''. $get_sch_yr[1]['DOES_EXAM'].'\',\''. $get_sch_yr[1]['DOES_COMMENTS'].'\',\''.$get_sch_yr[1]['MARKING_PERIOD_ID'].'\')');
                            if($_SESSION['total_sem']!='' && $_SESSION['total_sem']!=0)
                            {
                            $get_sem=DBGet(DBQuery('SELECT * FROM institute_semesters WHERE INSTITUTE_ID=\''.UserInstitute().'\' AND SYEAR=\''.UserSyear().'\' '));
                            foreach($get_sem as $ind=>$data)
                            {
                                $y_id=DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_years WHERE SYEAR=\''.(UserSyear()+1).'\' AND INSTITUTE_ID=\''.UserInstitute().'\' '));
                                $next_mp_id=DBGet(DBQuery('SELECT '.db_seq_nextval('marking_period_seq').' as SEQ'));
                                DBQuery('INSERT INTO institute_semesters (MARKING_PERIOD_ID,YEAR_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) VALUES (\''.$next_mp_id[1]['SEQ'].'\',\''.$y_id[1]['MARKING_PERIOD_ID'].'\',\''.(UserSyear()+1).'\',\''.UserInstitute().'\',\''.$data['TITLE'].'\',\''.$data['SHORT_NAME'].'\',\''.$data['SORT_ORDER'].'\',\''.$_SESSION['sem_start'][$ind].'\',\''.$_SESSION['sem_end'][$ind].'\',\''.$_SESSION['sem_start'][$ind].'\',\''.$_SESSION['sem_end'][$ind].'\',\''.$data['DOES_GRADES'].'\',\''.$data['DOES_EXAM'].'\',\''.$data['DOES_COMMENTS'].'\',\''.$data['MARKING_PERIOD_ID'].'\')');
                            }
                                if($_SESSION['total_qrt']!='' && $_SESSION['total_qrt']!=0)
                                {
                                    $qrtr=0;
                                    foreach($get_sem as $ind=>$data)
                                    {
                                        $get_qrtr=DBGet(DBQuery('SELECT * FROM institute_quarters WHERE INSTITUTE_ID=\''.UserInstitute().'\' AND SYEAR=\''.UserSyear().'\' AND SEMESTER_ID=\''.$data['MARKING_PERIOD_ID'].'\' '));
                                        foreach($get_qrtr as $ind_q=>$data_q)
                                        {
                                            $qrtr++;
                                            $s_id=DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_semesters WHERE SYEAR=\''.(UserSyear()+1).'\' AND INSTITUTE_ID=\''.UserInstitute().'\' ORDER BY MARKING_PERIOD_ID '));
                                            $next_mp_id=DBGet(DBQuery('SELECT '.db_seq_nextval('marking_period_seq').' as SEQ'));
                                            DBQuery('INSERT INTO institute_quarters (MARKING_PERIOD_ID,SEMESTER_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) VALUES (\''.$next_mp_id[1]['SEQ'].'\',\''.$s_id[$ind]['MARKING_PERIOD_ID'].'\',\''.(UserSyear()+1).'\',\''.UserInstitute().'\',\''.$data_q['TITLE'].'\',\''.$data_q['SHORT_NAME'].'\',\''.$data_q['SORT_ORDER'].'\',\''.$_SESSION['qrtr_start'][$qrtr].'\',\''.$_SESSION['qrtr_end'][$qrtr].'\',\''.$_SESSION['qrtr_start'][$qrtr].'\',\''.$_SESSION['qrtr_end'][$qrtr].'\',\''.$data_q['DOES_GRADES'].'\',\''.$data_q['DOES_EXAM'].'\',\''.$data_q['DOES_COMMENTS'].'\',\''.$data_q['MARKING_PERIOD_ID'].'\')');
                                        }
                                    }
                                }
                                if($_SESSION['total_prg']!='' && $_SESSION['total_prg']!=0)
                                {
                                    $prg=0;
                                    $get_qrtr=DBGet(DBQuery('SELECT * FROM institute_quarters WHERE INSTITUTE_ID=\''.UserInstitute().'\' AND SYEAR=\''.UserSyear().'\' '));
                                    foreach($get_qrtr as $ind_q=>$data_q)
                                    {
                                      $get_prg=DBGet(DBQuery('SELECT * FROM institute_progress_periods WHERE INSTITUTE_ID=\''.UserInstitute().'\' AND SYEAR=\''.UserSyear().'\' AND QUARTER_ID=\''.$data_q['MARKING_PERIOD_ID'].'\' '));
                                      foreach($get_prg as $ind_p=>$data_p)
                                      {$prg++;
                                       $q_id=DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_quarters WHERE SYEAR=\''.(UserSyear()+1).'\' AND INSTITUTE_ID=\''.UserInstitute().'\' ORDER BY MARKING_PERIOD_ID '));
                                       $next_mp_id=DBGet(DBQuery('SELECT '.db_seq_nextval('marking_period_seq').' as SEQ'));
                                       DBQuery('INSERT INTO institute_progress_periods (MARKING_PERIOD_ID,QUARTER_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS) VALUES (\''.$next_mp_id[1]['SEQ'].'\',\''.$q_id[$ind_q]['MARKING_PERIOD_ID'].'\',\''.(UserSyear()+1).'\',\''.UserInstitute().'\',\''.$data_p['TITLE'].'\',\''.$data_p['SHORT_NAME'].'\',\''.$data_p['SORT_ORDER'].'\',\''.$_SESSION['prog_start'][$prg].'\',\''.$_SESSION['prog_end'][$prg].'\',\''.$_SESSION['prog_start'][$prg].'\',\''.$_SESSION['prog_end'][$prg].'\',\''.$data_p['DOES_GRADES'].'\',\''.$data_p['DOES_EXAM'].'\',\''.$data_p['DOES_COMMENTS'].'\')');   
                                      }
                                    }
                                }
                            }  
                        }
                        else
                        {
                        DBQuery('INSERT INTO institute_years (MARKING_PERIOD_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT '.db_seq_nextval('marking_period_seq').',SYEAR+1,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE + INTERVAL 1 YEAR,END_DATE + INTERVAL 1 YEAR,POST_START_DATE + INTERVAL 1 YEAR,POST_END_DATE +INTERVAL 1 YEAR,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM institute_years WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
                        DBQuery('INSERT INTO institute_semesters (MARKING_PERIOD_ID,YEAR_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT '.db_seq_nextval('marking_period_seq').',(SELECT MARKING_PERIOD_ID FROM institute_years y WHERE y.SYEAR=s.SYEAR+1 AND y.ROLLOVER_ID=s.YEAR_ID),SYEAR+1,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE + INTERVAL 1 YEAR,END_DATE + INTERVAL 1 YEAR,POST_START_DATE + INTERVAL 1 YEAR,POST_END_DATE + INTERVAL 1 YEAR,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM institute_semesters s WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
                        DBQuery('INSERT INTO institute_quarters (MARKING_PERIOD_ID,SEMESTER_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT '.db_seq_nextval('marking_period_seq').',(SELECT MARKING_PERIOD_ID FROM institute_semesters s WHERE s.SYEAR=q.SYEAR+1 AND s.ROLLOVER_ID=q.SEMESTER_ID),SYEAR+1,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE+INTERVAL 1 YEAR,END_DATE+INTERVAL 1 YEAR,POST_START_DATE+INTERVAL 1 YEAR,POST_END_DATE+INTERVAL 1 YEAR,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM institute_quarters q WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
                        DBQuery('INSERT INTO institute_progress_periods (MARKING_PERIOD_ID,QUARTER_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT '.db_seq_nextval('marking_period_seq').',(SELECT MARKING_PERIOD_ID FROM institute_quarters q WHERE q.SYEAR=p.SYEAR+1 AND q.ROLLOVER_ID=p.QUARTER_ID),SYEAR+1,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE+INTERVAL 1 YEAR,END_DATE+INTERVAL 1 YEAR,POST_START_DATE+INTERVAL 1 YEAR,POST_END_DATE+INTERVAL 1 YEAR,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM institute_progress_periods p WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
                        }
                        $exists_RET[$table] = DBGet(DBQuery("SELECT count(*) AS COUNT from $table WHERE SYEAR='$next_syear'".(!$no_institute_tables[$table]?" AND INSTITUTE_ID='".UserInstitute()."'":'')));             
                        $total_rolled_data=$exists_RET[$table][1]['COUNT'];
                        echo $tables['institute_years'].'|'.'(|'.$total_rolled_data.'|)|'.$tablesDisplay[$table];
                    break;

                    case 'course_subjects':
                    DBQuery('DELETE FROM course_subjects WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
                    DBQuery('INSERT INTO course_subjects (SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,ROLLOVER_ID) SELECT SYEAR+1,INSTITUTE_ID,TITLE,SHORT_NAME,SUBJECT_ID FROM course_subjects WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
                    $exists_RET[$table] = DBGet(DBQuery('SELECT count(*) AS COUNT from '.$table.' WHERE SYEAR=\''.$next_syear.'\''.(!$no_institute_tables[$table]?' AND INSTITUTE_ID=\''.UserInstitute().'\'':'')));
                    $total_rolled_data=$exists_RET[$table][1]['COUNT'];
                    echo $tables['course_subjects'].'|'.'(|'.$total_rolled_data.'|)|'.$tablesDisplay[$table];
                    break;

		case 'courses':
         $rollover_shadow_course= "";
                    DBQuery('DELETE FROM courses WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
                    DBQuery('INSERT INTO courses (SYEAR,SUBJECT_ID,INSTITUTE_ID,GRADE_LEVEL,TITLE,SHORT_NAME,ROLLOVER_ID) SELECT SYEAR+1,(SELECT SUBJECT_ID FROM course_subjects s WHERE s.SYEAR=c.SYEAR+1 AND s.ROLLOVER_ID=c.SUBJECT_ID),INSTITUTE_ID,GRADE_LEVEL,TITLE,SHORT_NAME,COURSE_ID FROM courses c WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
                    $exists_RET[$table] = DBGet(DBQuery('SELECT count(*) AS COUNT from '.$table.' WHERE SYEAR=\''.$next_syear.'\''.(!$no_institute_tables[$table]?' AND INSTITUTE_ID=\''.UserInstitute().'\'':'')));
                    $total_rolled_data=$exists_RET[$table][1]['COUNT'];
                    echo $tables['courses'].'|'.'(|'.$total_rolled_data.'|)|'.$tablesDisplay[$table];
                    break;
                   
                    case 'course_periods':
                     $rollover_shadow_course_periods= "";

			

                        $get_cp_tbd=DBGet(DBQuery('SELECT COURSE_PERIOD_ID FROM course_periods WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
                        foreach($get_cp_tbd as $arr_ind=>$arr_dt)
                        {
                            DBQuery('DELETE FROM course_periods WHERE COURSE_PERIOD_ID=\''.$arr_dt['COURSE_PERIOD_ID'].'\' ');
                            DBQuery('DELETE FROM course_period_var WHERE COURSE_PERIOD_ID=\''.$arr_dt['COURSE_PERIOD_ID'].'\' ');
                        }
                        unset($arr_ind);
                        unset($arr_dt);

                        $get_cp_dt=DBGet(DBQuery('SELECT SYEAR+1 as SYEAR,INSTITUTE_ID,(SELECT COURSE_ID FROM courses c WHERE c.SYEAR=p.SYEAR+1 AND c.ROLLOVER_ID=p.COURSE_ID),COURSE_WEIGHT,TITLE,SHORT_NAME,MP,'.db_case(array('MP',"'FY'",'(SELECT MARKING_PERIOD_ID FROM institute_years n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)',"'SEM'",'(SELECT MARKING_PERIOD_ID FROM institute_semesters n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)',"'QTR'",'(SELECT MARKING_PERIOD_ID FROM institute_quarters n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)')).',IF(MARKING_PERIOD_ID IS NULL,BEGIN_DATE + INTERVAL 1 YEAR,'.db_case(array('MP',"'FY'",'(SELECT START_DATE FROM institute_years n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)',"'SEM'",'(SELECT START_DATE FROM institute_semesters n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)',"'QTR'",'(SELECT START_DATE FROM institute_quarters n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)')).') as BEGIN_DATE,IF(MARKING_PERIOD_ID IS NULL,END_DATE + INTERVAL 1 YEAR,'.db_case(array('MP',"'FY'",'(SELECT END_DATE FROM institute_years n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)',"'SEM'",'(SELECT END_DATE FROM institute_semesters n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)',"'QTR'",'(SELECT END_DATE FROM institute_quarters n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)')).') as END_DATE,TEACHER_ID,SECONDARY_TEACHER_ID,TOTAL_SEATS,0 AS FILLED_SEATS,(SELECT ID FROM report_card_grade_scales n WHERE n.ROLLOVER_ID=p.GRADE_SCALE_ID),DOES_HONOR_ROLL,DOES_CLASS_RANK,DOES_BREAKOFF,GENDER_RESTRICTION,HOUSE_RESTRICTION,CREDITS,AVAILABILITY,HALF_DAY,PARENT_ID,(SELECT CALENDAR_ID FROM institute_calendars n WHERE n.ROLLOVER_ID=p.CALENDAR_ID),COURSE_PERIOD_ID,SCHEDULE_TYPE,last_updated,MODIFIED_BY FROM course_periods p WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\' LIMIT 1'));
			foreach($get_cp_dt as $arr_ind=>$arr_dt)
                        {
                        foreach($arr_dt as $a_i=>$a_d)
                        {
                            $datas[]="'".singleQuoteReplace("'","''",$a_d)."'";
                        }
                        $datas=implode(',',$datas);
                        
                        DBQuery('INSERT INTO course_periods (SYEAR,INSTITUTE_ID,COURSE_ID,COURSE_WEIGHT,TITLE,SHORT_NAME,MP,MARKING_PERIOD_ID,BEGIN_DATE,END_DATE,TEACHER_ID,SECONDARY_TEACHER_ID,TOTAL_SEATS,FILLED_SEATS,GRADE_SCALE_ID,DOES_HONOR_ROLL,DOES_CLASS_RANK,DOES_BREAKOFF,GENDER_RESTRICTION,HOUSE_RESTRICTION,CREDITS,AVAILABILITY,HALF_DAY,PARENT_ID,CALENDAR_ID,ROLLOVER_ID,SCHEDULE_TYPE,last_updated,MODIFIED_BY) VALUES ('.$datas.')');
                        $get_max_id=DBGet(DBQuery("SELECT MAX(COURSE_PERIOD_ID) as COURSE_PERIOD_ID FROM course_periods"));
                        
                        unset($datas);
                        unset($a_i);
                        unset($a_d);
                        
                        $get_cpv=DBGet(DBQuery("SELECT ".$get_max_id[1]['COURSE_PERIOD_ID']." as COURSE_PERIOD_ID,DAYS,COURSE_PERIOD_DATE + INTERVAL '1' YEAR AS COURSE_PERIOD_DATE,PERIOD_ID,START_TIME,END_TIME,ROOM_ID,DOES_ATTENDANCE FROM course_period_var WHERE COURSE_PERIOD_ID='".$arr_dt['COURSE_PERIOD_ID']."' "));
                        foreach($get_cpv as $cpv_ind=>$cpv_dt)
                        {

                            $spid=DBGet(DBQuery('SELECT PERIOD_ID FROM institute_periods  WHERE SYEAR=\''.$arr_dt['SYEAR'].'\' AND ROLLOVER_ID=\''.$cpv_dt['PERIOD_ID'].'\' '));
                            $cpv_dt['PERIOD_ID']=$spid[1]['PERIOD_ID'];
                            foreach($cpv_dt as $c_i=>$c_dt)
                            {
                                $col[]=$c_i;
                                $dt[]="'".singleQuoteReplace("'","''",$c_dt)."'";
                            }
                            $col=implode(',',$col);
                            $dt=implode(',',$dt);
                            DBQuery('INSERT INTO course_period_var ('.$col.') VALUES ('.$dt.')');
                            unset($col);
                            unset($dt);
                            unset($c_i);
                            unset($c_dt);
                        }
                        }
                       
                        
                        DBQuery('UPDATE course_periods SET PARENT_ID=COURSE_PERIOD_ID WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
                              

                        
                        $exists_RET[$table] = DBGet(DBQuery('SELECT count(*) AS COUNT from '.$table.' WHERE SYEAR=\''.$next_syear.'\''.(!$no_institute_tables[$table]?' AND INSTITUTE_ID=\''.UserInstitute().'\'':'')));
                        $total_rolled_data=$exists_RET[$table][1]['COUNT'];
                    echo $tables['course_periods'].'|'.'(|'.$total_rolled_data.'|)|'.$tablesDisplay[$table];
                        break;
		case 'student_enrollment':
                   
                                                    DBQuery('INSERT INTO student_enrollment (SYEAR,NEXT_INSTITUTE,INSTITUTE_ID,STUDENT_ID,GRADE_ID,START_DATE,END_DATE,ENROLLMENT_CODE,DROP_CODE,CALENDAR_ID,LAST_INSTITUTE) SELECT SYEAR+1,NEXT_INSTITUTE,INSTITUTE_ID,STUDENT_ID,(SELECT NEXT_GRADE_ID FROM institute_gradelevels g WHERE g.ID=e.GRADE_ID),\''.$next_start_date.'\' AS START_DATE,NULL AS END_DATE,(SELECT ID FROM student_enrollment_codes WHERE SYEAR=\''.$next_syear.'\' AND TYPE=\'Roll\') AS ENROLLMENT_CODE,NULL AS DROP_CODE,(SELECT CALENDAR_ID FROM institute_calendars WHERE ROLLOVER_ID=e.CALENDAR_ID),INSTITUTE_ID FROM student_enrollment e WHERE e.SYEAR=\''.UserSyear().'\' AND e.INSTITUTE_ID=\''.UserInstitute().'\' AND ((\''.DBDate('mysql').'\' BETWEEN e.START_DATE AND e.END_DATE OR e.END_DATE IS NULL) AND \''.DBDate('mysql').'\'>=e.START_DATE) AND e.NEXT_INSTITUTE=\''.UserInstitute().'\'');
			// ROLL STUDENTS WHO ARE TO BE RETAINED
                                                    DBQuery('INSERT INTO student_enrollment (SYEAR,NEXT_INSTITUTE,INSTITUTE_ID,STUDENT_ID,GRADE_ID,START_DATE,END_DATE,ENROLLMENT_CODE,DROP_CODE,CALENDAR_ID,LAST_INSTITUTE) SELECT SYEAR+1,NEXT_INSTITUTE,INSTITUTE_ID,STUDENT_ID,GRADE_ID,\''.$next_start_date.'\' AS START_DATE,NULL AS END_DATE,(SELECT ID FROM student_enrollment_codes WHERE SYEAR=\''.$next_syear.'\' AND TYPE=\'Roll\') AS ENROLLMENT_CODE,NULL AS DROP_CODE,(SELECT CALENDAR_ID FROM institute_calendars WHERE ROLLOVER_ID=e.CALENDAR_ID),INSTITUTE_ID FROM student_enrollment e WHERE e.SYEAR=\''.UserSyear().'\' AND e.INSTITUTE_ID=\''.UserInstitute().'\' AND ((\''.DBDate('mysql').'\' BETWEEN e.START_DATE AND e.END_DATE OR e.END_DATE IS NULL) AND \''.DBDate('mysql').'\'>=e.START_DATE) AND e.NEXT_INSTITUTE=\'0\'');
			// ROLL STUDENTS TO NEXT INSTITUTE
                                                    DBQuery('INSERT INTO student_enrollment (SYEAR,INSTITUTE_ID,GRADE_ID,STUDENT_ID,START_DATE,END_DATE,NEXT_INSTITUTE,ENROLLMENT_CODE,DROP_CODE,CALENDAR_ID,LAST_INSTITUTE) SELECT SYEAR+1,NEXT_INSTITUTE,(SELECT g.ID FROM institute_gradelevels g WHERE g.SORT_ORDER=1 AND g.INSTITUTE_ID=e.NEXT_INSTITUTE),STUDENT_ID,\''.$next_start_date.'\' AS START_DATE,NULL AS END_DATE,NEXT_INSTITUTE,(SELECT ID FROM student_enrollment_codes WHERE SYEAR=\''.$next_syear.'\' AND TYPE=\'Roll\') AS ENROLLMENT_CODE,NULL AS DROP_CODE,NULL,NEXT_INSTITUTE FROM student_enrollment e WHERE e.SYEAR=\''.UserSyear().'\' AND e.INSTITUTE_ID=\''.UserInstitute().'\' AND ((\''.DBDate('mysql').'\' BETWEEN e.START_DATE AND e.END_DATE OR e.END_DATE IS NULL) AND \''.DBDate('mysql').'\'>=e.START_DATE) AND e.NEXT_INSTITUTE NOT IN (\''.UserInstitute().'\',\'0\',\'-1\')');
                                                    
                                                    DBQuery('INSERT INTO medical_info (STUDENT_ID,SYEAR,INSTITUTE_ID,PHYSICIAN,PHYSICIAN_PHONE,PREFERRED_HOSPITAL) SELECT STUDENT_ID,\''.$next_syear.'\' as SYEAR,INSTITUTE_ID,PHYSICIAN,PHYSICIAN_PHONE,PREFERRED_HOSPITAL FROM medical_info WHERE SYEAR='.UserSyear().' AND INSTITUTE_ID='.UserInstitute());
                                                    DBQuery('UPDATE student_enrollment SET NEXT_INSTITUTE=\'-1\' WHERE GRADE_ID=(SELECT MAX(NEXT_GRADE_ID)FROM institute_gradelevels) AND SYEAR=\''.$next_syear.'\' AND LAST_INSTITUTE=\''.UserInstitute().'\'');
                                                    DBQuery("UPDATE student_enrollment SET DROP_CODE=(SELECT ID FROM student_enrollment_codes WHERE SYEAR='".UserSyear()."' AND TYPE='Roll'),END_DATE='".$next_start_date."' WHERE SYEAR=".  UserSyear()." AND INSTITUTE_ID=".  UserInstitute().' AND DROP_CODE IS NULL AND END_DATE IS NULL');
//                                                
                                                    
                        $exists_RET[$table] = DBGet(DBQuery('SELECT count(*) AS COUNT from '.$table.' WHERE SYEAR=\''.$next_syear.'\''.(!$no_institute_tables[$table]?' AND INSTITUTE_ID=\''.UserInstitute().'\'':'')));
                        $total_rolled_data=$exists_RET[$table][1]['COUNT'];
                        echo $tables['student_enrollment'].'|'.'(|'.$total_rolled_data.'|)|'.$tablesDisplay[$table];
                    break;
        
		case 'report_card_grade_scales':
                         
			DBQuery('DELETE FROM report_card_grade_scales WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			DBQuery('DELETE FROM report_card_grades WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			
                        DBQuery('INSERT INTO report_card_grade_scales (SYEAR,INSTITUTE_ID,TITLE,COMMENT,SORT_ORDER,ROLLOVER_ID,GP_SCALE) SELECT SYEAR+1,INSTITUTE_ID,TITLE,COMMENT,SORT_ORDER,ID,GP_SCALE FROM report_card_grade_scales WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			DBQuery('INSERT INTO report_card_grades (SYEAR,INSTITUTE_ID,TITLE,COMMENT,BREAK_OFF,GPA_VALUE,GRADE_SCALE_ID,UNWEIGHTED_GP,SORT_ORDER) SELECT SYEAR+1,INSTITUTE_ID,TITLE,COMMENT,BREAK_OFF,GPA_VALUE,(SELECT ID FROM report_card_grade_scales WHERE ROLLOVER_ID=GRADE_SCALE_ID AND INSTITUTE_ID=report_card_grades.INSTITUTE_ID),UNWEIGHTED_GP,SORT_ORDER FROM report_card_grades WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
                        $exists_RET[$table] = DBGet(DBQuery('SELECT count(*) AS COUNT from '.$table.' WHERE SYEAR=\''.$next_syear.'\''.(!$no_institute_tables[$table]?' AND INSTITUTE_ID=\''.UserInstitute().'\'':'')));
                        $total_rolled_data=$exists_RET[$table][1]['COUNT'];
                        echo $tables['report_card_grade_scales'].'|'.'(|'.$total_rolled_data.'|)|'.$tablesDisplay[$table];
                    break;
       
		case 'report_card_comments':
                   
			DBQuery('DELETE FROM report_card_comments WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			DBQuery('INSERT INTO report_card_comments (SYEAR,INSTITUTE_ID,TITLE,SORT_ORDER,COURSE_ID) SELECT SYEAR+1,INSTITUTE_ID,TITLE,SORT_ORDER,'.db_case(array('COURSE_ID',"''",'NULL',db_case(array('COURSE_ID','0','0','(SELECT COURSE_ID FROM courses WHERE ROLLOVER_ID=rc.COURSE_ID)')))).' FROM report_card_comments rc WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
                        $exists_RET[$table] = DBGet(DBQuery('SELECT count(*) AS COUNT from '.$table.' WHERE SYEAR=\''.$next_syear.'\''.(!$no_institute_tables[$table]?' AND INSTITUTE_ID=\''.UserInstitute().'\'':'')));
                        $total_rolled_data=$exists_RET[$table][1]['COUNT'];
                        echo $tables['report_card_comments'].'|'.'(|'.$total_rolled_data.'|)|'.$tablesDisplay[$table];
                     break;
                  case 'honor_roll':
		//case 'eligibility_activities':

			DBQuery('DELETE FROM '.$table.' WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			$table_properties = db_properties($table);
			$columns = '';
			foreach($table_properties as $column=>$values)
			{
				if($column!='ID' && $column!='SYEAR')
					$columns .= ','.$column;
			}
                        DBQuery('INSERT INTO '.$table.' (SYEAR'.$columns.') SELECT SYEAR+1'.$columns.' FROM '.$table.' WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
                        
                        $exists_RET[$table] = DBGet(DBQuery('SELECT count(*) AS COUNT from '.$table.' WHERE SYEAR=\''.$next_syear.'\''.(!$no_institute_tables[$table]?' AND INSTITUTE_ID=\''.UserInstitute().'\'':'')));
                        $total_rolled_data=$exists_RET[$table][1]['COUNT'];
                        echo $tables['honor_roll'].'|'.'(|'.$total_rolled_data.'|)|'.$tablesDisplay[$table];
                      break;

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
                       
                        
                        
			DBQuery('DELETE FROM attendance_code_categories WHERE SYEAR=\''.$next_syear.'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
			$table_properties = db_properties('attendance_code_categories');
			$columns = '';
			foreach($table_properties as $column=>$values)
			{
				if($column!='ID' && $column!='SYEAR')
					$columns .= ','.$column;
			}
                        DBQuery('INSERT INTO attendance_code_categories (SYEAR'.$columns.') SELECT SYEAR+1'.$columns.' FROM attendance_code_categories WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'');
                         
                         $exists_RET[$table] = DBGet(DBQuery('SELECT count(*) AS COUNT from '.$table.' WHERE SYEAR=\''.$next_syear.'\''.(!$no_institute_tables[$table]?' AND INSTITUTE_ID=\''.UserInstitute().'\'':'')));
                        $total_rolled_data=$exists_RET[$table][1]['COUNT'];
                        echo $tables['attendance_codes'].'|'.'(|'.$total_rolled_data.'|)|'.$tablesDisplay[$table];
                      
                        break;

		// DOESN'T HAVE A INSTITUTE_ID
		case 'student_enrollment_codes':
                        
			
                        $student_enroll_rolled=DBGet(DBQuery('SELECT ID FROM '.$table.' WHERE SYEAR=\''.$next_syear.'\''));
                        $total_student_enroll_rolled=count($student_enroll_rolled);
			$table_properties = db_properties($table);
			$columns = '';
			foreach($table_properties as $column=>$values)
			{
				if($column!='ID' && $column!='SYEAR')
					$columns .= ','.$column;
			}
                        if($total_student_enroll_rolled==0){
			DBQuery('INSERT INTO '.$table.' (SYEAR'.$columns.') SELECT SYEAR+1'.$columns.' FROM '.$table.' WHERE SYEAR=\''.UserSyear().'\'');
                                $roll_RET=DBGet(DBQuery('SELECT ID FROM '.$table.' WHERE TYPE=\'Roll\' AND SYEAR=\''.$next_syear.'\''));
                                if(!$roll_RET){
                                    DBQuery('INSERT INTO '.$table.' (SYEAR'.$columns.') VALUES(\''.$next_syear.'\',\'Rolled Over\',\'ROLL\',\'Roll\')');
                                }
                        }
                        $exists_RET[$table] = DBGet(DBQuery('SELECT count(*) AS COUNT from '.$table.' WHERE SYEAR=\''.$next_syear.'\''.(!$no_institute_tables[$table]?' AND INSTITUTE_ID=\''.UserInstitute().'\'':'')));
                        $total_rolled_data=$exists_RET[$table][1]['COUNT'];
                        echo $tables['student_enrollment_codes'].'|'.'(|'.$total_rolled_data.'|)|'.$tablesDisplay[$table];
                      break;

                    case 'NONE' :
                        DBQuery('DELETE FROM program_config WHERE (program=\'eligibility\' OR program=\'Currency\') AND syear=\''.$next_syear.'\' AND syear IS NOT NULL AND institute_id IS NOT NULL AND institute_id=\''.UserInstitute().'\'');
                        DBQuery('INSERT INTO program_config(syear,institute_id,program,title,value) SELECT syear+1,\''.UserInstitute().'\',program,title,value FROM program_config WHERE (program=\'eligibility\' OR program=\'Currency\') AND syear=\''.UserSyear().'\' AND syear IS NOT NULL AND institute_id IS NOT NULL AND institute_id=\''.UserInstitute().'\'');
                        echo '<div style="padding-top:90px; text-align:center;"><span style="font-size:14px; font-weight:bold;">The institute year has been rolled.</span><br/><br/><input type=button onclick=document.location.href="index.php?modfunc=logout" value="Please login again" class=btn_large ></div>';
						
                        unset($_SESSION['_REQUEST_vars']['tables']);
                        unset($_SESSION['_REQUEST_vars']['delete_ok']);
                        
}

function roll_calendar($calendar_id,$rollover_id)
{
    $next_y=UserSyear()+1;
    $cal_RET=DBGet(DBQuery('SELECT DATE_FORMAT(MIN(INSTITUTE_DATE),\'%c\') AS START_MONTH,DATE_FORMAT(MIN(INSTITUTE_DATE),\'%e\') AS START_DAY,DATE_FORMAT(MIN(INSTITUTE_DATE),\'%Y\') AS START_YEAR,
                                    DATE_FORMAT(MAX(INSTITUTE_DATE),\'%c\') AS END_MONTH,DATE_FORMAT(MAX(INSTITUTE_DATE),\'%e\') AS END_DAY,DATE_FORMAT(MAX(INSTITUTE_DATE),\'%Y\') AS END_YEAR FROM attendance_calendar WHERE CALENDAR_ID='.$rollover_id.''));
    $min_month=$cal_RET[1]['START_MONTH'];
    $min_day=$cal_RET[1]['START_DAY'];
    $min_year=$cal_RET[1]['START_YEAR']+1;
    $max_month=$cal_RET[1]['END_MONTH'];
    $max_day=$cal_RET[1]['END_DAY'];
    $max_year=$cal_RET[1]['END_YEAR']+1;
    $begin=mktime(0,0,0,$min_month,$min_day,$min_year)+ 43200;
    $end=mktime(0,0,0,$max_month,$max_day,$max_year)+ 43200;
    $day_RET=DBGet(DBQuery('SELECT INSTITUTE_DATE FROM attendance_calendar WHERE CALENDAR_ID=\''.$rollover_id.'\' ORDER BY INSTITUTE_DATE LIMIT 0, 7'));
    foreach ($day_RET as $day)
    {
        $weekdays[date('w',strtotime($day['INSTITUTE_DATE']))]=date('w',strtotime($day['INSTITUTE_DATE']));
    }
    $weekday = date('w',$begin);
    for($i=$begin;$i<=$end;$i+=86400)
    {
            if($weekdays[$weekday]!=''){
                if(is_leap_year($next_y)){
                   $previous_year_day=$i-31622400;
                }else{
                     $previous_year_day=$i-31536000;
                }
                $previous_RET=DBGet(DBQuery('SELECT COUNT(INSTITUTE_DATE) AS INSTITUTE FROM attendance_calendar WHERE INSTITUTE_DATE=\''.date('Y-m-d',$previous_year_day).'\' AND CALENDAR_ID=\''.$rollover_id.'\''));
                if($previous_RET[1]['INSTITUTE']==0){
                    $prev_weekday=date('w',$previous_year_day);
                    if($weekdays[$prev_weekday]==''){
                        DBQuery('INSERT INTO attendance_calendar (SYEAR,INSTITUTE_ID,INSTITUTE_DATE,MINUTES,CALENDAR_ID) values(\''.$next_y.'\',\''.UserInstitute().'\',\''.date('Y-m-d',$i).'\',\'999\',\''.$calendar_id.'\')');
                    }
                }else{
                    DBQuery('INSERT INTO attendance_calendar (SYEAR,INSTITUTE_ID,INSTITUTE_DATE,MINUTES,CALENDAR_ID) values(\''.$next_y.'\',\''.UserInstitute().'\',\''.date('Y-m-d',$i).'\',\'999\',\''.$calendar_id.'\')');
                }
            }
            $weekday++;
            if($weekday==7)
                    $weekday = 0;
    }
}
function roll_given_date($table,$cal_id,$roll_id)
{
$next_y=UserSyear()+1;    
 $st_date=date('Y-m-d',strtotime($_SESSION['roll_s_start_date']));
 $end_date=date('Y-m-d',strtotime($_SESSION['roll_s_end_date']));
$c_dt=$st_date;
$c_dt_arr[]=$c_dt;
while(strtotime($c_dt)<strtotime($end_date))
{
    
$c_dt=date('Y-m-d', strtotime('+1 day', strtotime($c_dt)));
$c_dt_arr[]=$c_dt;

}


switch($table)
{
    case 'calendar_events':
    foreach($c_dt_arr as $dt)
    {
    DBQuery('INSERT INTO calendar_events (SYEAR,INSTITUTE_ID,CALENDAR_ID,INSTITUTE_DATE,TITLE,DESCRIPTION) SELECT SYEAR+1,INSTITUTE_ID,'.$cal_id.' as CALENDAR_ID,INSTITUTE_DATE+INTERVAL \'1\' YEAR,TITLE,DESCRIPTION FROM calendar_events WHERE SYEAR=\''.UserSyear().'\' AND  INSTITUTE_ID=\''.UserInstitute().'\' AND INSTITUTE_DATE+INTERVAL \'1\' YEAR=\''.$dt.'\' AND CALENDAR_ID=\''.$roll_id.'\' ');
    }
    DBQuery('INSERT INTO calendar_events_visibility (CALENDAR_ID,PROFILE_ID,PROFILE) SELECT \''.$cal_id.'\' as CALENDAR_ID,PROFILE_ID,PROFILE FROM calendar_events_visibility WHERE CALENDAR_ID=\''.$roll_id.'\' ');
   
    break;
    case 'attendance_calendar':
    $day_RET=DBGet(DBQuery('SELECT INSTITUTE_DATE FROM attendance_calendar WHERE CALENDAR_ID=\''.$roll_id.'\' ORDER BY INSTITUTE_DATE LIMIT 0, 7'));
   
        
        foreach ($day_RET as $day)
    {
        $weekdays[date('D',strtotime($day['INSTITUTE_DATE']))]=date('D',strtotime($day['INSTITUTE_DATE']));
    }
    
    foreach($c_dt_arr as $dt)
    {
        foreach($weekdays as $i=>$d)
        {
            if($d==date('D',strtotime($dt)))
            {
                DBQuery('INSERT INTO attendance_calendar (SYEAR,INSTITUTE_ID,INSTITUTE_DATE,MINUTES,CALENDAR_ID) VALUES 
                         (\''.$next_y.'\',\''.UserInstitute().'\',\''.$dt.'\',\'999\',\''.$cal_id.'\') ');
                
            }
        }
    }
    break;

}

}
function is_leap_year($year)
{
	return ((($year % 4) == 0) && ((($year % 100) != 0) || (($year %400) == 0)));
}
?>
