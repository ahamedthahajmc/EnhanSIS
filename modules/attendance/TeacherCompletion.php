<?php

 
include('../../RedirectModulesInc.php');
include('lang/language.php');

if ($_REQUEST['month_date'] && $_REQUEST['day_date'] && $_REQUEST['year_date']) {
//    $date = $_REQUEST['year_date'] . '-' . MonthFormatter($_REQUEST['month_date']) . '-' . $_REQUEST['day_date'];
    $date = $_REQUEST['year_date'] . '-' . $_REQUEST['month_date'] . '-' . $_REQUEST['day_date'];
} else {
    $_REQUEST['day_date'] = date('d');
    $_REQUEST['month_date'] = strtoupper(date('m'));
    $_REQUEST['year_date'] = date('Y');
    $date = $_REQUEST['year_date'] . '-' . $_REQUEST['month_date'] . '-' . $_REQUEST['day_date'];
}

DrawBC(""._attendance." > " . ProgramTitle());
$QI = DBQuery('SELECT sp.PERIOD_ID,sp.TITLE FROM institute_periods sp WHERE sp.INSTITUTE_ID=\'' . UserInstitute() . '\' AND sp.SYEAR=\'' . UserSyear() . '\' AND EXISTS (SELECT \'\' FROM course_periods cp,course_period_var cpv WHERE cp.SYEAR=sp.SYEAR AND cpv.PERIOD_ID=sp.PERIOD_ID AND cpv.DOES_ATTENDANCE=\'Y\') ORDER BY sp.SORT_ORDER');
$periods_RET = DBGet($QI, array(), array('PERIOD_ID'));
$period_select = "<SELECT class=\"form-control\" name=period><OPTION value=''>"._all."</OPTION>";
foreach ($periods_RET as $id => $period)
    $period_select .= "<OPTION value=$id" . (($_REQUEST['period'] == $id) ? ' SELECTED' : '') . ">" . $period[1]['TITLE'] . "</OPTION>";
$period_select .= "</SELECT>";
echo "<FORM class='form-horizontal' action=Modules.php?modname=" . strip_tags(trim($_REQUEST[modname])) . " method=POST>";
echo "<div class=\"panel panel-default\">";
echo "<div class=\"panel-body\">";
DrawHeaderHome('<div class="form-inline clearfix"><div class="col-md-12"><div class="inline-block">' . PrepareDateSchedule($date, 'date', false, array('submit' =>true)) . '</div><div class="form-group m-l-15">' . $period_select . '</div><div class="form-group"> &nbsp;<INPUT type=submit class="btn btn-primary" value='._go.'></div></div></div>');
echo '</div>'; //.panel-body
echo '</div>'; //.panel.panel-default
echo '</FORM>';


$day = date('D', strtotime($date));
switch ($day) {
    case 'Sun':
        $day = 'U';
        break;
    case 'Thu':
        $day = 'H';
        break;
    default:
        $day = substr($day, 0, 1);
        break;
}
$p = optional_param('period', '', PARAM_SPCL);
$current_mp = GetCurrentMP('QTR', $date);
$MP_TYPE = 'QTR';
if (!$current_mp) {
    $current_mp = GetCurrentMP('SEM', $date);
    $MP_TYPE = 'SEM';
}
if (!$current_mp) {
    $current_mp = GetCurrentMP('FY', $date);
    $MP_TYPE = 'FY';
}
$sql = 'SELECT concat(s.LAST_NAME,\',\',s.FIRST_NAME, \' \') AS FULL_NAME,sp.TITLE,cpv.PERIOD_ID,s.STAFF_ID
        FROM staff s,course_periods cp,course_period_var cpv,institute_periods sp,attendance_calendar acc
        WHERE
        sp.PERIOD_ID = cpv.PERIOD_ID
        AND acc.CALENDAR_id=cp.CALENDAR_ID
        AND cp.COURSE_PERIOD_ID=cpv.COURSE_PERIOD_ID
        AND acc.INSTITUTE_DATE=\'' . date('Y-m-d', strtotime($date)) . '\'
        AND cp.TEACHER_ID=s.STAFF_ID AND cp.MARKING_PERIOD_ID IN (' . GetAllMP($MP_TYPE, $current_mp) . ')
        AND cp.SYEAR=\'' . UserSyear() . '\' AND cp.INSTITUTE_ID=\'' . UserInstitute() . '\' AND s.PROFILE=\'teacher\'
        AND cpv.DOES_ATTENDANCE=\'Y\' AND instr(cpv.DAYS,\'' . $day . '\')>0' . (($p) ? ' AND cpv.PERIOD_ID=\'' . $p . '\'' : '') . '
        AND NOT EXISTS (SELECT \'\' FROM attendance_completed ac WHERE ac.STAFF_ID=cp.TEACHER_ID AND ac.INSTITUTE_DATE=\'' . date('Y-m-d', strtotime($date)) . '\' AND ac.PERIOD_ID=sp.PERIOD_ID)
		';
$RET = DBGet(DBQuery($sql), array(), array('STAFF_ID', 'PERIOD_ID'));
$i = 0;
if (count($RET)) {
    foreach ($RET as $staff_id => $periods) {
        $i++;
        $staff_RET[$i]['FULL_NAME'] = $periods[key($periods)][1]['FULL_NAME'];
        foreach ($periods as $period_id => $period) {
            $staff_RET[$i][$period_id] = '<i class="fa fa-times fa-lg text-danger"></i>';
        }
    }
}

$columns = array('FULL_NAME' => 'Teacher');
if (!$_REQUEST['period']) {
    foreach ($periods_RET as $id => $period)
        $columns[$id] = $period[1]['TITLE'];
} else
    $period_title = $periods_RET[$_REQUEST['period']][1]['TITLE'] . ' ';
echo '<div class="panel panel-default">';
ListOutput($staff_RET, $columns, ''._teacherWhoHasntTaken.' ' . $period_title . ''._attendance.'', ''._teachersWhoHaventTaken.' ' . $period_title . ''._attendance.'');
echo '</div>';
?>
