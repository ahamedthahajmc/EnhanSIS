<?php


function VerifyDate_sort($date)
{
    if (strpos(strip_tags($date), '/') !== false) 
    {
    	$vdate = explode("/", $date);
    	if(count($vdate))
    	{
            $month = MonthNWSwitch($vdate[0],'tonum');
            $day = $vdate[1];
            $year = $vdate[2];
    	}
    	else
        {
            return false;
        }
    }
    else
        return false;

	return checkdate($month,$day,$year);
}

function date_to_timestamp($date)
{
    $newarr=array();
    foreach ($date as $dt)
    {
        $arr_date=explode('/', $dt);
        $month=MonthNWSwitch($arr_date[0],'tonum');
        $day = $arr_date[1];
        $year = $arr_date[2];
        array_push($newarr, mktime(0,0,0,$month,$day,$year));
    }
    return $newarr;
}

function point_to_number($point)
{
    $newarr=array();
    foreach ($point as $value)
    {
        $value=strip_tags($value);
        $rank_arr=explode(' / ', $value);

        array_push($newarr,$rank_arr[0]);
    }
    return $newarr;
}

function percent_to_number($percent)
{
    $newarr=array();
    foreach ($percent as $value)
    {
        $value=strip_tags($value);
        $rank_arr=explode('%', $value);

        array_push($newarr,$rank_arr[0]);
    }
    return $newarr;
}

function range_to_number($range)
{
    $newarr=array();
    foreach ($range as $value)
    {
        $rank_arr=explode(' - ', $value);

        array_push($newarr,$rank_arr[0]);
    }
    return $newarr;
}

function rank_to_number($rank)
{
    $newarr=array();
    foreach ($rank as $value)
    {
        $rank_arr=explode(' out of ', $value);

        array_push($newarr,$rank_arr[0]);
    }
    return $newarr;
}
?>
