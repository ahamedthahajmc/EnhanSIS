<?php

 

include 'RedirectRootInc.php';
include 'ConfigInc.php';
include 'Warehouse.php';

$id = sqlSecurityFilter($_REQUEST['id']);

if ($_REQUEST['table_name'] != '' && $_REQUEST['table_name'] == 'course_periods') {

    $sql = "SELECT * FROM course_periods WHERE COURSE_ID='".$id."'AND (marking_period_id IS NOT NULL AND marking_period_id IN(" . GetAllMP(GetMPTable(GetMP(UserMP(), 'TABLE')), UserMP()) . ") OR marking_period_id IS NULL AND '" . date('Y-m-d') . "' <= end_date) ORDER BY TITLE";
    $QI = DBQuery($sql);

    $coursePeriods_RET = DBGet($QI);
    $html  = 'cp_modal||';
    $html .= '<h6>'.count($coursePeriods_RET) . ((count($coursePeriods_RET) == 1) ? ' '._periodWas.'' : ' '._periodsWere.'') . ' '._found.'.</h6>';
    if (count($coursePeriods_RET) > 0) {
        $html .= '<table class="table table-bordered"><thead><tr class="alpha-grey"><th>'._coursePeriods.'</th></tr></thead>';
        $html .= '<tbody>';

        foreach ($coursePeriods_RET as $val) {
            $subject_id = DBGet(DBQuery('SELECT SUBJECT_ID FROM courses WHERE COURSE_ID=' . $val['COURSE_ID']));
            $html .= '<tr><td><a href=javascript:void(0); onclick="cpPasteField(\'' . $val['TITLE'] . '\',\'' . $val['COURSE_PERIOD_ID'] . '\');">' . $val['TITLE'] . '</a></td></tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
    }
}

if ($_REQUEST['table_name'] != '' && $_REQUEST['table_name'] == 'courses') {

    $sql = "SELECT COURSE_ID,c.TITLE, CONCAT_WS(' - ',c.short_name,c.title) AS GRADE_COURSE FROM courses c LEFT JOIN institute_gradelevels sg ON c.grade_level=sg.id WHERE SUBJECT_ID='".$id."' ORDER BY c.TITLE";
    $QI = DBQuery($sql);
    $courses_RET = DBGet($QI);
    $html  = 'course_modal||';
    $html .= '<h6>'.count($courses_RET) . ((count($courses_RET) == 1) ? ' '._courseWas.'' : ' '._coursesWere.'') . ' '._found.'.</h6>';
    if (count($courses_RET) > 0) {
        $html .= '<table  class="table table-bordered"><thead><tr class="alpha-grey"><th>'._course.'</th></tr></thead>';
        $html .= '<tbody>';
        foreach ($courses_RET as $val) {

            $html.= '<tr><td><a href=javascript:void(0); onclick="chooseCpModalSearch(' . $val['COURSE_ID'] . ',\'course_periods\')">' . $val['TITLE'] . '</a></td></tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
    }
}

echo $html;
?>
