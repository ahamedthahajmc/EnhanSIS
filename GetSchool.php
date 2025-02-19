<?php
 
	include('RedirectRootInc.php'); 
	include'ConfigInc.php';
        include("Warehouse.php");
// include('functions/SqlSecurityFnc.php');

$marking_period = sqlSecurityFilter($_GET['u']);
                $get_institutename = DBGet(DBQuery("SELECT institute_name FROM  history_marking_periods  WHERE marking_period_id = $marking_period"));
        if($get_institutename[1]['institute_name'])
            echo $get_institutename[1]['institute_name'];
        else
        {
             $get_instituteid = DBGet(DBQuery("SELECT institute_id FROM  marking_periods  WHERE marking_period_id = $marking_period"));
             if($get_instituteid[1]['institute_id'])
             {
                $get_instituteid = DBGet(DBQuery("SELECT title FROM  institutes  WHERE id = $get_instituteid[1][institute_id]")); 
                 echo $get_instituteid[1]['title'];
             }
        }

?>
