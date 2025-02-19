<?php


include('../../RedirectModulesInc.php');
$next_syear = UserSyear() + 1;
$_SESSION['DT'] = $DatabaseType;
$_SESSION['DS'] = $DatabaseServer;
$_SESSION['DU'] = $DatabaseUsername;
$_SESSION['DP'] = $DatabasePassword;
$_SESSION['DB'] = $DatabaseName;
$_SESSION['DBP'] = $DatabasePort;
$_SESSION['NY'] = $next_syear;

echo '<div id="start_date" class="text-danger"></div>';

echo '<table width="80%" cellpadding="6" cellspacing="6"><tr><td width="50%" valign="top"><div id="back_db" style="display: none; padding-top:60px;" align="center"><img src="assets/missing_attn_loader.gif" /><br/><br/><strong>'._savingDatabaseForBackupBeforeRolloverPleaseDonotClickAnywhere.'.....</strong></div><div id="calculating" style="display: none; padding-top:60px;" align="center"><img src="assets/rollover_anim.gif" /><br/><br/><strong>'._instituteYearRollingOverPleaseWait.'...</strong></div><div id="response" style="font-size:14px"></div></td></tr></table>';
$notice_roll_date = DBGet(DBQuery('SELECT SYEAR FROM institute_years WHERE SYEAR>\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\''));
$cur_session_RET = DBGet(DBQuery('SELECT YEAR(start_date) AS PRE,YEAR(end_date) AS POST FROM institute_years WHERE INSTITUTE_ID=\'' . UserInstitute() . '\' AND SYEAR=\'' . UserSyear() . '\''));
if ($cur_session_RET[1]['PRE'] == $cur_session_RET[1]['POST']) {
    $cur_session = $cur_session_RET[1]['PRE'];
} else {
    $cur_session = $cur_session_RET[1]['PRE'] . '-' . $cur_session_RET[1]['POST'];
}
$rolled = count($notice_roll_date);
$_SESSION['roll_start_date'] = date('Y-m-d', strtotime($_REQUEST['year_roll_start_date'] . "-" . $_REQUEST['month_roll_start_date'] . "-" . $_REQUEST['day_roll_start_date']));
if (trim($_REQUEST['custom_date']) == 'Y') {
    $_SESSION['roll_s_start_date'] = date('Y-m-d', strtotime($_REQUEST['year_roll_institute_start_date'] . "-" . $_REQUEST['month_roll_institute_start_date'] . "-" . $_REQUEST['day_roll_institute_start_date']));
    $_SESSION['roll_s_end_date'] = date('Y-m-d', strtotime($_REQUEST['year_roll_institute_end_date'] . "-" . $_REQUEST['month_roll_institute_end_date'] . "-" . $_REQUEST['day_roll_institute_end_date']));
    $_SESSION['START_MONTH'] = $_REQUEST['month_roll_institute_start_date'];
    $_SESSION['START_DAY'] = $_REQUEST['day_roll_institute_start_date'];
    $_SESSION['START_YEAR'] = $_REQUEST['year_roll_institute_start_date'];
    $_SESSION['END_MONTH'] = $_REQUEST['month_roll_institute_end_date'];
    $_SESSION['END_DAY'] = $_REQUEST['day_roll_institute_end_date'];
    $_SESSION['END_YEAR'] = $_REQUEST['year_roll_institute_end_date'];

    for ($i = 1; $i <= $_REQUEST['total_sem']; $i++) {
        $_SESSION['sem_start'][$i] = date('Y-m-d', strtotime($_REQUEST['year_sem_start_' . $i] . "-" . $_REQUEST['month_sem_start_' . $i] . "-" . $_REQUEST['day_sem_start_' . $i]));
        $_SESSION['sem_end'][$i] = date('Y-m-d', strtotime($_REQUEST['year_sem_end_' . $i] . "-" . $_REQUEST['month_sem_end_' . $i] . "-" . $_REQUEST['day_sem_end_' . $i]));
    }
    for ($i = 1; $i <= $_REQUEST['total_qrt']; $i++) {
        $_SESSION['qrtr_start'][$i] = date('Y-m-d', strtotime($_REQUEST['year_qrtr_start_' . $i] . "-" . $_REQUEST['month_qrtr_start_' . $i] . "-" . $_REQUEST['day_qrtr_start_' . $i]));
        $_SESSION['qrtr_end'][$i] = date('Y-m-d', strtotime($_REQUEST['year_qrtr_end_' . $i] . "-" . $_REQUEST['month_qrtr_end_' . $i] . "-" . $_REQUEST['day_qrtr_end_' . $i]));
    }
    for ($i = 1; $i <= $_REQUEST['total_prg']; $i++) {
        $_SESSION['prog_start'][$i] = date('Y-m-d', strtotime($_REQUEST['year_prog_start_' . $i] . "-" . $_REQUEST['month_prog_start_' . $i] . "-" . $_REQUEST['day_prog_start_' . $i]));
        $_SESSION['prog_end'][$i] = date('Y-m-d', strtotime($_REQUEST['year_prog_end_' . $i] . "-" . $_REQUEST['month_prog_end_' . $i] . "-" . $_REQUEST['day_prog_end_' . $i]));
    }
    $_SESSION['custom_date'] = 'Y';
    $_SESSION['total_sem'] = $_REQUEST['total_sem'];
    $_SESSION['total_qrt'] = $_REQUEST['total_qrt'];
    $_SESSION['total_prg'] = $_REQUEST['total_prg'];
}
if ($rolled == 0) {
    
    $tables = array('staff' => _staff,
     'institute_periods' => _institutePeriods,
     'institute_years' => _markingPeriods,
     'institute_calendars' => _calendars,
     'report_card_grade_scales' => _reportCardGradeCodes,
     'course_subjects' => _subjects,
     'courses' => _courses,
     'course_periods' => _coursePeriods,
     'student_enrollment' => _students,
     'report_card_comments' => _reportCardCommentCodes,
     'honor_roll' =>_honorRollSetup,
     'attendance_codes' => _attendanceCodes,
     'student_enrollment_codes' => _studentEnrollmentCodes,
    );
    $no_institute_tables = array('student_enrollment_codes' =>true, 'staff' =>true);
    $required = array('staff' =>true, 'institute_years' =>true, 'student_enrollment' =>true, 'student_enrollment_codes' =>true);
    $i = 0;
    $j = 0;
    $rollover_rolled=5;
    foreach ($tables as $table => $name) {
        if ($i == 0 && $j == 0) {
            $table_list .= '<div class="row">';
        } elseif ($i == 0 && $j > 0) {
            $table_list .= '</div><div class="row">';
        }
        $table_list .= '<div class="col-md-3"><label class="checkbox checkbox-inline checkbox-switch switch-success switch-xs"><INPUT type=checkbox value=Y  name=' . $table . ' CHECKED ' . ($required[$table] ? ' disabled="disabled"' : '') . ' onchange="validate_rollover(this.form,this)"><span></span> ' . $name . '</label></div>';
        $i++;
        if ($i == 4) {
            $i = 0;
        }
        $j++;
    }
    $table_list .= '</div>'; //.row
    //$table_list .= '<hr/>';
    //
    //===============================
    $rolledover_table=11;
    if (Prompt_rollover(_confirmRollover, ''._areYouSureYouWantToRollTheDataFor.' ' . $cur_session . ' '._toTheNextInstituteYear.' ?', $table_list)) {
        echo "<script type='text/javascript'>back_before_roll();</script>";
    }
} else {
    Prompt_rollover_back(_rolloverCompleted, ''._dataHasBeenRolledoverFor.' ' . $cur_session . ' '._for.' ' . GetInstitute(UserInstitute()) . '');
}

foreach ($tables as $table => $name) {
    echo '<INPUT type=hidden name=hide_' . $table . ' id="chk_' . $table . '" value="' . $_REQUEST[$table] . '">';
}
echo '<div id="staff"></div>';
echo '<div id="institute_periods"></div>';
echo '<div id="institute_years"></div>';
echo '<div id="attendance_calendars"></div>';
echo '<div id="report_card_grade_scales"></div>';
echo '<div id="course_subjects"></div>';
echo '<div id="courses"></div>';
echo '<div id="course_periods"></div>';
echo '<div id="student_enrollment"></div>';
echo '<div id="honor_roll"></div>';
echo '<div id="attendance_codes"></div>';
echo '<div id="student_enrollment_codes"></div>';
echo '<div id="report_card_comments"></div>';
?>