<?php

function GetTeacher($teacher_id,$title='',$column='FULL_NAME',$institutes=true)
{	global $_HaniIMS;
		if(!$_HaniIMS['GetTeacher'])
	{

                $QI=DBQuery('SELECT STAFF_ID,CONCAT(LAST_NAME,\', \',FIRST_NAME) AS FULL_NAME,USERNAME,PROFILE FROM staff s INNER JOIN staff_institute_relationship USING(staff_id),login_authentication la WHERE s.STAFF_ID=la.USER_ID AND s.PROFILE=\'teacher\' AND syear='.  UserSyear());
		$_HaniIMS['GetTeacher'] = DBGet($QI,array(),array('STAFF_ID'));
	}
		return $_HaniIMS['GetTeacher'][$teacher_id][1][$column];
}
?>
