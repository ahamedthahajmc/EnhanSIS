<?php
 
include('../../RedirectModulesInc.php');
$person_RET = DBGet(DBQuery('SELECT FIRST_NAME,MIDDLE_NAME,LAST_NAME FROM people WHERE PERSON_ID=\''.$_REQUEST[person_id].'\''));
$contacts_RET = DBGet(DBQuery('SELECT TITLE,VALUE FROM people_join_contacts WHERE PERSON_ID=\''.$_REQUEST[person_id].'\''));
echo '<BR>';
PopTable('header',$person_RET[1]['FIRST_NAME'].' '.$person_RET[1]['MIDDLE_NAME'].' '.$person_RET[1]['LAST_NAME'],'width=75%');
if(count($contacts_RET))
{
	foreach($contacts_RET as $info)
		echo '<B>'.$info['TITLE'].'</B>: '.$info['VALUE'].'<BR>';
}
else
	echo 'This person has no information in the system.';
PopTable('footer');
?>