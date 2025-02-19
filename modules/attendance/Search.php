<?php
 
include('../../RedirectModulesInc.php');
if (User('PROFILE') == 'teacher')
    $_REQUEST['modname'] = 'attendance/TakeAttendance.php';
elseif (User('PROFILE') == 'parent' || User('PROFILE') == 'student')
    $_REQUEST['modname'] = 'attendance/StudentSummary.php';
else
    $_REQUEST['modname'] = 'attendance/Administration.php';
$modcat = 'attendance';
if (AllowUse($_REQUEST['modname'])) {
    //echo "<SCRIPT language=javascript>parent.help.location=\"Bottom.php?modcat=$modcat&modname=$_REQUEST[modname]\";</SCRIPT>";
    include("modules/$_REQUEST[modname]");
}
?>