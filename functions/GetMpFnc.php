<?php

function GetMP($mp='',$column='TITLE')
{	global $_HaniIMS;
	// mab - need to translate marking_period_id to title to be useful as a function call from dbget
	// also, it doesn't make sense to ask for same thing you give
	if($column=='MARKING_PERIOD_ID')
		$column='TITLE';

	if(!$_HaniIMS['GetMP'])
	{
           
		$_HaniIMS['GetMP'] = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,TITLE,POST_START_DATE,POST_END_DATE,\'institute_quarters\' AS `TABLE`,\'SEMESTER_ID\' AS `PA_ID`,SORT_ORDER,SHORT_NAME,START_DATE,END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS FROM institute_quarters         WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'
					UNION      SELECT MARKING_PERIOD_ID,TITLE,POST_START_DATE,POST_END_DATE,\'institute_semesters\' AS `TABLE`,\'YEAR_ID\' AS `PA_ID`,SORT_ORDER,SHORT_NAME,START_DATE,END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS FROM institute_semesters        WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'
					UNION      SELECT MARKING_PERIOD_ID,TITLE,POST_START_DATE,POST_END_DATE,\'institute_years\' AS `TABLE`, \'-1\' AS `PA_ID`,SORT_ORDER,SHORT_NAME,START_DATE,END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS FROM institute_years            WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'
					UNION      SELECT MARKING_PERIOD_ID,TITLE,POST_START_DATE,POST_END_DATE,\'institute_progress_periods\' AS `TABLE`, \'-1\' AS `PA_ID`,SORT_ORDER,SHORT_NAME,START_DATE,END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS FROM institute_progress_periods WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''),array(),array('MARKING_PERIOD_ID'));

        }
        
	if(substr($mp,0,1)=='E')
	{
		if($column=='TITLE' || $column=='SHORT_NAME')
			$suffix = ' Exam';
		$mp = substr($mp,1);
	}
if($mp=='')
{
    return 'Custom';
}
 else {
   
  if($mp==0 && $column=='TITLE')
      
		return 'Full Year'.$suffix;
	else
        {
		return $_HaniIMS['GetMP'][$mp][1][$column].$suffix;  
        }
}
	
}

