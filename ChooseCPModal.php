<?php
 
include('RedirectRootInc.php');
include'ConfigInc.php';
include 'Warehouse.php';

if( $_REQUEST['table_name']!='' && $_REQUEST['table_name']=='course_periods')
{

    $sql = "SELECT * FROM course_periods WHERE COURSE_ID='$_REQUEST[id]'AND (marking_period_id IS NOT NULL AND marking_period_id IN(".  GetAllMP(GetMPTable(GetMP(UserMP(),'TABLE')),  UserMP()).") OR marking_period_id IS NULL AND '".date('Y-m-d')."' <= end_date) ORDER BY TITLE";
    $QI = DBQuery($sql);

    $coursePeriods_RET = DBGet($QI);
    $html='cp_modal||';
    $html.=count($coursePeriods_RET).((count($coursePeriods_RET)==1)?' '._periodWas.'':' '._periodsWere.'').' '._found.'.<br>';
    if(count($coursePeriods_RET)>0)
    {
        $html.='<table class="table table-bordered"><tr class="bg-grey-200"><th>'._coursePeriods.'</th></tr>'; 

        foreach($coursePeriods_RET as $val)
        {
            $subject_id=DBGet(DBQuery('SELECT SUBJECT_ID FROM courses WHERE COURSE_ID='.$val['COURSE_ID']));
//        $html.= '<tr><td><a href=javascript:void(0); onclick="selectCpModal(\'course_div\',\''.$val['TITLE'].'\');">'.$val['TITLE'].'</a></td></tr>';
//        $html.= '<tr><td><a href="Modules.php?modname=scheduling/MassSchedule.php&subject_id='.$subject_id[1]['SUBJECT_ID'].'&course_id='.$val['COURSE_ID'].'&modfunc=choose_course&course_period_id='.$val['COURSE_PERIOD_ID'].'" >'.$val['TITLE'].'</a></td></tr>';
          $html.= '<tr><td><a href=javascript:void(0); onclick="cpActionModal(\''.$val['TITLE'].'\',\''.$subject_id[1]['SUBJECT_ID'].'\',\''.$val['COURSE_ID'].'\',\''.$val['COURSE_PERIOD_ID'].'\');">'.$val['TITLE'].'</a></td></tr>';
        }
        $html.='</table>';
    }    
}
                
if( $_REQUEST['table_name']!='' && $_REQUEST['table_name']=='courses')
{

    $sql="SELECT COURSE_ID,c.TITLE, CONCAT_WS(' - ',c.short_name,c.title) AS GRADE_COURSE FROM courses c LEFT JOIN institute_gradelevels sg ON c.grade_level=sg.id WHERE SUBJECT_ID='$_REQUEST[id]' ORDER BY c.TITLE";
    $QI = DBQuery($sql);
    $courses_RET = DBGet($QI);
    $html='course_modal||';
    $html.=count($courses_RET). ((count($courses_RET)==1)?' '._courseWas.'':' '._coursesWere.'').' '._found.'.';
    if(count($courses_RET)>0)
    {
    $html.='<table  class="table table-bordered"><tr class="bg-grey-200"><th>'._course.'</th></tr>';
    foreach($courses_RET as $val)
    {

    $html.= '<tr><td><a href=javascript:void(0); onclick="chooseCpModal('.$val['COURSE_ID'].',\'course_periods\')">'.$val['TITLE'].'</a></td></tr>';
    }
    $html.='</table>';
    }
}
                
echo $html;
?>
