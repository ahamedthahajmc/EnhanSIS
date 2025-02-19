<?php


function GetSyear($date)
{	global $_HaniIMS;
		$RET = DBGet(DBQuery('SELECT SYEAR FROM attendance_calendar WHERE INSTITUTE_DATE = \''.$date.'\' '));
	return $RET[1]['SYEAR'];
}
?>