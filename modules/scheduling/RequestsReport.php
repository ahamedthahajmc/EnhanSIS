<?php
 
include('../../RedirectModulesInc.php');
include('lang/language.php');

	$count_RET = DBGet(DBQuery('SELECT cs.TITLE as SUBJECT_TITLE,c.TITLE as COURSE_TITLE,sr.COURSE_ID,COUNT(*) AS COUNT,(SELECT (sum(TOTAL_SEATS)-sum(filled_seats)) AS SEATS FROM course_periods cp,course_period_var cpv WHERE IF(sr.COURSE_ID IS NOT NULL AND sr.COURSE_ID<>0,cp.COURSE_ID=sr.COURSE_ID,cp.COURSE_ID) AND cp.COURSE_PERIOD_ID=cpv.COURSE_PERIOD_ID AND IF(sr.WITH_PERIOD_ID IS NOT NULL AND sr.WITH_PERIOD_ID<>0,cpv.PERIOD_ID =sr.WITH_PERIOD_ID,cpv.PERIOD_ID) ) AS SEATS FROM schedule_requests sr,courses c,course_subjects cs WHERE cs.SUBJECT_ID=c.SUBJECT_ID AND sr.COURSE_ID=c.COURSE_ID AND sr.SYEAR=\''.UserSyear().'\' AND sr.INSTITUTE_ID=\''.UserInstitute().'\' AND sr.MARKING_PERIOD_ID=\''.UserMP().'\'  GROUP BY sr.COURSE_ID,cs.TITLE,c.TITLE'),array(),array('SUBJECT_TITLE'));
        $columns = array('SUBJECT_TITLE'=>_subject,'COURSE_TITLE'=>_course,'COUNT'=>_numberOfRequests,'SEATS'=>_seats);
	
	DrawBC(""._scheduling." > ".ProgramTitle());
        echo '<div class="panel panel-default">';
	ListOutput($count_RET,$columns,  _request, _requests,array(),array(array('SUBJECT_TITLE')));
        echo '</div>';
?>