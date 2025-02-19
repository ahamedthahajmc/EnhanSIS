<?php


include('../../RedirectModulesInc.php');
include('lang/language.php');


echo '<div class="row">';

echo '<div class="col-md-6 col-md-offset-3">';

$tables = array('institute_periods' => 'Institute Periods', 'institute_years' => 'Marking Periods', 'report_card_grades' => 'Report Card Grade Codes', 'report_card_comments' => 'Report Card Comment Codes', 'eligibility_activities' => 'Eligibility Activity Codes', 'attendance_codes' => 'Attendance Codes', 'institute_gradelevels' => 'Grade Levels', 'rooms' => 'Rooms', 'institute_gradelevel_sections' => 'Sections', 'course_subjects' => 'Subjects', 'institute_calendars' => 'Calendar','courses' => 'Course',);
#$tables = array('institute_periods' =>_institutePeriods, 'institute_years' =>_markingPeriods, 'report_card_grades' =>_reportCardGradeCodes, 'report_card_comments' =>_institutePeriods, 'eligibility_activities' =>_institutePeriods, 'attendance_codes' =>_institutePeriods, 'institute_gradelevels' =>_reportCardGradeCodes, 'rooms' =>_institutePeriods, 'institute_gradelevel_sections' =>_institutePeriods, 'course_subjects' =>_institutePeriods, 'institute_calendars' =>_institutePeriods,'courses' =>_institutePeriods,);
$table_list = '<br/><div class="form-group"><label class="control-label text-uppercase" for="instituteTitle"><b>'._newInstitute.'\'s '._title.'</b></label><INPUT type=text name=title placeholder="Title" value="'._newInstitute.'" id="instituteTitle" onKeyUp="checkDuplicateName(1,this,0);" onBlur="checkDuplicateName(1,this,0);" class="form-control"></div>';

$table_list .= '<div class="row">';
foreach ($tables as $table => $name) {
    $table_list .= '<div class="col-md-6">';
    if($table=='courses')
    $table_list .= '<div class="checkbox checkbox-switch switch-success"><label><INPUT type="checkbox" id="course" value="Y" name="tables[' . $table . ']" checked="checked" onClick="checkChecked(\'course\',\'subject\');"><span></span> ' . $name . '</label></div>';
    elseif($table=='course_subjects')
    $table_list .= '<div class="checkbox checkbox-switch switch-success"><label><INPUT type="checkbox" id="subject"  value="Y" name="tables[' . $table . ']" checked="checked"  onClick="turnCheckOff(\'course\',\'subject\');"><span></span> ' . $name . '</label></div>';
    else
    $table_list .= '<div class="checkbox checkbox-switch switch-success"><label><INPUT type="checkbox" value="Y" name="tables[' . $table . ']" checked="checked"><span></span> ' . $name . '</label></div>';

    $table_list .= '</div>'; //.col-md-6
}
$table_list .= '</div>';

