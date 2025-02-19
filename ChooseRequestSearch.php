<?php
 

include 'RedirectRootInc.php';
include 'ConfigInc.php';
include 'Warehouse.php';

$id = sqlSecurityFilter($_REQUEST['id']);

if ($_REQUEST['table_name'] != '' && $_REQUEST['table_name'] == 'courses') {

    $sql = "SELECT COURSE_ID,c.TITLE, CONCAT_WS(' - ',c.short_name,c.title) AS GRADE_COURSE FROM courses c LEFT JOIN institute_gradelevels sg ON c.grade_level=sg.id WHERE SUBJECT_ID='".$id."' ORDER BY c.TITLE";
    $QI = DBQuery($sql);
    $courses_RET = DBGet($QI);
    $html = 'course_modal_request||';
    $html.= '<h6>'.count($courses_RET) . ((count($courses_RET) == 1) ? ' '._courseWas.'' : ' '._coursesWere.'') . ' '._found.'.</h6>';
    if (count($courses_RET) > 0) {
        $html.='<table class="table table-bordered"><thead><tr class="alpha-grey"><th>'._course.'</th></tr></thead>';
        $html.='<tbody>';
        foreach ($courses_RET as $val) {

            $html.= '<tr><td><a href=javascript:void(0); onclick="requestPasteField(\'' . $val['TITLE'] . '\',\'' . $val['COURSE_ID'] . '\');">' . $val['TITLE'] . '</a></td></tr>';
        }
        $html.='</tbody>';
        $html.='</table>';
        $html .= '<div id="request_div" style="display:none;"></div>';
    }
}

echo $html;
?>
