<?php

function GetAllMP($mp,$marking_period_id='0')
{	global $_HaniIMS;
	if($marking_period_id==0)
	{
		// there should be exactly one fy marking period
		$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_years WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
		$marking_period_id = $RET[1]['MARKING_PERIOD_ID'];
		$mp = 'FY';
	}
	elseif(!$mp) 
		 $mp = GetMPTable(GetMP($marking_period_id,'TABLE'));
             
	if(!$_HaniIMS['GetAllMP'][$mp])
	{
		switch($mp)
		{
			case 'PRO':
				// there should be exactly one fy marking period
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_years WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
				$fy = $RET[1]['MARKING_PERIOD_ID'];

				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,SEMESTER_ID FROM institute_quarters WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
				foreach($RET as $value)
				{
					$_HaniIMS['GetAllMP'][$mp][$value['MARKING_PERIOD_ID']] = "'$fy','$value[SEMESTER_ID]','$value[MARKING_PERIOD_ID]'";
					$_HaniIMS['GetAllMP'][$mp][$value['MARKING_PERIOD_ID']] .= ','.GetChildrenMP($mp,$value['MARKING_PERIOD_ID']);
					if(substr($_HaniIMS['GetAllMP'][$mp][$value['MARKING_PERIOD_ID']],-1)==',')
						$_HaniIMS['GetAllMP'][$mp][$value['MARKING_PERIOD_ID']] = substr($_HaniIMS['GetAllMP'][$mp][$value['MARKING_PERIOD_ID']],0,-1);
				}
			break;

			case 'QTR':
				// there should be exactly one fy marking period
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_years WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
				$fy = $RET[1]['MARKING_PERIOD_ID'];

				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,SEMESTER_ID FROM institute_quarters WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
				foreach($RET as $value)
					$_HaniIMS['GetAllMP'][$mp][$value['MARKING_PERIOD_ID']] = "'$fy','$value[SEMESTER_ID]','$value[MARKING_PERIOD_ID]'";
			break;

			case 'SEM':
				// there should be exactly one fy marking period
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_years WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
				$fy = $RET[1]['MARKING_PERIOD_ID'];

				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,SEMESTER_ID FROM institute_quarters WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''),array(),array('SEMESTER_ID'));
				foreach($RET as $sem=>$value)
				{
					$_HaniIMS['GetAllMP'][$mp][$sem] = "'$fy','$sem'";
					foreach($value as $qtr)
						$_HaniIMS['GetAllMP'][$mp][$sem] .= ",'$qtr[MARKING_PERIOD_ID]'";
				}
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_semesters s WHERE NOT EXISTS (SELECT \'\' FROM institute_quarters q WHERE q.SEMESTER_ID=s.MARKING_PERIOD_ID) AND SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
				foreach($RET as $value)
					$_HaniIMS['GetAllMP'][$mp][$value['MARKING_PERIOD_ID']] = "'$fy','$value[MARKING_PERIOD_ID]'";
			break;

			case 'FY':
				// there should be exactly one fy marking period which better be $marking_period_id
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,SEMESTER_ID FROM institute_quarters WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''),array(),array('SEMESTER_ID'));
				$_HaniIMS['GetAllMP'][$mp][$marking_period_id] = "'$marking_period_id'";
				foreach($RET as $sem=>$value)
				{
					$_HaniIMS['GetAllMP'][$mp][$marking_period_id] .= ",'$sem'";
					foreach($value as $qtr)
						$_HaniIMS['GetAllMP'][$mp][$marking_period_id] .= ",'$qtr[MARKING_PERIOD_ID]'";
				}
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_semesters s WHERE NOT EXISTS (SELECT \'\' FROM institute_quarters q WHERE q.SEMESTER_ID=s.MARKING_PERIOD_ID) AND SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
				foreach($RET as $value)
					$_HaniIMS['GetAllMP'][$mp][$marking_period_id] .= ",'$value[MARKING_PERIOD_ID]'";
			break;
                        
		}
	}

	return $_HaniIMS['GetAllMP'][$mp][$marking_period_id];
}

