<?php



include 'RedirectRootInc.php';
include 'Warehouse.php';
include 'Data.php';

$table_name = sqlSecurityFilter($_REQUEST['table_name']);
$field_name = sqlSecurityFilter($_REQUEST['field_name']);
$val = sqlSecurityFilter($_REQUEST['val']);
$id = sqlSecurityFilter($_REQUEST['id']);
$msg = sqlSecurityFilter($_REQUEST['msg']);
$field_id = sqlSecurityFilter($_REQUEST['field_id']);

if(isset($table_name) && isset($field_name) && isset($val) && isset($field_id) && isset($msg))
{
   $check_query=DBGet(DBQuery('SELECT COUNT(*) as REC_EXISTS FROM '.$table_name.' WHERE UPPER('.$field_name.')=UPPER(\''.singleQuoteReplace('','',trim($val)).'\') AND ID <>\''.$id.'\' '));

   echo $check_query[1]['REC_EXISTS'].'_'.$field_id.'_'.$msg;
}

?>