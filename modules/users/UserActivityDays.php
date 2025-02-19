<?php

if($_REQUEST['modfunc']=='update'){

if($_REQUEST['activity']){
$TOTAL_COUNT=DBGet(DBQuery('SELECT COUNT(ACTIVITY_DAYS) AS TOTAL_COUNT FROM system_preference_Misc'));
$TOTAL_COUNT=$TOTAL_COUNT[1]['TOTAL_COUNT'];
if($TOTAL_COUNT==0 && $_REQUEST['activity']['ACTIVITY_DAYS']){
DBQuery('INSERT INTO system_preference_Misc (ACTIVITY_DAYS) VALUES(\''.$_REQUEST['activity']['ACTIVITY_DAYS'].'\')');
}else if($TOTAL_COUNT==1){
$sql='UPDATE system_preference_Misc SET ';
foreach($_REQUEST['activity'] as $column_name=>$value)
					{
					$sql .= $column_name='\''.str_replace("\'","''",str_replace("`","''",$value)).'\',';

}
$sql= substr($sql,0,-1) .' WHERE 1=1';
DBQuery($sql);
}
}
unset($_REQUEST['activity']);
}
$activity_RET=DBGet(DBQuery('SELECT ACTIVITY_DAYS FROM system_preference_Misc LIMIT 1'));
$activity=$activity_RET[1];
echo "<FORM name=activity id=activity action=Modules.php?modname=".strip_tags(trim($_REQUEST[modname]))."&modfunc=update method=POST>";
echo '<table>';
echo '<tr><td>'._maximumInactiveDaysAllowed.':</td><td>'.TextInput($activity['ACTIVITY_DAYS'],'activity[ACTIVITY_DAYS]','','class=cell_floating').'</td></tr>';
echo '<tr><td><CENTER>'.SubmitButton(_save,'','class="btn btn-primary"').'</CENTER></td></tr>';
echo '</table>';
echo '</FORM>';