function GetAllMP_Mod($mp,$marking_period_id='0')
{	global $_HaniIMS;

	if($marking_period_id==0)
	{
		// there should be exactly one fy marking period
		$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_years WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
		$marking_period_id = $RET[1]['MARKING_PERIOD_ID'];
		$mp = 'FY';
	}
	elseif(!$mp) 
		 $mp = GetMPTable(GetMP($marking_period_id,'TABLE'));
        
     
	if(!$_HaniIMS['GetAllMP'][$mp])
	{
		switch($mp)
		{
			case 'PRO':
				// there should be exactly one fy marking period
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_years WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
				$fy = $RET[1]['MARKING_PERIOD_ID'];

				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,SEMESTER_ID FROM institute_quarters WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
				foreach($RET as $value)
				{
					$_HaniIMS['GetAllMP'][$mp][$value['MARKING_PERIOD_ID']] = "'$fy','$value[SEMESTER_ID]','$value[MARKING_PERIOD_ID]'";
					$_HaniIMS['GetAllMP'][$mp][$value['MARKING_PERIOD_ID']] .= ','.GetChildrenMP($mp,$value['MARKING_PERIOD_ID']);
					if(substr($_HaniIMS['GetAllMP'][$mp][$value['MARKING_PERIOD_ID']],-1)==',')
						$_HaniIMS['GetAllMP'][$mp][$value['MARKING_PERIOD_ID']] = substr($_HaniIMS['GetAllMP'][$mp][$value['MARKING_PERIOD_ID']],0,-1);
				}
			break;

			case 'QTR':
				// there should be exactly one fy marking period
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_years WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
				$fy = $RET[1]['MARKING_PERIOD_ID'];

				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,SEMESTER_ID FROM institute_quarters WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
				foreach($RET as $value)
					$_HaniIMS['GetAllMP'][$mp][$value['MARKING_PERIOD_ID']] = "'$fy','$value[SEMESTER_ID]','$value[MARKING_PERIOD_ID]'";
			break;

			case 'SEM':
				// there should be exactly one fy marking period
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_years WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
				$fy = $RET[1]['MARKING_PERIOD_ID'];

				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,SEMESTER_ID FROM institute_quarters WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''),array(),array('SEMESTER_ID'));
				foreach($RET as $sem=>$value)
				{
					$_HaniIMS['GetAllMP'][$mp][$sem] = "'$fy','$sem'";

				}
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_semesters s WHERE NOT EXISTS (SELECT \'\' FROM institute_quarters q WHERE q.SEMESTER_ID=s.MARKING_PERIOD_ID) AND SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
				foreach($RET as $value)
					$_HaniIMS['GetAllMP'][$mp][$value['MARKING_PERIOD_ID']] = "'$fy','$value[MARKING_PERIOD_ID]'";
			break;

			case 'FY':
				// there should be exactly one fy marking period which better be $marking_period_id
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,SEMESTER_ID FROM institute_quarters WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''),array(),array('SEMESTER_ID'));
				$_HaniIMS['GetAllMP'][$mp][$marking_period_id] = "'$marking_period_id'";
				foreach($RET as $sem=>$value)
				{
//					$_HaniIMS['GetAllMP'][$mp][$marking_period_id] .= ",'$sem'";
//					foreach($value as $qtr)
//						$_HaniIMS['GetAllMP'][$mp][$marking_period_id] .= ",'$qtr[MARKING_PERIOD_ID]'";
				}
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID FROM institute_semesters s WHERE NOT EXISTS (SELECT \'\' FROM institute_quarters q WHERE q.SEMESTER_ID=s.MARKING_PERIOD_ID) AND SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''));
//				foreach($RET as $value)
//					$_HaniIMS['GetAllMP'][$mp][$marking_period_id] .= ",'$value[MARKING_PERIOD_ID]'";
			break;
                        
		}
	}

	return $_HaniIMS['GetAllMP'][$mp][$marking_period_id];
}
function GetParentMP($mp,$marking_period_id='0')
{	global $_HaniIMS;

	if(!$_HaniIMS['GetParentMP'][$mp])
	{
		switch($mp)
		{
			case 'QTR':

			break;

			case 'SEM':
				$_HaniIMS['GetParentMP'][$mp] = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,SEMESTER_ID AS PARENT_ID FROM institute_quarters WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''),array(),array('MARKING_PERIOD_ID'));
			break;

			case 'FY':
				$_HaniIMS['GetParentMP'][$mp] = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,YEAR_ID AS PARENT_ID FROM institute_semesters WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''),array(),array('MARKING_PERIOD_ID'));
			break;
		}
	}

	return $_HaniIMS['GetParentMP'][$mp][$marking_period_id][1]['PARENT_ID'];
}