$table_list .= "<input type=hidden id=checkDuplicateNameTable1 value='institutes'/>";
$table_list .= "<input type=hidden id=checkDuplicateNameField1 value='title'/>";
$table_list .= "<input type=hidden id=checkDuplicateNameMsg1 value='institute name'/>";
if (clean_param($_REQUEST['copy'], PARAM_ALPHAMOD) == 'done') {
    echo '<strong>'._instituteInformationHasBeenCopiedSuccessfully.'</strong>';
} else {
    DrawBC(""._instituteSetup." > " . ProgramTitle());
     if (Prompt_Copy_Institute(''._confirmCopyInstitute.'', ''._areYouSureYouWantToCopyTheDataFor. ' <span class="text-primary">' . GetInstitute(UserInstitute()) . '</span> '._toANewInstitute.'', $table_list)) {
        if (count($_REQUEST['tables'])) {

            // $id = DBGet(DBQuery('SHOW TABLE STATUS LIKE \'institutes\''));
            // $id[1]['ID'] = $id[1]['AUTO_INCREMENT'];
            // $id = $id[1]['ID'];


            $copy_syear_RET = DBGet(DBQuery('SELECT MAX(syear) AS SYEAR FROM institute_years WHERE institute_id=' . UserInstitute()));
            $new_sch_syear = $copy_syear_RET[1]['SYEAR'];
            DBQuery('INSERT INTO institutes (SYEAR,TITLE) values(\'' . $new_sch_syear . '\',\'' . str_replace("'", "''", str_replace("\'", "''", paramlib_validation($col = TITLE, $_REQUEST['title']))) . '\')');
            $id = mysqli_insert_id($connection);

            DBQuery('INSERT INTO institute_years (MARKING_PERIOD_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT fn_marking_period_seq(),SYEAR,\'' . $id . '\' AS INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM institute_years WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\' ORDER BY MARKING_PERIOD_ID');
            DBQuery('INSERT INTO program_config(INSTITUTE_ID,SYEAR,PROGRAM,TITLE,VALUE) VALUES(\'' . $id . '\',\'' . $new_sch_syear . '\',\'MissingAttendance\',\'LAST_UPDATE\',\'' . date('Y-m-d') . '\')');
            DBQuery('INSERT INTO program_config(INSTITUTE_ID,SYEAR,PROGRAM,TITLE,VALUE) VALUES(\'' . $id . '\',\'' . $new_sch_syear . '\',\'UPDATENOTIFY\',\'display_institute\',"Y")');

            $current_start_date = DBGet(DBQuery('SELECT START_DATE FROM staff_institute_relationship WHERE STAFF_ID=\'' . User('STAFF_ID') . '\' AND INSTITUTE_ID='.UserInstitute().' AND syear='.UserSyear().''));
            $temp_start_date='';
            if($current_start_date[1]['START_DATE']!='')
            $temp_start_date=$current_start_date[1]['START_DATE'];
            else
            $temp_start_date=date('Y-m-d');
            DBQuery('INSERT INTO staff_institute_relationship(staff_id,institute_id,syear,start_date)VALUES(\'' . User('STAFF_ID') . '\',\'' . $id . '\',\'' . UserSyear() . '\',"'.$temp_start_date.'")');
            $other_admin_details=DBGet(DBQuery('SELECT * FROM login_authentication WHERE PROFILE_ID=0 AND USER_ID!=' . User('STAFF_ID') . ''));
            if(!empty($other_admin_details))
            {
            foreach($other_admin_details as $institute_data)
            {
            DBQuery('INSERT INTO  staff_institute_relationship(staff_id,institute_id,syear,start_date) VALUES (' . $institute_data['USER_ID'] . ',' . $id . ',' . UserSyear(). ',"'.$temp_start_date.'")');    
            }
            }
            if (User('PROFILE_ID') != 0) {
                $super_id = DBGet(DBQuery('SELECT STAFF_ID FROM staff WHERE PROFILE_ID=0 AND PROFILE=\'admin\''));
                $current_start_date = DBGet(DBQuery('SELECT START_DATE FROM staff_institute_relationship WHERE STAFF_ID=\'' . $super_id[1]['STAFF_ID'] . '\' AND INSTITUTE_ID='.$id.' AND syear='.UserSyear().''));
                if($current_start_date[1]['START_DATE']!='')
                $temp_start_date=$current_start_date[1]['START_DATE'];
                else
                $temp_start_date=date('Y-m-d');
                 $staff_exists=DBGet(DBQuery('SELECT * FROM staff_institute_relationship WHERE STAFF_ID='.$super_id[1]['STAFF_ID'] . ' AND INSTITUTE_ID='. $id . ' AND SYEAR='.UserSyear()));
                    if(count($staff_exists)==0)
                        DBQuery('INSERT INTO  staff_institute_relationship(staff_id,institute_id,syear,start_date) VALUES (' . $super_id[1]['STAFF_ID'] . ',' . $id . ',' . UserSyear() . ',"'.$temp_start_date.'")');
            }
            foreach ($_REQUEST['tables'] as $table => $value)
                _rollover($table);
            DBQuery("UPDATE institute_years SET ROLLOVER_ID = NULL WHERE INSTITUTE_ID='$id'");

            $chk_stu_enrollment_codes_exist = DBGet(DBQuery('SELECT COUNT(*) AS STU_ENR_COUNT FROM `student_enrollment_codes` WHERE `syear` = \''.$new_sch_syear.'\''));
            if($chk_stu_enrollment_codes_exist[1]['STU_ENR_COUNT'] == 0)
            {
                DBQuery('INSERT INTO `student_enrollment_codes` (`syear`, `title`, `short_name`, `type`) VALUES (\''.$new_sch_syear.'\', \'Transferred out\', \'TRAN\', \'TrnD\')');
                DBQuery('INSERT INTO `student_enrollment_codes` (`syear`, `title`, `short_name`, `type`) VALUES (\''.$new_sch_syear.'\', \'Transferred in\', \'TRAN\', \'TrnE\')');
                DBQuery('INSERT INTO `student_enrollment_codes` (`syear`, `title`, `short_name`, `type`) VALUES (\''.$new_sch_syear.'\', \'Rolled over\', \'ROLL\', \'Roll\')');
                DBQuery('INSERT INTO `student_enrollment_codes` (`syear`, `title`, `short_name`, `type`) VALUES (\''.$new_sch_syear.'\', \'Dropped Out\', \'DROP\', \'Drop\')');
                DBQuery('INSERT INTO `student_enrollment_codes` (`syear`, `title`, `short_name`, `type`) VALUES (\''.$new_sch_syear.'\', \'New\', \'NEW\', \'Add\')');
            }
        }
        echo '<FORM action=Modules.php?modname=' . strip_tags(trim($_REQUEST['modname'])) . ' method=POST>';
        //echo '<script language=JavaScript>parent.side.location="' . $_SESSION['Side_PHP_SELF'] . '?modcat="+parent.side.document.forms[0].modcat.value;</script>';

        echo '<div class="panel panel-default">';
        echo '<div class="panel-body text-center">';
        echo '<div class="new-institute-created  p-30">';
        echo '<div class="icon-institute">';
        echo '<span></span>';
        echo '</div>';
        echo '<h5 class="p-20">'._theDataHaveBeenCopiedToANewInstituteCalled.' <b class="text-success">'.paramlib_validation($col = TITLE, $_REQUEST['title']).'</b>. '._toFinishTheOperationClickTheButtonBelow.'</h5>';
        echo '<div class="text-center"><INPUT type="submit" value="'._finishSetup.'" class="btn btn-primary btn-lg"></div>';
        echo '</div>'; //.new-institute-created
        echo '</div>'; //.panel-body
        echo '</div>'; //.panel
        
        //DrawHeaderHome('<i class="icon-checkbox-checked"></i> &nbsp;The data have been copied to a new institute called "' . paramlib_validation($col = TITLE, $_REQUEST['title']) . '".To finish the operation, click OK button.', '<INPUT  type=submit value="._ok." class="btn btn-primary">');
        echo '<input type="hidden" name="copy" value="done"/>';
        echo '</FORM>';
        unset($_SESSION['_REQUEST_vars']['tables']);
        unset($_SESSION['_REQUEST_vars']['delete_ok']);
    }
}

