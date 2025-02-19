<?php

 
include('../../RedirectModulesInc.php');
if ($_REQUEST['student_id'] == 'new') {
    unset($_SESSION['student_id']);
    $_SESSION['unset_student'] = true;
}
if (User('PROFILE') == 'parent' || User('PROFILE') == 'student') {
    $_REQUEST['modname'] = 'scheduling/ViewSchedule.php';
} else {
    $_REQUEST['modname'] = 'scheduling/Schedule.php';
}

$modcat = 'scheduling';

if (AllowUse($_REQUEST['modname'])) {
    //echo "<SCRIPT language=javascript>window.location.href = window.location.href.replace('Search.php','Schedule.php');parent.help.location=\"Bottom.php?modcat=$modcat&modname=$_REQUEST[modname]\";</SCRIPT>";
    include("modules/$_REQUEST[modname]");
}
?>