function GetChildrenMP($mp,$marking_period_id='0')
{	global $_HaniIMS;

	switch($mp)
	{
		case 'FY':
			if(!$_HaniIMS['GetChildrenMP']['FY'])
			{
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,SEMESTER_ID FROM institute_quarters WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''),array(),array('SEMESTER_ID'));
				foreach($RET as $sem=>$value)
				{
					$_HaniIMS['GetChildrenMP'][$mp]['0'] .= ",'$sem'";
					foreach($value as $qtr)
						$_HaniIMS['GetChildrenMP'][$mp]['0'] .= ",'$qtr[MARKING_PERIOD_ID]'";
				}
				$_HaniIMS['GetChildrenMP'][$mp]['0'] = substr($_HaniIMS['GetChildrenMP'][$mp]['0'],1);
			}
			return $_HaniIMS['GetChildrenMP'][$mp]['0'];
		break;

		case 'SEM':
			if(GetMP($marking_period_id,'TABLE')=='institute_quarters')
				$marking_period_id = GetParentMP('SEM',$marking_period_id);
			if(!$_HaniIMS['GetChildrenMP']['SEM'])
			{
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,SEMESTER_ID FROM institute_quarters WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''),array(),array('SEMESTER_ID'));
				foreach($RET as $sem=>$value)
				{
					foreach($value as $qtr)
						$_HaniIMS['GetChildrenMP'][$mp][$sem] .= ",'$qtr[MARKING_PERIOD_ID]'";
					$_HaniIMS['GetChildrenMP'][$mp][$sem] = substr($_HaniIMS['GetChildrenMP'][$mp][$sem],1);
				}
			}
			return $_HaniIMS['GetChildrenMP'][$mp][$marking_period_id];
		break;

		case 'QTR':
			return "".$marking_period_id."";
		break;

		case 'PRO':
			if(!$_HaniIMS['GetChildrenMP']['PRO'])
			{
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,QUARTER_ID FROM institute_progress_periods WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''),array(),array('QUARTER_ID'));
				foreach($RET as $qtr=>$value)
				{
					foreach($value as $pro)
						$_HaniIMS['GetChildrenMP'][$mp][$qtr] .= ",'$pro[MARKING_PERIOD_ID]'";
					$_HaniIMS['GetChildrenMP'][$mp][$qtr] = substr($_HaniIMS['GetChildrenMP'][$mp][$qtr],1);
				}
			}
			return $_HaniIMS['GetChildrenMP'][$mp][$marking_period_id];
		break;
	}
}
function GetMPChildren($mp,$marking_period_id='0')
{	global $_HaniIMS;

	switch($mp)
	{
		case 'year':
			if(!$_HaniIMS['GetChildrenMP']['FY'])
			{
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,SEMESTER_ID FROM institute_quarters WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''),array(),array('SEMESTER_ID'));
				foreach($RET as $sem=>$value)
				{
					$_HaniIMS['GetChildrenMP'][$mp]['0'] .= ",'$sem'";
					foreach($value as $qtr)
						$_HaniIMS['GetChildrenMP'][$mp]['0'] .= ",'$qtr[MARKING_PERIOD_ID]'";
				}
				$_HaniIMS['GetChildrenMP'][$mp]['0'] = substr($_HaniIMS['GetChildrenMP'][$mp]['0'],1);
                                if($_HaniIMS['GetChildrenMP'][$mp]['0']!='')
                                    $_HaniIMS['GetChildrenMP'][$mp]['0']=$_HaniIMS['GetChildrenMP'][$mp]['0'].','.$marking_period_id;
                                else
                                    $_HaniIMS['GetChildrenMP'][$mp]['0']=$marking_period_id;
			}
			return $_HaniIMS['GetChildrenMP'][$mp]['0'];
		break;

		case 'semester':
			if(GetMP($marking_period_id,'TABLE')=='institute_quarters')
				$marking_period_id = GetParentMP('SEM',$marking_period_id);
			if(!$_HaniIMS['GetChildrenMP']['SEM'])
			{
				$RET = DBGet(DBQuery('SELECT MARKING_PERIOD_ID,SEMESTER_ID FROM institute_quarters WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\''),array(),array('SEMESTER_ID'));
				foreach($RET as $sem=>$value)
				{
					foreach($value as $qtr)
						$_HaniIMS['GetChildrenMP'][$mp][$sem] .= ",'$qtr[MARKING_PERIOD_ID]'";
					$_HaniIMS['GetChildrenMP'][$mp][$sem] = substr($_HaniIMS['GetChildrenMP'][$mp][$sem],1);
				}
                                if($_HaniIMS['GetChildrenMP'][$mp][$marking_period_id]!='')
                                    $_HaniIMS['GetChildrenMP'][$mp][$marking_period_id]=$_HaniIMS['GetChildrenMP'][$mp][$marking_period_id].','.$marking_period_id;
                                else
                                    $_HaniIMS['GetChildrenMP'][$mp][$marking_period_id]=$marking_period_id;
			}
                        
			return $_HaniIMS['GetChildrenMP'][$mp][$marking_period_id];
		break;

		case 'quarter':
			return "".$marking_period_id."";
		break;

        }
}
?>
