<?php

function _makeLetterGrade($percent,$course_period_id=0,$staff_id=0,$ret='')
{	
    global $programconfig,$_HaniIMS;
	if(!$course_period_id)
		$course_period_id = UserCoursePeriod();

	if(!$staff_id)
		$staff_id = User('STAFF_ID');

	if(!$programconfig[$staff_id])
	{
		$config_RET = DBGet(DBQuery('SELECT TITLE,VALUE FROM program_user_config WHERE USER_ID=\''.$staff_id.'\' AND PROGRAM=\'Gradebook\' AND VALUE LIKE \'%_'.$course_period_id.'\''),array(),array('TITLE'));
		if(count($config_RET))
			foreach($config_RET as $title=>$value)
                        {
                                $unused_var=explode('_',$value[1]['VALUE']);
                                $programconfig[$staff_id][$title] =$unused_var[0];
                                $programconfig[$staff_id]['current_course_period_id'] =$course_period_id;
//				$programconfig[$staff_id][$title] = rtrim($value[1]['VALUE'],'_'.$course_period_id);
                        }
		else
			$programconfig[$staff_id] = true;
	}
        else
        {
           if($programconfig[$staff_id]['current_course_period_id']!=$course_period_id)
           {
               $programconfig[$staff_id]=array();
               $config_RET = DBGet(DBQuery('SELECT TITLE,VALUE FROM program_user_config WHERE USER_ID=\''.$staff_id.'\' AND PROGRAM=\'Gradebook\' AND VALUE LIKE \'%_'.$course_period_id.'\''),array(),array('TITLE'));
		if(count($config_RET))
			foreach($config_RET as $title=>$value)
                        {
                                $unused_var=explode('_',$value[1]['VALUE']);
                                $programconfig[$staff_id][$title] =$unused_var[0];
                                $programconfig[$staff_id]['current_course_period_id'] =$course_period_id;
//				$programconfig[$staff_id][$title] = rtrim($value[1]['VALUE'],'_'.$course_period_id);
                        }
		else
			$programconfig[$staff_id] = true;
               
           }
        }
	if(!$_HaniIMS['_makeLetterGrade']['courses'][$course_period_id])
		$_HaniIMS['_makeLetterGrade']['courses'][$course_period_id] = DBGet(DBQuery('SELECT DOES_BREAKOFF,GRADE_SCALE_ID FROM course_periods WHERE COURSE_PERIOD_ID=\''.$course_period_id.'\''));
	$does_breakoff = $_HaniIMS['_makeLetterGrade']['courses'][$course_period_id][1]['DOES_BREAKOFF'];
	$grade_scale_id = $_HaniIMS['_makeLetterGrade']['courses'][$course_period_id][1]['GRADE_SCALE_ID'];

	$percent = is_numeric($percent) ? $percent * 100 : 0;
       
		if($programconfig[$staff_id]['ROUNDING']=='UP')
                {
			$percent = ceil($percent);
                }
		elseif($programconfig[$staff_id]['ROUNDING']=='DOWN')
                {
			$percent = floor($percent);
                }
		elseif($programconfig[$staff_id]['ROUNDING']=='NORMAL')
                {
			$percent = round($percent,0);
                }
                else
                {
		$percent = round($percent,2); // institute default
                }
	if($ret=='%')
		return $percent;

	if(!$_HaniIMS['_makeLetterGrade']['grades'][$grade_scale_id])
		$_HaniIMS['_makeLetterGrade']['grades'][$grade_scale_id] = DBGet(DBQuery('SELECT TITLE,ID,BREAK_OFF FROM report_card_grades WHERE SYEAR=\''.UserSyear().'\' AND INSTITUTE_ID=\''.UserInstitute().'\' AND GRADE_SCALE_ID=\''.$grade_scale_id.'\' ORDER BY BREAK_OFF IS NOT NULL DESC,BREAK_OFF DESC,SORT_ORDER'));
	
	foreach($_HaniIMS['_makeLetterGrade']['grades'][$grade_scale_id] as $grade)
	{
            
		if($does_breakoff=='Y' ? $percent>=$programconfig[$staff_id][$course_period_id.'-'.$grade['ID']] && is_numeric($programconfig[$staff_id][$course_period_id.'-'.$grade['ID']]) : $percent>=$grade['BREAK_OFF'])
			return $ret=='ID' ? $grade['ID'] : $grade['TITLE'];
	}
}
?>
