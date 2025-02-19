<?php
 
include('../../RedirectModulesInc.php');
if (User('PROFILE') == 'teacher')
    $_REQUEST['modname'] = 'eligibility/EnterEligibility.php';
else
    $_REQUEST['modname'] = 'eligibility/Student.php';
$modcat = 'eligibility';
if (AllowUse($_REQUEST['modname'])) {
    //echo "<SCRIPT language=javascript>parent.help.location=\"Bottom.php?modcat=$modcat&modname=$_REQUEST[modname]\";</SCRIPT>";
    include("modules/$_REQUEST[modname]");
}
?>