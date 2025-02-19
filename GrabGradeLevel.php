<?php
 

include('RedirectRootInc.php');
include'ConfigInc.php';
include 'Warehouse.php';
$institute_id=(int)$_GET['id'];
$res= DBGet(DBQuery('SELECT * FROM institute_gradelevels WHERE institute_id='.$institute_id.''));
$period_select="<OPTION value=''>N/A</OPTION>";
foreach($res as $r1)
{
    $period_select .= "<OPTION value=$r1[ID]>$r1[TITLE]</OPTION>";
}
echo $period_select;

?>
