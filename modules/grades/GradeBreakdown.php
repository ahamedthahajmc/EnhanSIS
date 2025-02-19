<?php

 
include('../../RedirectModulesInc.php');
DrawBC(""._gradebook." > " . ProgramTitle());

echo '<div class="panel panel-default">';
$i = 0;
if (!$_REQUEST['gd_mp'])
    $mp = UserMP();
else
    $mp = $_REQUEST['gd_mp'];

$chk_qua = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,SEMESTER_ID FROM institute_quarters WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\''));
if ($chk_qua)
    $sem = GetParentMP('SEM', UserMP());
else
    $sem = GetParentMP('FY', UserMP());

echo "<FORM class=\"no-margin\" action=Modules.php?modname=" . strip_tags(trim($_REQUEST[modname])) . " method=POST name=gd_brk_dwn id=gd_brk_dwn >";
$mp_select = "<SELECT name=mp class=\"form-control\" onchange=\"window.location='Modules.php?modname=$_REQUEST[modname]&mp='+this.options[this.selectedIndex].value;\" ><OPTION value=" . UserMP() . ">" . GetMP(UserMP()) . "</OPTION><OPTION value=" . $sem . (($sem == $_REQUEST['mp']) ? ' SELECTED' : '') . ">" . GetMP($sem) . "</OPTION><OPTION value=E" . $sem . (('E' . $sem == $_REQUEST['mp']) ? ' SELECTED' : '') . ">" . GetMP($sem) . ' Exam</OPTION></SELECT>';
DrawHeader(_gradeBreakdown,'<div class="form-group">'.$mp_select.'</div>');
echo '</FORM>';

echo '<hr class="no-margin"/>';

$sql = 'SELECT CONCAT(s.LAST_NAME,\', \',s.FIRST_NAME) as FULL_NAME,s.STAFF_ID,g.REPORT_CARD_GRADE_ID FROM student_report_card_grades g,staff s,staff_institute_relationship ssr,course_periods cp WHERE g.COURSE_PERIOD_ID=cp.COURSE_PERIOD_ID AND cp.TEACHER_ID=s.STAFF_ID AND s.STAFF_ID=ssr.STAFF_ID AND cp.SYEAR=ssr.SYEAR AND cp.SYEAR=g.SYEAR AND ssr.SYEAR=\'' . UserSyear() . '\' AND g.MARKING_PERIOD_ID=\'' . (isset($_REQUEST['mp']) ? $_REQUEST['mp'] : UserMP()) . '\'';
$grouped_RET = DBGet(DBQuery($sql), array(), array('STAFF_ID', 'REPORT_CARD_GRADE_ID'));
$grades_RET = DBGet(DBQuery('SELECT rg.ID,rg.TITLE FROM report_card_grades rg,report_card_grade_scales rs WHERE rg.INSTITUTE_ID=\'' . UserInstitute() . '\' AND rg.SYEAR=\'' . UserSyear() . '\' AND rs.ID=rg.GRADE_SCALE_ID ORDER BY rs.SORT_ORDER,rs.ID,rg.BREAK_OFF IS NOT NULL DESC,rg.BREAK_OFF DESC,rg.SORT_ORDER'));
if (count($grouped_RET)) {
    foreach ($grouped_RET as $staff_id => $grades) {
        $i++;
        $teachers_RET[$i]['FULL_NAME'] = $grades[key($grades)][1]['FULL_NAME'];
        foreach ($grades_RET as $grade)
            $teachers_RET[$i][$grade['ID']] = (is_countable($grades[$grade['ID']])) ? count($grades[$grade['ID']]) : 0;
        
    }
}

$columns = array('FULL_NAME' =>_teacher);
foreach ($grades_RET as $grade)
    $columns[$grade['ID']] = $grade['TITLE'];

echo '<div class="table-responsive">';
ListOutput($teachers_RET, $columns, _teacher, _teachers);
echo '</div>'; //.table-responsive

echo '</div>'; //.panel
?>
