<?php
 
session_start();
include('RedirectRootInc.php');
include'ConfigInc.php';
include 'Warehouse.php';
// include('functions/SqlSecurityFnc.php');

if($_REQUEST['title'])
{
    $cp_id = sqlSecurityFilter($_REQUEST['course_period_id']);
    
    if($_REQUEST['course_period_id'])
    {
    $_SESSION['MassSchedule.php']['course_period_id']=$_REQUEST['course_period_id'];
    $gender_res = DBGet(DBQuery('SELECT GENDER_RESTRICTION FROM course_periods WHERE COURSE_PERIOD_ID='.$cp_id));
    $marking_period= DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM course_periods WHERE COURSE_PERIOD_ID='.$cp_id));
    if($marking_period[1]['MARKING_PERIOD_ID']==''){
        $get_syear_mpid=DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_years WHERE INSTITUTE_ID='.UserInstitute().' AND SYEAR='.UserSyear()));
        $marking_period=$get_syear_mpid;
    }
    $get_mp_det=DBGet(DBQuery('SELECT * FROM marking_periods WHERE MARKING_PERIOD_ID='.$marking_period[1]['MARKING_PERIOD_ID']));

    $_SESSION['MassSchedule.php']['gender'] = $gender_res[1]['GENDER_RESTRICTION'];
//        $_REQUEST['title'] = str_replace('"', '\"', $_REQUEST['title']);
        if ($gender_res[1]['GENDER_RESTRICTION'] != 'N')
        $_REQUEST['title']=$_REQUEST['title'].' - Gender : '.($gender_res == 'M' ? 'Male' : 'Female');
    }
    if($_REQUEST['course_id'])
    $_SESSION['MassSchedule.php']['course_id']=$_REQUEST['course_id'];
    if($_REQUEST['subject_id'])
    $_SESSION['MassSchedule.php']['subject_id']=$_REQUEST['subject_id'];
    
    echo $_REQUEST['title'].'|_*|*_|'.$get_mp_det[1]['MARKING_PERIOD_ID'].'|_*|*_|'.$get_mp_det[1]['TITLE'];
    
}
?>
