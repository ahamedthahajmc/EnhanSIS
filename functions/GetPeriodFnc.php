<?php

function GetPeriod($period_id,$title='')
{	global $_HaniIMS;
	if(!$_HaniIMS['GetPeriod'])
	{
		$sql = 'SELECT TITLE, PERIOD_ID FROM institute_periods WHERE SYEAR=\''.UserSyear().'\'';
		$_HaniIMS['GetPeriod'] = DBGet(DBQuery($sql),array(),array('PERIOD_ID'));
	}
	
	return $_HaniIMS['GetPeriod'][$period_id][1]['TITLE'];
}
function GetCpDet($cp_id,$key)
{	
    if($key!='' && $cp_id!='')
    {
    $get_det=DBGet(DBQuery('SELECT '.strtoupper($key).' FROM course_periods WHERE COURSE_PERIOD_ID='.$cp_id));
    }
    return $get_det[1][strtoupper($key)];
}

?>
