<?php
 
    error_reporting(0);

	include('RedirectRootInc.php');
	include'ConfigInc.php';
        include("Warehouse.php");
       $atten_chk = strip_tags(trim($_GET['u']));
       $period_id = strip_tags(trim($_GET['p_id']));
    $cp_id=strip_tags(trim($_REQUEST['cp_id']));
    if($period_id==0 && $cp_id!='new')
    {

        $chk_attendance  =DBGet(DBQuery("SELECT PERIOD_ID FROM course_period_var cpv WHERE COURSE_PERIOD_ID='". $cp_id."'"));
        $period_id=$chk_attendance[1]['PERIOD_ID'];
    }
   if($period_id!='')
        {
    $chk_attendance = DBGet(DBQuery("SELECT ATTENDANCE FROM institute_periods WHERE PERIOD_ID='". $period_id."'"));
    $chk_atten_cp=DBGet(DBQuery("SELECT DOES_ATTENDANCE FROM course_period_var WHERE COURSE_PERIOD_ID='". $cp_id."'"));

    $attendance = $chk_attendance[1]['ATTENDANCE'];
    if(($attendance!='Y' && $atten_chk=='Y') || ($attendance!='Y' && $chk_atten_cp['DOES_ATTENDANCE']=='Y' && $atten_chk=='N'))
    {   if(strip_tags(trim($_GET['ids']))=='')
       echo '0';
        else
        echo '0/'.$_GET['ids'];
       }
    else
        {
        echo '1';
    }
    exit;
    }
	
?>