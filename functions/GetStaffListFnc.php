<?php

function GetStaffList(&$extra)
{
	global $profiles_RET;
	$functions = array('PROFILE' => 'makeProfile');
	switch (User('PROFILE')) {
		case 'admin':
			$profiles_RET = DBGet(DBQuery('SELECT * FROM user_profiles'), array(), array('ID'));
			$sql = 'SELECT DISTINCT CONCAT(TRIM(s.LAST_NAME),\', \',s.FIRST_NAME,\' \',COALESCE(s.MIDDLE_NAME,\' \')) AS FULL_NAME,
                        la.USERNAME,s.PROFILE,s.IS_DISABLE,s.PROFILE_ID,s.IS_DISABLE,s.STAFF_ID ' . $extra['SELECT'] . '
                        FROM
                        people s ' . $extra['FROM'] . ',login_authentication la,students st,student_enrollment ssm
                        WHERE
                        st.STUDENT_ID=ssm.STUDENT_ID AND ssm.SYEAR=' . UserSyear() . '  AND s.PROFILE IS NOT NULL AND s.PROFILE_ID in (SELECT ID FROM user_profiles WHERE PROFILE=\'parent\') AND s.STAFF_ID=la.USER_ID AND la.PROFILE_ID in (SELECT ID FROM user_profiles WHERE PROFILE=\'parent\')';
			if ($_REQUEST['_search_all_institutes'] != 'Y')
				$sql .= ' AND ssm.INSTITUTE_ID=' . UserInstitute() . ' AND s.STAFF_ID IN (SELECT PERSON_ID FROM students_join_people sjp WHERE ssm.STUDENT_ID = sjp.STUDENT_ID
AND ssm.INSTITUTE_ID=' . UserInstitute() . ' AND ssm.SYEAR=' . UserSyear() . ' AND (ssm.end_date is NULL or ssm.end_date>="' . DBDate('mysql') . '")) ';
			else
				$sql .= ' AND ssm.INSTITUTE_ID IN(' .  GetUserInstitutes(UserID(), true) . ')  AND s.STAFF_ID IN (SELECT PERSON_ID FROM students_join_people sjp WHERE ssm.STUDENT_ID = sjp.STUDENT_ID
AND ssm.INSTITUTE_ID IN (' .  GetUserInstitutes(UserID(), true) . ') AND ssm.SYEAR=' . UserSyear() . ' AND (ssm.end_date is NULL or ssm.end_date>="' . DBDate('mysql') . '"))';
			if ($_REQUEST['_dis_user'] != 'Y')
				$sql .= ' AND (s.IS_DISABLE<>\'Y\' OR  s.IS_DISABLE IS NULL)';
			if ($_REQUEST['username'])
				$sql .= 'AND UPPER(la.USERNAME) LIKE \'' . singleQuoteReplace("'", "\'", strtoupper($_REQUEST['username'])) . '%\' ';
			if ($_REQUEST['last'])
				$sql .= 'AND UPPER(s.LAST_NAME) LIKE \'' . singleQuoteReplace("'", "\'", strtoupper($_REQUEST['last'])) . '%\' ';
			if ($_REQUEST['first'])
				$sql .= 'AND UPPER(s.FIRST_NAME) LIKE \'' . singleQuoteReplace("'", "\'", strtoupper($_REQUEST['first'])) . '%\' ';
			if ($_REQUEST['profile']) {
				if (is_number($_REQUEST['profile']) == FALSE)
					$sql .= ' AND s.PROFILE=\'' . $_REQUEST['profile'] . '\' AND s.PROFILE_ID IS NULL ';
				else
					$sql .= ' AND s.PROFILE_ID=\'' . $_REQUEST['profile'] . '\' ';
			}
			$sql .= $extra['WHERE'] . ' ';
			if ($extra['GROUP'])
				$sql .= ' GROUP BY ' . $extra['GROUP'];
			$sql .= 'ORDER BY FULL_NAME';
			/**************************************for Back to User*************************************************************/
			if ($_SESSION['staf_search']['sql'] && $_REQUEST['return_session']) {
				$sql = $_SESSION['staf_search']['sql'];
			} else {
				if ($_REQUEST['sql_save_session_staf'])
					$_SESSION['staf_search']['sql'] = $sql;
			}
			/***************************************************************************************************/
			if ($extra['functions']) {
				$functions += $extra['functions'];
			}

			return DBGet(DBQuery($sql), $functions);
			break;
	}
}
function GetUserStaffList(&$extra)
{
	global $profiles_RET;
	$functions = array('PROFILE' => 'makeProfile');
	switch (User('PROFILE')) {
		case 'admin':
			$profiles_RET = DBGet(DBQuery('SELECT * FROM user_profiles'), array(), array('ID'));
			$sql = 'SELECT DISTINCT CONCAT(TRIM(s.LAST_NAME),\', \',s.FIRST_NAME,\' \',COALESCE(s.MIDDLE_NAME,\' \')) AS FULL_NAME,
					s.PROFILE,s.IS_DISABLE,s.PROFILE_ID,ssr.END_DATE,s.STAFF_ID ' . $extra['SELECT'] . '
                FROM
					staff s INNER JOIN staff_institute_relationship ssr USING(staff_id) ' . $extra['FROM'] . ',login_authentication la
				WHERE
					(s.PROFILE_ID!=4 OR s.PROFILE_ID IS NULL) AND ssr.SYEAR=\'' . UserSyear() . '\' AND s.STAFF_ID=la.USER_ID AND la.PROFILE_ID NOT IN (3,4)';
			if (User('PROFILE_ID') == '1')
				$sql .= ' AND s.PROFILE_ID!=0 ';

			if ($_REQUEST['_search_all_institutes'] != 'Y')
				$sql .= ' AND institute_id=' .  UserInstitute() . ' ';
			else
				$sql .= ' AND institute_id IN(' .  GetUserInstitutes(UserID(), true) . ') ';
			if ($_REQUEST['_dis_user'] != 'Y')
				$sql .= ' AND (s.IS_DISABLE<>\'Y\' OR  s.IS_DISABLE IS NULL) AND (ssr.END_DATE>=\'' . date('Y-m-d') . '\' OR ssr.END_DATE<\'0000-01-01\' OR ssr.END_DATE IS NULL)';
			if ($_REQUEST['username'])
				$sql .= 'AND UPPER(la.USERNAME) LIKE \'' . singleQuoteReplace("'", "\'", strtoupper($_REQUEST['username'])) . '%\' ';
			if ($_REQUEST['last'])
				$sql .= 'AND UPPER(s.LAST_NAME) LIKE \'' . singleQuoteReplace("'", "\'", strtoupper($_REQUEST['last'])) . '%\' ';
			if ($_REQUEST['first'])
				$sql .= 'AND UPPER(s.FIRST_NAME) LIKE \'' . singleQuoteReplace("'", "\'", strtoupper($_REQUEST['first'])) . '%\' ';
			if ($_REQUEST['profile'] == "") {
				$sql .= ' ';
			} else {
				if ($_REQUEST['profile'] == 0 || $_REQUEST['profile']) {
					if (is_number($_REQUEST['profile']) == FALSE)
						$sql .= ' AND s.PROFILE=\'' . $_REQUEST['profile'] . '\' AND s.PROFILE_ID IS NULL ';
					else
						$sql .= ' AND s.PROFILE_ID=\'' . $_REQUEST['profile'] . '\' ';
				}
			}

			$sql .= $extra['WHERE'] . ' ';

			if (strpos($_REQUEST['modname'], 'users/TeacherPrograms.php') !== false) {
				$sql .= ' AND s.PROFILE_ID NOT IN(0,1) ';
			}

			if ($extra['GROUP'])
				$sql .= ' GROUP BY ' . $extra['GROUP'];
			$sql .= 'ORDER BY FULL_NAME ';
			/**************************************for Back to User*************************************************************/
			if ($_SESSION['staf_search']['sql'] && $_REQUEST['return_session']) {
				$sql = $_SESSION['staf_search']['sql'];
			} else {
				if ($_REQUEST['sql_save_session_staf'])
					$_SESSION['staf_search']['sql'] = $sql;

				if (strpos($_REQUEST['modname'], 'users/TeacherPrograms.php') !== false) {
					$_SESSION['staf_search_hold'] = $sql;
				}
			}

			/***************************************************************************************************/
			if ($extra['functions'])
				$functions += $extra['functions'];
			//echo $sql;
			return DBGet(DBQuery($sql), $functions);
			break;
	}
}



