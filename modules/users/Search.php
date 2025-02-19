<?php

include('../../RedirectModulesInc.php');
$modcat = 'users';
$_REQUEST['modname'] = '';
if (AllowUse('users/User.php')) {
    if (User('PROFILE_ID') == 4) {
        $_REQUEST['modname'] = 'users/User.php';
        $_REQUEST['next_modname'] = 'users/User.php';
    } else {
        $_REQUEST['modname'] = 'users/Staff.php';
        $_REQUEST['next_modname'] = 'users/Staff.php';
    }
} elseif (AllowUse('users/Preferences.php')) {
    $_REQUEST['modname'] = 'users/Preferences.php';
}
if ($_REQUEST['modname']) {
    //echo "<SCRIPT language=javascript>parent.help.location=\"Bottom.php?modcat=$modcat&modname=$_REQUEST[modname]\";</SCRIPT>";
    include("modules/$_REQUEST[modname]");
}
?>