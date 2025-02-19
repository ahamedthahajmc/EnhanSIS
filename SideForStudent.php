<?php
 
session_start();
unset($_SESSION['student_id']);
unset($_SESSION['students_order']);
unset($_SESSION['_REQUEST_vars']);
unset($_SESSION['_REQUEST_vars']);
$request_modname = explode("/",$_REQUEST['modname']);
foreach ($request_modname as $mod_key => $mod_val) {
	$request_modname[$mod_key] = strip_tags(urlencode(trim($mod_val)));
}
$filtered_modname = implode("/", $request_modname);

echo "<script>window.location.href='Modules.php?modname=".$filtered_modname."&ajax=true';</script>";


?>
