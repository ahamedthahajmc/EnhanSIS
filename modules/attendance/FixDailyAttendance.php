<?php


include('../../RedirectModulesInc.php');
include('lang/language.php');

DrawBC(""._attendance." > " . ProgramTitle());
$message = '<div class="form-inline"><div class="col-md-12"><div class="form-group"><label class="control-label">'._from.'</label></div><div class="inline-block">' . DateInputAY(DBDate('mysql'), 'min', 1) . '</div><div class="form-group"><label class="control-label">'._to.'</label></div><div class="inline-block">' . DateInputAY(DBDate('mysql'), 'max', 2) . '</div></div></div><br/>';
if (Prompt_Home('Confirm', ''._whenDoYouWantToRecalculateTheDailyAttendance.'?', $message)) {
    $current_RET = DBGet(DBQuery('SELECT DISTINCT DATE_FORMAT(INSTITUTE_DATE,\'%d-%m-%Y\') as INSTITUTE_DATE FROM attendance_calendar WHERE INSTITUTE_ID=\'' . UserInstitute() . '\' AND SYEAR=\'' . UserSyear() . '\''), array(), array('INSTITUTE_DATE'));
    $extra = array();
    $students_RET = GetStuList($extra);
//            print_r($current_RET);
    $begin = mktime(0, 0, 0, MonthNWSwitch($_REQUEST['month_min'], 'to_num'), $_REQUEST['day_min'] * 1, $_REQUEST['year_min']) + 43200;
    $end = mktime(0, 0, 0, MonthNWSwitch($_REQUEST['month_max'], 'to_num'), $_REQUEST['day_max'] * 1, $_REQUEST['year_max']) + 43200;

    for ($i = $begin; $i <= $end; $i += 86400) {
        if ($current_RET[date('d-m-Y', $i)]) {
            foreach ($students_RET as $student) {
                UpdateAttendanceDaily($student['STUDENT_ID'], date('Y-m-d', $i));
            }
        }
    }

    unset($_REQUEST['modfunc']);
    echo '<div class="alert bg-success alert-styled-left">The Daily Attendance for that timeframe has been recalculated.</div>';
}
?>