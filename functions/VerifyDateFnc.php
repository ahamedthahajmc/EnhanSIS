<?php


function VerifyDate($date)
{
	$vdate = explode("-", $date);
	if (count($vdate)) {
		$day = $vdate[0];
		$month = MonthNWSwitch($vdate[1], 'tonum');
		$year = $vdate[2];
		$e_date = '01-' . $month . '-' . $year;
		$num_days = date('t', strtotime($e_date));
		if ($num_days < $day) {
			return false;
		}
	} else {
		return false;
	}

	// in the < 8 php if you pass a string value for the int argument but string value can be converted in int that will not give you an error but in php 8 you have to pass int type value for int argument
	// note - default variable type in php is " string " type 
	return checkdate(intval($month), intval($day), intval($year));
}
