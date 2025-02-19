<?php
 
include('RedirectRootInc.php');
error_reporting(0);

if (file_exists("Data.php"))
{
    include("Data.php");
}
if($DatabaseServer == '')
{
    // redirect user to the install procedure
    header('Location: install/index.php');
}
else {
    // Server Names and Paths
    db_start();

    $sql = DBQuery("select value from app where name='build'");
    $build = $sql->fetch_assoc();
    $month = substr($build['value'],0,2);
    $day = substr($build['value'],2,2);
    $year = substr($build['value'],4,4);
    $revision = substr($build['value'],8,3);

    $build_date = mktime(0,0,0,$month,$day,$year);
    if ($build_date < mktime(0,0,0,5,28,2009))
    {
        if($revision == '000') {
            // redirect user to the upgrade procedure
            header('Location: install/index.php?upreq=true');
        }
    }
}
?>
