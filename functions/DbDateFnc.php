<?php

function DBDate($type='oracle')
{
	if($type=='oracle')
		return strtoupper(date('d-M-y'));
	elseif($type=='postgres')
		return date('Y-m-d');
	elseif($type=="mysql")
		return date('Y-m-d');
}
function DaySname($value,$pattern='1')
{
	$days=array('Monday'=>'M','Tuesday'=>'T','Wednesday'=>'W','Thursday'=>'H','Friday'=>'F','Saturday'=>'S','Sunday'=>'U');
        if($pattern==1)
        return $days[$value];
        else
        return array_search($value,$days);
}
function DaySnameMod($value,$pattern='1')
{
	$days=array('Monday'=>'M','Tuesday'=>'T','Wednesday'=>'W','Thursday'=>'H','Friday'=>'F','Saturday'=>'S','Sunday'=>'U');
        if(in_array($value,$days))
        {
            if($pattern==1)
            return $days[$value];
            else
            return array_search($value,$days);
        }
        else
        {
            $val_arr=str_split($value);
            $key="";
            foreach($val_arr as $val)
            {
                if (array_search($val, $days) != false) {
                    if ($checker == true) {
                            $key .= ', ';
                    }
                    $key .= (array_search($val, $days));
                    $checker = true;
                }
            }
            //$key_arr=str_split($key);
            return $key; 
        }
}
function MonthFormatter($value,$pattern='1')
{
    
	$days=array('JAN'=>'01','FEB'=>'02','MAR'=>'03','APR'=>'04','MAY'=>'05','JUN'=>'06','JUL'=>'07','AUG'=>'08','SEP'=>'09','OCT'=>'10','NOV'=>'11','DEC'=>'12');
        if($pattern==1)
        {
        return $days[$value];
        }
        else
        {
        return array_search($value,$days);
        }
}

?>