function GetMPAllInstitute($mp,$column='TITLE')
{	global $_HaniIMS;

	// mab - need to translate marking_period_id to title to be useful as a function call from dbget
	// also, it doesn't make sense to ask for same thing you give
	if($column=='MARKING_PERIOD_ID')
		$column='TITLE';

	if(!$_HaniIMS['GetMP'])
	{
		$_HaniIMS['GetMP'] = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,TITLE,POST_START_DATE,POST_END_DATE,\'institute_quarters\'        AS `TABLE`,SORT_ORDER,SHORT_NAME,START_DATE,END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS FROM institute_quarters         WHERE SYEAR=\''.UserSyear().'\' 
					UNION      SELECT MARKING_PERIOD_ID,TITLE,POST_START_DATE,POST_END_DATE,\'institute_semesters\'       AS `TABLE`,SORT_ORDER,SHORT_NAME,START_DATE,END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS FROM institute_semesters        WHERE SYEAR=\''.UserSyear().'\' 
					UNION      SELECT MARKING_PERIOD_ID,TITLE,POST_START_DATE,POST_END_DATE,\'institute_years\'           AS `TABLE`,SORT_ORDER,SHORT_NAME,START_DATE,END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS FROM institute_years            WHERE SYEAR=\''.UserSyear().'\' 
					UNION      SELECT MARKING_PERIOD_ID,TITLE,POST_START_DATE,POST_END_DATE,\'institute_progress_periods\' AS `TABLE`,SORT_ORDER,SHORT_NAME,START_DATE,END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS FROM institute_progress_periods WHERE SYEAR=\''.UserSyear().'\''),array(),array('MARKING_PERIOD_ID'));
	}
	if(substr($mp,0,1)=='E')
	{
		if($column=='TITLE' || $column=='SHORT_NAME')
			$suffix = ' Exam';
		$mp = substr($mp,1);
	}
        
	if($mp==0 && $column=='TITLE')
		return 'Full Year'.$suffix;
	else
		return $_HaniIMS['GetMP'][$mp][1][$column].$suffix;
}
function GetMP_teacherschedule($mp,$column='TITLE')
{	global $_HaniIMS;

	// mab - need to translate marking_period_id to title to be useful as a function call from dbget
	// also, it doesn't make sense to ask for same thing you give
	if($column=='MARKING_PERIOD_ID')
		$column='TITLE';

	if(!$_HaniIMS['GetMP'])
	{
		$_HaniIMS['GetMP'] = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,TITLE,POST_START_DATE,POST_END_DATE,\'institute_quarters\'        AS `TABLE`,SORT_ORDER,SHORT_NAME,START_DATE,END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS FROM institute_quarters         WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'
					UNION      SELECT MARKING_PERIOD_ID,TITLE,POST_START_DATE,POST_END_DATE,\'institute_semesters\'       AS `TABLE`,SORT_ORDER,SHORT_NAME,START_DATE,END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS FROM institute_semesters        WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'
					UNION      SELECT MARKING_PERIOD_ID,TITLE,POST_START_DATE,POST_END_DATE,\'institute_years\'           AS `TABLE`,SORT_ORDER,SHORT_NAME,START_DATE,END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS FROM institute_years            WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\'
					UNION      SELECT MARKING_PERIOD_ID,TITLE,POST_START_DATE,POST_END_DATE,\'institute_progress_periods\' AS `TABLE`,SORT_ORDER,SHORT_NAME,START_DATE,END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS FROM institute_progress_periods WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''),array(),array('MARKING_PERIOD_ID'));
	}
	if(substr($mp,0,1)=='E')
	{
            
		if($column=='TITLE' || $column=='SHORT_NAME')
			$suffix = ' Exam';
		$mp = substr($mp,1);
	}
        if($mp=='Custom' && $column=='TITLE')
        {
            return $mp;
        }
	if($mp==0 && $column=='TITLE')
        {
		return 'Full Year'.$suffix;
        }
        
	else  
		return $_HaniIMS['GetMP'][$mp][1][$column].$suffix;

}
function GetMPTable($mp_table)
{
	switch($mp_table)
	{
		case 'institute_years':
			return 'FY';
		break;
		case 'institute_semesters':
			return 'SEM';
		break;
		case 'institute_quarters':
			return 'QTR';
		break;
		case 'institute_progress_periods':
			return 'PRO';
		break;
		default:
			return 'FY';
		break;
	}
}
function GetCurrentMP($mp,$date,$error=true)
{	global $_HaniIMS;

	switch($mp)
	{
		case 'FY':
			$table = 'institute_years';
		break;

		case 'SEM':
			$table = 'institute_semesters';
		break;

		case 'QTR':
			$table = 'institute_quarters';
		break;

		case 'PRO':
			$table = 'institute_progress_periods';
		break;
	}

	if(!$_HaniIMS['GetCurrentMP'][$date][$mp])
	 	$_HaniIMS['GetCurrentMP'][$date][$mp] = DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM '.$table.' WHERE \''.$date.'\' BETWEEN START_DATE AND END_DATE AND SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));

	if($_HaniIMS['GetCurrentMP'][$date][$mp][1]['MARKING_PERIOD_ID'])
		return $_HaniIMS['GetCurrentMP'][$date][$mp][1]['MARKING_PERIOD_ID'];
	elseif(strpos($_SERVER['PHP_SELF'],'Side.php')===false && $error==true)
		ErrorMessage(array(_youAreNotCurrentlyInAMarkingPeriod));
		
}
function GetMPId($mp)
{	

	switch($mp)
	{
		case 'FY':
			$table = 'institute_years';
		break;

		case 'SEM':
			$table = 'institute_semesters';
		break;

		case 'QTR':
			$table = 'institute_quarters';
		break;

		case 'PRO':
			$table = 'institute_progress_periods';
		break;
	}

	$get_mp_id=DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM '.$table.' WHERE INSTITUTE_ID='.UserInstitute().' AND SYEAR='.UserSyear()));
        if($get_mp_id[1]['MARKING_PERIOD_ID']!='')
            return $get_mp_id[1]['MARKING_PERIOD_ID'];
        else
           return UserMP ();
}

function check_exam($mp)
{
    $qr=  DBGet(DBQuery('select * from marking_periods where marking_period_id=\''.$mp.'\''));

    return $qr[1]['DOES_EXAM'];
}
function ParentMP($mp)
{
     $qr=  DBGet(DBQuery('select * from marking_periods where marking_period_id=\''.$mp.'\''));

    return $qr[1]['PARENT_ID'];
}
?>