function makeProfile($value)
{
	global $THIS_RET, $profiles_RET;

	if ($THIS_RET['PROFILE_ID'] != '')
		$return = $profiles_RET[$THIS_RET['PROFILE_ID']][1]['TITLE'];
	elseif ($value == 'admin')
		$return = 'Administrator w/Custom';
	elseif ($value == 'teacher')
		$return = 'Teacher w/Custom';
	elseif ($value == 'parent')
		$return = 'Parent w/Custom';
	elseif ($value == 'none')
		$return = 'No Access';
	else $return = $value;

	return $return;
}



# ---------------------------------------- For Missing attn ------------------------------------------------- #

function GetStaffList_Miss_Atn(&$extra)
{

	global $profiles_RET;
	$functions = array('PROFILE' => 'makeProfile');
	switch (User('PROFILE')) {
		case 'admin':
			$profiles_RET = DBGet(DBQuery('SELECT * FROM user_profiles'));
			$sql = 'SELECT CONCAT(TRIM(s.LAST_NAME),\', \',s.FIRST_NAME,\' \',COALESCE(s.MIDDLE_NAME,\' \')) AS FULL_NAME,
						s.PROFILE,s.PROFILE_ID,s.STAFF_ID ' . $extra['SELECT'] . '
					FROM
						staff s INNER JOIN staff_institute_relationship ssr USING(staff_id) ' . $extra['FROM'] . '
					WHERE
						ssr.SYEAR=\'' . UserSyear() . '\'';
			if ($_REQUEST['_search_all_institutes'] != 'Y')
				$sql .= ' AND ssr.institute_id=' . UserInstitute() . ' ';
			if ($_REQUEST['username'])
				$sql .= 'AND UPPER(s.USERNAME) LIKE \'' . singleQuoteReplace("'", "\'", strtoupper($_REQUEST['username'])) . '%\' ';
			if ($_REQUEST['last'])
				$sql .= 'AND UPPER(s.LAST_NAME) LIKE \'' . singleQuoteReplace("'", "\'", strtoupper($_REQUEST['last'])) . '%\' ';
			if ($_REQUEST['first'])
				$sql .= 'AND UPPER(s.FIRST_NAME) LIKE \'' . singleQuoteReplace("'", "\'", strtoupper($_REQUEST['first'])) . '%\' ';
			if ($_REQUEST['profile'])
				$sql .= 'AND s.PROFILE=\'' . $_REQUEST['profile'] . '\' ';


			//echo $sql_st = 'SELECT teacher_id FROM missing_attendance mi where institute_id=\''.  UserInstitute().'\' AND syear=\''.  UserSyear().'\''.$extra['WHERE2'].' UNION SELECT secondary_teacher_id FROM missing_attendance mi where institute_id=\''.  UserInstitute().'\' AND syear=\''.  UserSyear().'\''.$extra['WHERE2'];

			$sql_st = 'SELECT cp.teacher_id FROM missing_attendance mi,course_periods cp,institutes s,course_period_var cpv WHERE mi.COURSE_PERIOD_ID=cp.COURSE_PERIOD_ID AND cp.COURSE_PERIOD_ID=cpv.COURSE_PERIOD_ID AND cpv.PERIOD_ID=mi.PERIOD_ID AND s.ID=mi.INSTITUTE_ID AND mi.INSTITUTE_ID=\'' .  UserInstitute() . '\'   AND (mi.INSTITUTE_DATE=cpv.COURSE_PERIOD_DATE OR POSITION(IF(DATE_FORMAT(mi.INSTITUTE_DATE,\'%a\') LIKE \'Thu\',\'H\',(IF(DATE_FORMAT(mi.INSTITUTE_DATE,\'%a\') LIKE \'Sun\',\'U\',SUBSTR(DATE_FORMAT(mi.INSTITUTE_DATE,\'%a\'),1,1)))) IN cpv.DAYS)>0)' . $extra['WHERE2'] . ' UNION select cp.SECONDARY_TEACHER_ID FROM missing_attendance mi,course_periods cp,institutes s,course_period_var cpv WHERE mi.COURSE_PERIOD_ID=cp.COURSE_PERIOD_ID AND cp.COURSE_PERIOD_ID=cpv.COURSE_PERIOD_ID AND cpv.PERIOD_ID=mi.PERIOD_ID AND s.ID=mi.INSTITUTE_ID AND mi.INSTITUTE_ID=\'' .  UserInstitute() . '\' AND (mi.INSTITUTE_DATE=cpv.COURSE_PERIOD_DATE OR POSITION(IF(DATE_FORMAT(mi.INSTITUTE_DATE,\'%a\') LIKE \'Thu\',\'H\',(IF(DATE_FORMAT(mi.INSTITUTE_DATE,\'%a\') LIKE \'Sun\',\'U\',SUBSTR(DATE_FORMAT(mi.INSTITUTE_DATE,\'%a\'),1,1)))) IN cpv.DAYS)>0)' . $extra['WHERE2'];
			$res_st = DBGet(DBQuery($sql_st));
			//echo count($res_st);
			$a = 0;
			foreach ($res_st as $row_st) {
				$teacher_str .= "'" . $row_st['TEACHER_ID'] . "',";
				$a++;
			}

			if ($a != 0) {
				$teacher_str = substr($teacher_str, 0, -1);
				$sql .= 'AND s.STAFF_ID IN (' . $teacher_str . ')';
			}



			$sql .= $extra['WHERE'] . ' ';
			if ($extra['GROUP'])
				$sql .= ' GROUP BY ' . $extra['GROUP'];
			$sql .= 'ORDER BY FULL_NAME';


			if ($extra['functions'])
				$functions = $extra['functions'];

			if ($a != 0) {
				if (count($functions) > 0)
					return DBGet(DBQuery($sql), $functions);
				else
					return DBGet(DBQuery($sql));
			}
			break;
	}
}

