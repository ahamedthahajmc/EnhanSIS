<?php
 
include('../../RedirectModulesInc.php');
if (User('PROFILE') == 'teacher')
    $_REQUEST['modname'] = 'grades/Grades.php';
elseif (User('PROFILE') == 'parent' || User('PROFILE') == 'student')
    $_REQUEST['modname'] = 'grades/StudentGrades.php';
else
    $_REQUEST['modname'] = 'grades/ReportCards.php';

$modcat = 'grades';
if (AllowUse($_REQUEST['modname'])) {
    //echo "<SCRIPT language=javascript>parent.help.location=\"Bottom.php?modcat=$modcat&modname=$_REQUEST[modname]\";</SCRIPT>";
    include("modules/$_REQUEST[modname]");
}
?>