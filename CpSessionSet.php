<?php
 

session_start();
include 'RedirectRootInc.php';
include 'ConfigInc.php';
include 'Warehouse.php';

$REQUEST_title = sqlSecurityFilter($_REQUEST['title']);

if($REQUEST_title)
{
    $cp_id = sqlSecurityFilter($_REQUEST['course_period_id']);
    $course_id = sqlSecurityFilter($_REQUEST['course_id']);
    $subject_id = sqlSecurityFilter($_REQUEST['subject_id']);
    
    if($_REQUEST['course_period_id'])
    {
        $_SESSION['MassSchedule.php']['course_period_id']=$_REQUEST['course_period_id'];
        $gender_res = DBGet(DBQuery('SELECT GENDER_RESTRICTION FROM course_periods WHERE COURSE_PERIOD_ID='.$cp_id));
        $_SESSION['MassSchedule.php']['gender'] = $gender_res[1]['GENDER_RESTRICTION'];
        // $_REQUEST['title'] = str_replace('"', '\"', $_REQUEST['title']);
        if ($gender_res[1]['GENDER_RESTRICTION'] != 'N')
            $REQUEST_title = $REQUEST_title . ' - Gender : '.($gender_res == 'M' ? 'Male' : 'Female');
    }

    if($_REQUEST['course_id'])
        $_SESSION['MassSchedule.php']['course_id']=$course_id;
    if($_REQUEST['subject_id'])
        $_SESSION['MassSchedule.php']['subject_id']=$_REQUEST['subject_id'];
    
    echo $REQUEST_title;
}

?>