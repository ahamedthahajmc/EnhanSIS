<?php


include('../../RedirectModulesInc.php');
$_REQUEST['modname'] = "institutesetup/Calendar.php";
$js_extra = "window.location.href = window.location.href.replace('Search.php','Calendar.php');";

$modcat = 'institutesetup';
if (AllowUse($_REQUEST['modname'])) {
    //echo "<SCRIPT language=javascript>".$js_extra."parent.help.location=\"Bottom.php?modcat=$modcat&modname=$_REQUEST[modname]\";</SCRIPT>";
    include("modules/$_REQUEST[modname]");
}
?>
