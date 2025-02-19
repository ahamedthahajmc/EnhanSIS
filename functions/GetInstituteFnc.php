<?php

function GetInstitute($sch)
{	global $_HaniIMS;
		if(!$_HaniIMS['GetInstitute'])
	{
		$QI=DBQuery('SELECT ID,TITLE FROM institutes');
		$_HaniIMS['GetInstitute'] = DBGet($QI,array(),array('ID'));
	}

	if($_HaniIMS['GetInstitute'][$sch])
		return $_HaniIMS['GetInstitute'][$sch][1]['TITLE'];
	else
		return $sch;
}
function GetUserInstitutes($staff_id,$str=false)
{
      if(User('PROFILE_ID')!=4 && User('PROFILE')!='parent')
      {
        $str_return='';
        $institutes=DBGet(DBQuery('SELECT INSTITUTE_ID FROM staff_institute_relationship WHERE staff_id='.$staff_id.' AND syear='.  UserSyear()));
        foreach($institutes as $institute)
        {
            $return[]=$institute['INSTITUTE_ID'];
            $str_return .=$institute['INSTITUTE_ID'].',';
        }
        if($str==true)
        {
            return substr($str_return,0,-1);
        }
        else
        {
            return $return;
        }
      }
      else if (User('PROFILE_ID')==4 || User('PROFILE')=='parent')
      {
          $institutes=DBGet(DBQuery('SELECT INSTITUTE_ID FROM student_enrollment WHERE STUDENT_ID='.UserStudentID().' AND SYEAR='.UserSyear().' ORDER BY ID DESC LIMIT 0,1'));
          return $institutes[1]['INSTITUTE_ID'];
      }
}

function GetInstituteInfo($sch)
{	global $_HaniIMS;
		if(!$_HaniIMS['GetInstituteInfo'])
	{
		$QI=DBQuery('SELECT * FROM institutes');
		$_HaniIMS['GetInstituteInfo'] = DBGet($QI,array(),array('ID'));
	}
	if($_HaniIMS['GetInstituteInfo'][$sch])
		return 'Address :'.$_HaniIMS['GetInstituteInfo'][$sch][1]['ADDRESS'].','.$_HaniIMS['GetInstituteInfo'][$sch][1]['CITY'].','.$_HaniIMS['GetInstituteInfo'][$sch][1]['STATE'].','.$_HaniIMS['GetInstituteInfo'][$sch][1]['ZIPCODE']. ($_HaniIMS['GetInstituteInfo'][$sch][1]['PHONE']!=NULL ? ' <p> Phone :'.$_HaniIMS['GetInstituteInfo'][$sch][1]['PHONE'].'</p>' : '');
                 
	else
		return $sch;
}


?>