function GetStaffListNoAccess()
{

	switch (User('PROFILE')) {
		case 'admin':
			$sql = 'SELECT DISTINCT CONCAT(TRIM(s.LAST_NAME),\', \',s.FIRST_NAME,\' \',COALESCE(s.MIDDLE_NAME,\' \')) AS FULL_NAME,CONCAT(UPPER(MID(s.PROFILE,1,1)),MID(s.PROFILE,2,LENGTH(s.PROFILE)-1)) AS PROFILE,s.PROFILE_ID,s.IS_DISABLE,
                      s.STAFF_ID FROM people s ,students st,student_enrollment ssm WHERE st.STUDENT_ID=ssm.STUDENT_ID AND
                      ssm.SYEAR=' . UserSyear() . ' AND s.PROFILE IS NOT NULL AND s.PROFILE_ID=4
                      AND ' . ($_REQUEST['_search_all_institutes'] == 'Y' ? 'ssm.INSTITUTE_ID IN (SELECT INSTITUTE_ID FROM institute_years WHERE SYEAR=' . UserSyear() . ')' : 'ssm.INSTITUTE_ID=' . UserInstitute()) . ' 
                      AND s.STAFF_ID IN (SELECT PERSON_ID FROM students_join_people sjp WHERE ssm.STUDENT_ID = sjp.STUDENT_ID AND ssm.INSTITUTE_ID=' . ($_REQUEST['_search_all_institutes'] == 'Y' ? 'ssm.INSTITUTE_ID IN (SELECT INSTITUTE_ID FROM institute_years WHERE SYEAR=' . UserSyear() . ')' : 'ssm.INSTITUTE_ID=' . UserInstitute()) . ' 
                      AND ssm.SYEAR=' . UserSyear() . ') AND s.IS_DISABLE IS NULL AND s.PROFILE=\'parent\' AND s.PROFILE_ID=4';
			if ($_REQUEST['last'])
				$sql .= ' AND UPPER(s.LAST_NAME) LIKE \'' . singleQuoteReplace("'", "\'", strtoupper($_REQUEST['last'])) . '%\' ';
			if ($_REQUEST['first'])
				$sql .= ' AND UPPER(s.FIRST_NAME) LIKE \'' . singleQuoteReplace("'", "\'", strtoupper($_REQUEST['first'])) . '%\' ';
			$sql .= ' AND s.STAFF_ID NOT IN (SELECT USER_ID FROM login_authentication WHERE PROFILE_ID=4) ORDER BY FULL_NAME';
			return DBGet(DBQuery($sql));
			break;
	}
}