function _rollover($table) {
    global $id;

    switch ($table) {
        case 'institute_periods':
            DBQuery('INSERT INTO institute_periods (SYEAR,INSTITUTE_ID,SORT_ORDER,TITLE,SHORT_NAME,LENGTH,START_TIME,END_TIME,IGNORE_SCHEDULING,ATTENDANCE) SELECT SYEAR,\'' . $id . '\' AS INSTITUTE_ID,SORT_ORDER,TITLE,SHORT_NAME,LENGTH,START_TIME,END_TIME,IGNORE_SCHEDULING,ATTENDANCE FROM institute_periods WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\'');
            break;

        case 'institute_gradelevels':
            $table_properties = db_properties($table);
            $columns = '';
            foreach ($table_properties as $column => $values) {
                if ($column != 'ID' && $column != 'INSTITUTE_ID' && $column != 'NEXT_GRADE_ID')
                    $columns .= ',' . $column;
            }
            DBQuery('INSERT INTO ' . $table . ' (INSTITUTE_ID' . $columns . ') SELECT \'' . $id . '\' AS INSTITUTE_ID' . $columns . ' FROM ' . $table . ' WHERE INSTITUTE_ID=\'' . UserInstitute() . '\'');
            DBQuery('UPDATE ' . $table . ' t1,' . $table . ' t2 SET t1.NEXT_GRADE_ID= t1.ID+1 WHERE t1.INSTITUTE_ID=\'' . $id . '\' AND t1.ID+1=t2.ID');
            break;

        case 'institute_years':
            DBQuery('INSERT INTO institute_semesters (MARKING_PERIOD_ID,YEAR_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT fn_marking_period_seq(),(SELECT MARKING_PERIOD_ID FROM institute_years y WHERE y.SYEAR=s.SYEAR AND y.ROLLOVER_ID=s.YEAR_ID AND y.INSTITUTE_ID=\'' . $id . '\') AS YEAR_ID,SYEAR,\'' . $id . '\' AS INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM institute_semesters s WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\' ORDER BY MARKING_PERIOD_ID');
            DBQuery('INSERT INTO institute_quarters (MARKING_PERIOD_ID,SEMESTER_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT fn_marking_period_seq(),(SELECT MARKING_PERIOD_ID FROM institute_semesters s WHERE s.SYEAR=q.SYEAR AND s.ROLLOVER_ID=q.SEMESTER_ID AND s.INSTITUTE_ID=\'' . $id . '\') AS SEMESTER_ID,SYEAR,\'' . $id . '\' AS INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM institute_quarters q WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\' ORDER BY MARKING_PERIOD_ID');
            DBQuery('INSERT INTO institute_progress_periods (MARKING_PERIOD_ID,QUARTER_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT fn_marking_period_seq(),(SELECT MARKING_PERIOD_ID FROM institute_quarters q WHERE q.SYEAR=p.SYEAR AND q.ROLLOVER_ID=p.QUARTER_ID AND q.INSTITUTE_ID=\'' . $id . '\'),SYEAR,\'' . $id . '\' AS INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM institute_progress_periods p WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\' ORDER BY MARKING_PERIOD_ID');

            DBQuery('UPDATE institute_semesters SET ROLLOVER_ID = NULL WHERE INSTITUTE_ID=\'' . $id . '\'');
            DBQuery('UPDATE institute_quarters SET ROLLOVER_ID = NULL WHERE INSTITUTE_ID=\'' . $id . '\'');
            DBQuery('UPDATE institute_progress_periods SET ROLLOVER_ID = NULL WHERE INSTITUTE_ID=\'' . $id . '\'');

            break;

        case 'report_card_grades':
            DBQuery('INSERT INTO report_card_grade_scales (SYEAR,INSTITUTE_ID,TITLE,COMMENT,SORT_ORDER,ROLLOVER_ID,GP_SCALE) SELECT SYEAR,\'' . $id . '\',TITLE,COMMENT,SORT_ORDER,ID,GP_SCALE FROM report_card_grade_scales WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\'');

            $qr = DBGet(DBQuery('select * from report_card_grades where institute_id=' . UserInstitute() . ' and SYEAR= ' . UserSyear() . ''));
            $c = 1;
            foreach ($qr as $qk => $qv) {

                $qr1 = DBGet(DBQuery('select id from report_card_grade_scales where title=(select title from report_card_grade_scales where id=' . $qv['GRADE_SCALE_ID'] . ') and institute_id=' . $id . ''));
                $gr_scale_id = $qr1[1]['ID'];

                DBQuery('INSERT INTO report_card_grades (SYEAR,INSTITUTE_ID,TITLE,COMMENT,BREAK_OFF,GPA_VALUE,UNWEIGHTED_GP,GRADE_SCALE_ID,SORT_ORDER) SELECT SYEAR,\'' . $id . '\',TITLE,COMMENT,BREAK_OFF,GPA_VALUE,UNWEIGHTED_GP,\'' . $gr_scale_id . '\',SORT_ORDER FROM report_card_grades WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\' AND ID=' . $qv['ID']);
            }

            DBQuery('UPDATE report_card_grade_scales SET ROLLOVER_ID=NULL WHERE INSTITUTE_ID=\'' . $id . '\'');



            break;

        case 'report_card_comments':
            $qr = DBGet(DBQuery('SELECT COURSE_ID,ID FROM report_card_comments WHERE   SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\''));

            foreach ($qr as $qk => $qv) {

                $qr1 = DBGet(DBQuery('select COURSE_ID,ID FROM report_card_comments WHERE   SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\''));
                $course_id = $qr1[$qk]['COURSE_ID'];
                $id1 = $qr1[$qk]['ID'];
                DBQuery('INSERT INTO report_card_comments (SYEAR,INSTITUTE_ID,TITLE,SORT_ORDER,COURSE_ID) SELECT SYEAR,\'' . $id . '\',TITLE,SORT_ORDER,\'' . $course_id . '\' FROM report_card_comments WHERE ID =\'' . $id1 . '\' AND SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\'');
            }
            break;

        case 'eligibility_activities':
        case 'attendance_codes':
            $table_properties = db_properties($table);
            $columns = '';
            foreach ($table_properties as $column => $values) {
                if ($column != 'ID' && $column != 'SYEAR' && $column != 'INSTITUTE_ID')
                    $columns .= ',' . $column;
            }
            DBQuery('INSERT INTO ' . $table . ' (SYEAR,INSTITUTE_ID' . $columns . ') SELECT SYEAR,\'' . $id . '\' AS INSTITUTE_ID' . $columns . ' FROM ' . $table . ' WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\'');
            break;
            
        case 'rooms':
           $table_properties = db_properties($table);
           $columns = '';
           foreach ($table_properties as $column => $values) {
               if ($column != 'ROOM_ID' && $column != 'INSTITUTE_ID')
                   $columns .= ',' . $column;
           }
           DBQuery('INSERT INTO ' . $table . ' (INSTITUTE_ID' . $columns . ') SELECT \'' . $id . '\' AS INSTITUTE_ID' . $columns . ' FROM ' . $table . ' WHERE INSTITUTE_ID=\'' . UserInstitute() . '\'');
           break;   
            
        case 'institute_gradelevel_sections':
            $table_properties = db_properties($table);
            $columns = '';
            foreach ($table_properties as $column => $values) {
                if ($column != 'ID' && $column != 'INSTITUTE_ID')
                    $columns .= ',' . $column;
            }
            DBQuery('INSERT INTO ' . $table . ' (INSTITUTE_ID' . $columns . ') SELECT \'' . $id . '\' AS INSTITUTE_ID' . $columns . ' FROM ' . $table . ' WHERE  INSTITUTE_ID=\'' . UserInstitute() . '\'');
            break;    
        
        case 'course_subjects':
            $table_properties = db_properties($table);
            $columns = '';
            foreach ($table_properties as $column => $values) {
                if ($column != 'SUBJECT_ID' && $column != 'SYEAR' && $column != 'INSTITUTE_ID')
                    $columns .= ',' . $column;
            }
            DBQuery('INSERT INTO ' . $table . ' (SYEAR,INSTITUTE_ID' . $columns . ') SELECT SYEAR,\'' . $id . '\' AS INSTITUTE_ID' . $columns . ' FROM ' . $table . ' WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\'');
            break;  
            
            
         
        case 'institute_calendars':
           $get_all=DBGet(DBQuery('SELECT * FROM institute_calendars WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\'')); 
           foreach($get_all as $ga)
           {
           $query_values=$id.','."'".$ga['TITLE']."'".','.$ga['SYEAR'];
           $query_build='INSERT INTO institute_calendars (INSTITUTE_ID,TITLE,SYEAR';
           if($ga['DEFAULT_CALENDAR']!='')
           {
               $query_build.=',DEFAULT_CALENDAR';
               $query_values.=','."'".$ga['DEFAULT_CALENDAR']."'";
           }
           if($ga['DAYS']!='')
           {
               $query_build.=',DAYS';
               $query_values.=','."'".$ga['DAYS']."'";
           }
           $query_build.=') VALUES ('.$query_values.')';
           DBQuery($query_build);
           unset($query_values);
           unset($query_build);
           $calendar_id=DBGet(DBQuery('SELECT MAX(CALENDAR_ID) as CALENDAR_ID FROM institute_calendars WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' .$id.'\''));
           
           $table_properties = db_properties('attendance_calendar');
            $columns = '';
            foreach ($table_properties as $column => $values) {
                if ($column != 'INSTITUTE_ID' && $column != 'CALENDAR_ID')
                    $columns .= ',' . $column;
            }
            DBQuery('INSERT INTO attendance_calendar (CALENDAR_ID,INSTITUTE_ID' . $columns . ') SELECT \''.$calendar_id[1]['CALENDAR_ID'].'\' as CALENDAR_ID,\'' . $id . '\' AS INSTITUTE_ID' . $columns . ' FROM attendance_calendar WHERE CALENDAR_ID=\''.$ga['CALENDAR_ID'].'\' ');
           }
           break;
           
           
        case 'courses':
            $get_ts_grade=DBGet(DBQuery('SELECT * FROM institute_gradelevels WHERE INSTITUTE_ID=\''.$id.'\' '));
            $get_cs_grade=DBGet(DBQuery('SELECT * FROM institute_gradelevels WHERE  INSTITUTE_ID=\''.UserInstitute().'\' '));
            $get_ts_subjects=DBGet(DBQuery('SELECT * FROM course_subjects WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' .$id. '\''));     
            $get_cs_subjects=DBGet(DBQuery('SELECT * FROM course_subjects WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\'')); 
            foreach($get_cs_subjects as $gcsi=>$gcsd)
            {
                $get_course=DBGet(DBQuery('SELECT COURSE_ID,SYEAR,TITLE,SHORT_NAME,GRADE_LEVEL FROM courses WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\' AND SUBJECT_ID=\''.$gcsd['SUBJECT_ID'].'\''));
                foreach($get_course as $gc)
                {
                    $sql_columns=array('SUBJECT_ID','INSTITUTE_ID');
                    $sql_values=array($get_ts_subjects[$gcsi]['SUBJECT_ID'],$id);
                    foreach($gc as $gcc=>$gcd)
                    {
                        if($gcd!='' && $gcc!='GRADE_LEVEL')
                        {
                            if($gcc != 'COURSE_ID' && $gcc != 'TITLE' && $gcc != 'SHORT_NAME') {
                                $sql_columns[]=$gcc;
                                $sql_values[]="'".addslashes($gcd)."'";
                            }
                        }
                        if($gcd!='' && $gcc=='GRADE_LEVEL')
                        {
                            foreach($get_cs_grade as $gcsgi=>$gcsgd)
                            {
                                // if($gcd==$gcsd['ID']) 
                                if($gcd==$gcsgd['ID'])
                                {
                                    $sql_columns[]='GRADE_LEVEL';
                                    $sql_values[]="'".$get_ts_grade[$gcsgi]['ID']."'";
                                }
                            }
                        }
                    }
                    
                    DBQuery('INSERT INTO courses ('.implode(',',$sql_columns).') VALUES ('.(implode(',',$sql_values)).')');

                    DBQuery('UPDATE courses SET TITLE = (SELECT * FROM (SELECT TITLE FROM courses WHERE COURSE_ID=\''.$gc['COURSE_ID'].'\') AS T1), SHORT_NAME = (SELECT * FROM (SELECT SHORT_NAME FROM courses WHERE COURSE_ID=\''.$gc['COURSE_ID'].'\') AS T2) WHERE COURSE_ID = (SELECT * FROM (SELECT MAX(COURSE_ID) AS COURSE_ID FROM courses) AS T3)');
                }
            }
            break;
    }
}

echo '</div>';
echo '</div>'; //.row
?>
