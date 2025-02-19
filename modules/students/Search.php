<?php

include('../../RedirectModulesInc.php');
$modcat = 'students';
$_REQUEST['modname'] = '';
if (AllowUse('students/Student.php')) {
    $_REQUEST['modname'] = $_REQUEST['next_modname'] = 'students/Student.php';
    if (User('PROFILE') == 'parent' || User('PROFILE') == 'student')
        $_REQUEST['search_modfunc'] = 'list';
}
if ($_REQUEST['modname']) {
    //echo "<SCRIPT language=javascript>parent.help.location=\"Bottom.php?modcat=$modcat&modname=$_REQUEST[modname]\";</SCRIPT>";
    include("modules/$_REQUEST[modname]");
}
?>