<?php
 
include('RedirectRootInc.php');
include('ConfigInc.php');
include('Warehouse.php');
if ($_REQUEST['modfunc'] == 'detail' && $_REQUEST['student_id'] && $_REQUEST['student_id'] != 'new') {
    if ($_POST['button'] == 'Save') {

        if ($_REQUEST['TRANSFER']['INSTITUTE'] != '' && $_REQUEST['TRANSFER']['Grade_Level'] != '') {
            $drop_code = $_REQUEST['drop_code'];

            $_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_END_DATE'] = date("Y-m-d", strtotime($_REQUEST['year_TRANSFER']['STUDENT_ENROLLMENT_END_DATE'] . '-' . $_REQUEST['month_TRANSFER']['STUDENT_ENROLLMENT_END_DATE'] . '-' . $_REQUEST['day_TRANSFER']['STUDENT_ENROLLMENT_END_DATE']));

            $gread_exists = DBGet(DBQuery('SELECT COUNT(TITLE) AS PRESENT,ID FROM institute_gradelevels WHERE INSTITUTE_ID=\'' . $_REQUEST['TRANSFER']['INSTITUTE'] . '\' AND TITLE=(SELECT TITLE FROM
                            institute_gradelevels WHERE ID=(SELECT GRADE_ID FROM student_enrollment WHERE
                            STUDENT_ID=\'' . $_REQUEST['student_id'] . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\'  AND SYEAR=\'' . UserSyear() . '\'  ORDER BY ID DESC LIMIT 1))'));  //pinki

            $_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_START'] = date("Y-m-d", strtotime($_REQUEST['year_TRANSFER']['STUDENT_ENROLLMENT_START'] . '-' . $_REQUEST['month_TRANSFER']['STUDENT_ENROLLMENT_START'] . '-' . $_REQUEST['day_TRANSFER']['STUDENT_ENROLLMENT_START']));




            if (strtotime($_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_START']) >= strtotime($_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_END_DATE'])) {
                $check_asociation = DBGet(DBQuery('SELECT COUNT(STUDENT_ID) as REC_EX FROM student_enrollment WHERE STUDENT_ID=' . $_REQUEST['student_id'] . ' AND SYEAR=' . UserSyear() . ' AND INSTITUTE_ID=' . UserInstitute() . ' AND START_DATE<=\'' . $_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_END_DATE'] . '\' AND (END_DATE IS NULL OR END_DATE=\'0000-00-00\' AND END_DATE<=\'' . $_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_END_DATE'] . '\') ORDER BY ID DESC LIMIT 0,1'));
                if ($check_asociation[1]['REC_EX'] != 0) {
                    DBQuery('UPDATE student_enrollment SET DROP_CODE=\'' . $drop_code . '\',END_DATE=\'' . $_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_END_DATE'] . '\' WHERE STUDENT_ID=\'' . $_REQUEST['student_id'] . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\'  AND SYEAR=\'' . UserSyear() . '\'');  //pinki    
                    $syear_RET = DBGet(DBQuery("SELECT MAX(SYEAR) AS SYEAR,TITLE FROM institute_years WHERE INSTITUTE_ID=" . $_REQUEST['TRANSFER']['INSTITUTE']));
                    $syear = $syear_RET[1]['SYEAR'];
                    $enroll_code = DBGet(DBQuery('SELECT id FROM student_enrollment_codes WHERE syear=\'' . $syear . '\' AND type=\'TrnE\''));  //pinki
                    $last_institute_RET = DBGet(DBQuery('SELECT INSTITUTE_ID FROM student_enrollment WHERE STUDENT_ID=\'' . $_REQUEST['student_id'] . '\' AND SYEAR=\'' . UserSyear() . '\'')); //pinki
                    $last_institute = $last_institute_RET[1]['INSTITUTE_ID'];
                    $sch_id = $_REQUEST['TRANSFER']['INSTITUTE'];
                    $num_default_cal = DBGet(DBQuery('SELECT CALENDAR_ID FROM institute_calendars WHERE INSTITUTE_ID=' . $_REQUEST['TRANSFER']['INSTITUTE'] . ' AND DEFAULT_CALENDAR=\'Y\' '));
                    if (empty($num_default_cal)) {
                        $qr = DBGet(DBQuery('SELECT CALENDAR_ID FROM institute_calendars WHERE INSTITUTE_ID=' . $_REQUEST['TRANSFER']['INSTITUTE'] . ' LIMIT 0,1'));

                        $calender_id = $qr[1]['CALENDAR_ID'];
                    }
                    if (count($num_default_cal) == 1) {
                        $calender_id = $num_default_cal[1]['CALENDAR_ID'];
                    } else {
                        $calender_id = 'NULL';
                    }
                    if ($gread_exists[1]['PRESENT'] == 1 && $gread_exists[1]['ID']) {
                        DBQuery("INSERT INTO student_enrollment (SYEAR ,INSTITUTE_ID ,STUDENT_ID ,GRADE_ID ,START_DATE ,END_DATE ,ENROLLMENT_CODE ,DROP_CODE ,NEXT_INSTITUTE ,CALENDAR_ID ,LAST_INSTITUTE) VALUES (" . $syear . "," . $_REQUEST['TRANSFER']['INSTITUTE'] . "," . $_REQUEST['student_id'] . "," . $_REQUEST['TRANSFER']['Grade_Level'] . ",'" . $_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_START'] . "',''," . $enroll_code[1]['ID'] . ",'','" . $_REQUEST['TRANSFER']['INSTITUTE'] . "',$calender_id,$last_institute)");
                    } else {
                        DBQuery("INSERT INTO student_enrollment (SYEAR ,INSTITUTE_ID ,STUDENT_ID ,GRADE_ID ,START_DATE ,END_DATE ,ENROLLMENT_CODE ,DROP_CODE ,NEXT_INSTITUTE ,CALENDAR_ID ,LAST_INSTITUTE) VALUES (" . $syear . "," . $_REQUEST['TRANSFER']['INSTITUTE'] . "," . $_REQUEST['student_id'] . "," . $_REQUEST['TRANSFER']['Grade_Level'] . ",'" . $_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_START'] . "',''," . $enroll_code[1]['ID'] . ",'','" . $_REQUEST['TRANSFER']['INSTITUTE'] . "',$calender_id,$last_institute)");
                    }
                    $trans_institute = $syear_RET[1]['TITLE'];

                    $trans_student_RET = DBGet(DBQuery("SELECT FIRST_NAME,LAST_NAME,MIDDLE_NAME,NAME_SUFFIX FROM students WHERE STUDENT_ID='" . $_REQUEST['student_id'] . "'"));

                    $trans_student = $trans_student_RET[1]['LAST_NAME'] . ' ' . $trans_student_RET[1]['FIRST_NAME'];
                    DBQuery('UPDATE medical_info SET INSTITUTE_ID=' . $_REQUEST['TRANSFER']['INSTITUTE'] . ', SYEAR=' . $syear . ' WHERE STUDENT_ID=\'' . $_REQUEST['student_id'] . '\' AND SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\'');
                    unset($_REQUEST['modfunc']);
                    unset($_SESSION['_REQUEST_vars']['student_id']);
                    echo '<SCRIPT language=javascript>opener.document.location = "Modules.php?modname=students/Student.php&modfunc=&search_modfunc=list&next_modname=students/Student.php&stuid=' . $_REQUEST['student_id'] . '"; window.close();</script>';
                } else {
                    unset($_REQUEST['modfunc']);
                    unset($_SESSION['_REQUEST_vars']['student_id']);
                    echo '<SCRIPT language=javascript>alert("Please provide valid date");window.close();</script>';
                }
            } else {
                unset($_REQUEST['modfunc']);
                unset($_SESSION['_REQUEST_vars']['student_id']);
                echo '<SCRIPT language=javascript>alert("Please provide valid date");window.close();</script>';
            }
        } else {

            if ($_REQUEST['TRANSFER']['INSTITUTE'] == '' && $_REQUEST['TRANSFER']['Grade_Level'] != '')
                echo '<SCRIPT language=javascript>alert("Please select Institute");window.close();</script>';
            if ($_REQUEST['TRANSFER']['INSTITUTE'] != '' && $_REQUEST['TRANSFER']['Grade_Level'] == '')
                echo '<SCRIPT language=javascript>alert("Please select Grade Level");window.close();</script>';
            if ($_REQUEST['TRANSFER']['INSTITUTE'] == '' && $_REQUEST['TRANSFER']['Grade_Level'] == '')
                unset($_REQUEST['modfunc']);
            echo '<SCRIPT language=javascript>alert("Please select Institute and Grade Level");window.close();</script>';
        }
    }
    else {

        $sql = "SELECT ID,TITLE FROM institutes WHERE ID !=" . UserInstitute();
        $sql2 = DBGet(DBQuery('SELECT ID,TITLE FROM institutes WHERE ID !=' . UserInstitute() . '  LIMIT 0,1'));
        $sch_id = $sql2[1]['ID'];
        if ($sch_id != '') {
            $QI = DBQuery($sql);
            $institutes_RET = DBGet($QI);
            foreach ($institutes_RET as $institute_array) {
                $options[$institute_array['ID']] = $institute_array['TITLE'];
            }
            $res = DBGet(DBQuery('SELECT * FROM institute_gradelevels WHERE institute_id=' . $sch_id . ''));
            $options1 = array();
            foreach ($res as $res1) {
                $options1[$res1['ID']] = $res1['TITLE'];
            }

            $extraM .= 'onchange=grab_GradeLevel(this.value)';
            $exg = 'id="grab_grade"';
            
            echo '<div class="modal-header">';
            echo '<button type="button" class="close" data-dismiss="modal">×</button>';
            echo '<h5 class="modal-title">'._transferredOut.'</h5>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo '<input type="hidden" name="values[student_enrollment]['.$_REQUEST['student_id'].'][DROP_CODE]" value="'.$_REQUEST['drop_code'].'" />';
            echo '<div class="form-group datepicker-group">';
            echo '<label class="control-label">'._currentInstituteDropDate.'</label>';
            //echo DateInput_for_EndInputModal('', 'TRANSFER[STUDENT_ENROLLMENT_END_DATE]', '', $div, true);
            echo custom_datepicker('222', 'TRANSFER[STUDENT_ENROLLMENT_END_DATE]');

            echo '</div>';

            echo '<div class="form-group">';
            echo '<label class="control-label">'._transferringTo.'</label>';
            echo SelectInputModal('', 'TRANSFER[INSTITUTE]', '', $options, false, $extraM, 'class=cell_medium');
            echo '</div>';

            echo '<div class="form-group">';
            echo '<label class="control-label">'._gradeLevel.'</label>';
            echo SelectInputModal('', 'TRANSFER[Grade_Level]', '', $options1, false, $exg, 'class=cell_medium');
            echo '</div>';

            echo '<div class="form-group">';
            echo '<label class="control-label">'._newInstituteSEnrollmentDate.'</label>';
            //echo DateInput_for_EndInputModal('', 'TRANSFER[STUDENT_ENROLLMENT_START]', '', $div, true);
            echo custom_datepicker('223', 'TRANSFER[STUDENT_ENROLLMENT_START]');
            echo '</div>';
            echo '</div>'; //.modal-body

            echo '<div class="modal-footer">';
            echo '<INPUT type=submit class="btn btn-primary" name=button value='._save.'>';
            echo '</div>';

            //echo '</FORM>';

            unset($_REQUEST['values']);
            unset($_SESSION['_REQUEST_vars']['values']);
            unset($_REQUEST['button']);
            unset($_SESSION['_REQUEST_vars']['button']);
        } else {
            echo '<div align=center class="m-15">There is only one institute in the system so the student cannot be transferred to any other institute<br /><br>
                   <input type=button class="btn btn-default" value=Close onclick=\'closeThisModal("modal_default_transferred_out");\'></div>
                    </form>';
//            PopTableWindow('footer');


            unset($_REQUEST['values']);
            unset($_SESSION['_REQUEST_vars']['values']);
            unset($_REQUEST['button']);
            unset($_SESSION['_REQUEST_vars']['button']);
        }
    }
}

function custom_datepicker($id, $name) {
    $dt.= '<div class="input-group datepicker-group" id="original_date_' . $id . '" value="" style="">';
    $dt.= '<span class="input-group-addon"><i class="icon-calendar22"></i></span>';
    $dt.= '<input id="date_' . $id . '" placeholder="Select Date" value="" class="form-control daterange-single" type="text">';
    $dt.= '</div>';
    $dt.= '<input value="" id="monthSelect_date_' . $id . '" name="month_' . $name . '" type="hidden">';
    $dt.= '<input value="" id="daySelect_date_' . $id . '" name="day_' . $name . '" type="hidden">';
    $dt.= '<input value="" id="yearSelect_date_' . $id . '" name="year_' . $name . '" type="hidden">';
    echo $dt;
}

echo '<script type="text/javascript" src="assets/js/pages/picker_date.js"></script>';
