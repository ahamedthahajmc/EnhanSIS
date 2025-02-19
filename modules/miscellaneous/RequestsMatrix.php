<?php
 
include('../../RedirectModulesInc.php');
	$requests_RET = DBGet(DBQuery("SELECT concat(r.COURSE_ID, '_', r.COURSE_WEIGHT) AS CRS,
                                        r.COURSE_ID,r.COURSE_WEIGHT,cp.COURSE_PERIOD_ID,
                                        c.TITLE AS COURSE_TITLE,cp.PERIOD_ID,
                                        (cp.TOTAL_SEATS-cp.FILLED_SEATS) AS OPEN_SEATS,s.STUDENT_ID AS SCHEDULED
                                        FROM schedule_requests r,
                                        courses c,institute_periods sp,
                                        course_periods cp LEFT OUTER JOIN schedule s ON 
                                        (s.COURSE_PERIOD_ID=cp.COURSE_PERIOD_ID AND s.STUDENT_ID='".UserStudentID()."')
                                        WHERE 
                                        r.SYEAR='".UserSyear()."' AND r.INSTITUTE_ID='".UserInstitute()."'
                                        AND r.COURSE_ID=cp.COURSE_ID AND c.COURSE_ID=cp.COURSE_ID
                                        AND r.STUDENT_ID='".UserStudentID()."'
                                        AND sp.PERIOD_ID=cp.PERIOD_ID
                                        ORDER BY ".db_case(array('s.STUDENT_ID',"''","NULL",'sp.SORT_ORDER'))."
				"),array(),array('CRS','PERIOD_ID'));
	$periods_RET = DBGet(DBQuery("SELECT PERIOD_ID,SHORT_NAME FROM institute_periods WHERE SYEAR='".UserSyear()."' AND INSTITUTE_ID='".UserInstitute()."' ORDER BY SORT_ORDER"));
	echo '<CENTER><TABLE style="border: 1px solid;">';
	echo '<TR><TD></TD>';
	foreach($periods_RET as $period)
		echo '<TD><small><b>'.$period['SHORT_NAME'].'</b></small></TD>';
	foreach($requests_RET as $course=>$periods)
	{
		echo '<TR><TD><small><b>'.$periods[key($periods)][1]['COURSE_TITLE'].'</b><small></TD>';
		foreach($periods_RET as $period)
		{
			if($periods[$period['PERIOD_ID']][1]['SCHEDULED'])
                        {
				$color = '0000FF';
                        }
			elseif($periods[$period['PERIOD_ID']])
			{
				if($periods[$period['PERIOD_ID']][1]['OPEN_SEATS']==0)
					$color = 'FFFF00';
				else
					$color = '00FF00';
			}
			else
				$color = 'CCCCCC';
			echo '<TD height=10 width=6 bgcolor=#'.$color.'></TD>';
		}
		
		echo '</TR>';
	}
	echo '</TABLE></CENTER>';
?>