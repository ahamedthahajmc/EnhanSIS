<?php

function _makeNextInstitute($value,$column)
{	global $THIS_RET,$_HaniIMS;
	if($value=='0')
		return 'Retain';
	elseif($value=='-1')
		return 'Do not enroll after this institute year';
	elseif($value==$THIS_RET['INSTITUTE_ID'])
		return 'Next Grade at '.GetInstitute($value);
	else
		return GetInstitute($value);
}
function _makeCalendar($value,$column)
{	global $THIS_RET,$_HaniIMS,$calendars_RET;

	if(!$calendars_RET)
		$calendars_RET = DBGet(DBQuery('SELECT CALENDAR_ID,DEFAULT_CALENDAR,TITLE FROM institute_calendars WHERE SYEAR=\''.UserSyear().'\''),array(),array('CALENDAR_ID'));

	return $calendars_RET[$value][1]['TITLE'];
}
?>