<?php

 
include('../../RedirectModulesInc.php');
include('lang/language.php');

$QI = DBQuery("SELECT PERIOD_ID,TITLE FROM institute_periods WHERE INSTITUTE_ID='" . UserInstitute() . "' AND SYEAR='" . UserSyear() . "' ORDER BY SORT_ORDER ");
$periods_RET = DBGet($QI);

DrawBC(""._scheduling." > " . ProgramTitle());
echo "<FORM class='m-0' action=Modules.php?modname=" . strip_tags(trim($_REQUEST[modname])) . " method=POST>";
DrawHeader($period_select);
echo '</FORM>';
if ($_REQUEST['search_modfunc'] == 'list') {
    $mp = GetAllMP('QTR', UserMP());

    if (!isset($mp))
        $mp = GetAllMP('SEM', UserMP());

    if (!isset($mp))
        $mp = GetAllMP('FY', UserMP());
    Widgets('course');
    Widgets('request');
    $extra['SELECT'] .= ',sp.PERIOD_ID';
    $extra['FROM'] .= ',institute_periods sp,schedule ss,course_periods cp,course_period_var cpv';
    $extra['WHERE'] .= ' AND (\'' . DBDate() . '\' BETWEEN ss.START_DATE AND ss.END_DATE OR ss.END_DATE IS NULL) AND ss.INSTITUTE_ID=ssm.INSTITUTE_ID AND ss.MARKING_PERIOD_ID IN (' . $mp . ') AND ss.STUDENT_ID=ssm.STUDENT_ID AND ss.SYEAR=ssm.SYEAR AND ss.COURSE_PERIOD_ID=cp.COURSE_PERIOD_ID AND cp.COURSE_PERIOD_ID=cpv.COURSE_PERIOD_ID AND cpv.PERIOD_ID=sp.PERIOD_ID ';
    if (UserStudentID())
        $extra['WHERE'] .= ' AND s.STUDENT_ID=\'' . UserStudentID() . '\' ';
    $extra['group'] = array('STUDENT_ID', 'PERIOD_ID');

    $schedule_RET = GetStuList($extra);
}
unset($extra);
$extra['force_search'] = true;
$extra['new'] = true;

$extra['search'] .= '<div class="row">';
$extra['search'] .= '<div class="col-md-6">';
Widgets('course');
$extra['search'] .= '</div>'; //.col-md-6

$extra['search'] .= '<div class="col-md-6">';
Widgets('request');
$extra['search'] .= '</div>'; //.col-md-6
$extra['search'] .= '</div>'; //.row

foreach ($periods_RET as $period) {
    $extra['SELECT'] .= ',NULL AS PERIOD_' . $period['PERIOD_ID'];
    $extra['columns_after']['PERIOD_' . $period['PERIOD_ID']] = $period['TITLE'];
    $extra['functions']['PERIOD_' . $period['PERIOD_ID']] = '_preparePeriods';
}
if (!$_REQUEST['search_modfunc'])
    Search('student_id', $extra);
else {
    $singular = _studentWithAnIncompleteSchedule;
    $plural = _studentsWithIncompleteSchedules;

    $students_RET = GetStuList($extra);
    $bad_students[0] = array();
    foreach ($students_RET as $student) {
        $check_count1 = is_countable($schedule_RET[$student['STUDENT_ID']]) ? count($schedule_RET[$student['STUDENT_ID']]): 0;
        // if (count($schedule_RET[$student['STUDENT_ID']]) != count($periods_RET))
        if ($check_count1 != count($periods_RET))
            $bad_students[] = $student;
    }
    if (!is_array($extra['columns_after'])) {
        $extra['columns_after'] = array();
    }
    unset($bad_students[0]);

    $link['FULL_NAME']['link'] = "Modules.php?modname=scheduling/Schedule.php";
    $link['FULL_NAME']['variables'] = array('student_id' => 'STUDENT_ID');
    echo '<div class="panel panel-default">';
    echo '<div class="table-responsive">';
    ListOutput($bad_students, array('FULL_NAME' =>_student, 'STUDENT_ID' =>_studentId, 'GRADE_ID' =>_grade) + $extra['columns_after'], $singular, $plural, $link);
    echo "</div>"; //.table-responsive
    echo "</div>"; //.panel.panel-default
}

function _preparePeriods($value, $name) {
    global $THIS_RET, $schedule_RET;

    $period_id = substr($name, 7);
    if (!$schedule_RET[$THIS_RET['STUDENT_ID']][$period_id])
        return '<IMG SRC=assets/x.gif>';
    else
        return '';
}

?>