<?php
 
include("Data.php");
include("Warehouse.php");
$con=mysql_connect($DatabaseServer,$DatabaseUsername,$DatabasePassword);
$s=mysql_select_db($DatabaseName,$con);
$keyword = $_REQUEST['str'];
if($keyword=="")
    echo "";
else
{
$grpnames=DBGet(DBQuery("select * from mail_groupmembers where group_id=$keyword")) or die(mysql_error());
if(count($grpnames))
{
    foreach($grpnames  as $k => $v)
    {
        $names[]=$v['USER_NAME'];
        
    }
    echo $values=implode(',',$names);
}
else
    echo "";
}
?>
