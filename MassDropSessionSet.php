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
    $_SESSION['MassDrops.php']['course_period_id']=$_REQUEST['course_period_id'];
    $gender_res = DBGet(DBQuery('SELECT GENDER_RESTRICTION FROM course_periods WHERE COURSE_PERIOD_ID='.$cp_id));
    $_SESSION['MassDrops.php']['gender'] = $gender_res[1]['GENDER_RESTRICTION'];
//        $_REQUEST['title'] = str_replace('"', '\"', $_REQUEST['title']);
        if ($gender_res[1]['GENDER_RESTRICTION'] != 'N')
        $_REQUEST['title']=$_REQUEST['title'].' - Gender : '.($gender_res == 'M' ? 'Male' : 'Female');
    }
    if($_REQUEST['course_id'])
    $_SESSION['MassDrops.php']['course_id']=$_REQUEST['course_id'];
    if($_REQUEST['subject_id'])
    $_SESSION['MassDrops.php']['subject_id']=$_REQUEST['subject_id'];
    
    echo $_REQUEST['title'];
    
}
?>
