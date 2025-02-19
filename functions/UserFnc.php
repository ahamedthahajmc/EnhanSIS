<?php

function User($item)
{
	global $_HaniIMS, $DefaultSyear;
	if (!$_SESSION['UserSyear'])
		$_SESSION['UserSyear'] = $DefaultSyear;

	if (!$_HaniIMS['User'] || $_SESSION['UserSyear'] != $_HaniIMS['User'][1]['SYEAR']) {
		if ($_SESSION['STAFF_ID']) {
			if ($_SESSION['PROFILE_ID'] != 4)
				$sql = 'SELECT STAFF_ID,USERNAME,CONCAT(FIRST_NAME,\' \',LAST_NAME) AS NAME,PROFILE,la.PROFILE_ID,CURRENT_INSTITUTE_ID,EMAIL FROM staff s ,login_authentication la WHERE la.USER_ID=s.STAFF_ID AND la.PROFILE_ID <> 3 AND la.PROFILE_ID=s.PROFILE_ID AND STAFF_ID=' . $_SESSION['STAFF_ID'];
			if ($_SESSION['PROFILE_ID'] == 4 || $_SESSION['PROFILE'] == 'parent')
				$sql = 'SELECT p.STAFF_ID,la.USERNAME,CONCAT(p.FIRST_NAME,\' \',p.LAST_NAME) AS NAME,p.PROFILE,p.PROFILE_ID,p.CURRENT_INSTITUTE_ID,p.EMAIL FROM people p ,login_authentication la WHERE la.USER_ID=p.STAFF_ID AND la.PROFILE_ID <> 3  AND la.PROFILE_ID=p.PROFILE_ID AND STAFF_ID=' . $_SESSION['STAFF_ID'];
			$_HaniIMS['User'] = DBGet(DBQuery($sql));
		} elseif ($_SESSION['STUDENT_ID']) {
			$sql = 'SELECT USERNAME,CONCAT(s.FIRST_NAME,\' \',s.LAST_NAME) AS NAME,\'student\' AS PROFILE,\'3\' AS PROFILE_ID,CONCAT(\',\',se.INSTITUTE_ID,\',\') AS INSTITUTES,se.SYEAR,se.INSTITUTE_ID FROM students s,student_enrollment se,login_authentication la WHERE la.USER_ID=s.STUDENT_ID AND la.PROFILE_ID = 3 AND s.STUDENT_ID=' . $_SESSION['STUDENT_ID'] . ' AND se.SYEAR=\'' . $_SESSION['UserSyear'] . '\'  AND (se.END_DATE IS NULL OR se.END_DATE=\'0000-00-00\' OR se.END_DATE>=\'' . date('Y-m-d') . '\' ) AND se.STUDENT_ID=s.STUDENT_ID ORDER BY se.END_DATE DESC LIMIT 1';
			$_HaniIMS['User'] = DBGet(DBQuery($sql));
			if (count($_HaniIMS['User']) == 0) {
				$sql = 'SELECT USERNAME,CONCAT(s.FIRST_NAME,\' \',s.LAST_NAME) AS NAME,\'student\' AS PROFILE,\'3\' AS PROFILE_ID,CONCAT(\',\',se.INSTITUTE_ID,\',\') AS INSTITUTES,se.SYEAR,se.INSTITUTE_ID FROM students s,student_enrollment se,login_authentication la WHERE la.USER_ID=s.STUDENT_ID AND la.PROFILE_ID = 3 AND s.STUDENT_ID=' . $_SESSION['STUDENT_ID'] . ' AND se.SYEAR=\'' . $_SESSION['UserSyear'] . '\'   AND se.STUDENT_ID=s.STUDENT_ID ORDER BY se.END_DATE DESC LIMIT 1';
				$_HaniIMS['User'] = DBGet(DBQuery($sql));
				$_SESSION['UserInstitute'] = $_HaniIMS['User'][1]['INSTITUTE_ID'];
			} else {
				$_SESSION['UserInstitute'] = $_HaniIMS['User'][1]['INSTITUTE_ID'];
			}
		} else {
			exit('Error in User()');
		}
	}

	return $_HaniIMS['User'][1][$item];
}
function SelectedUserProfile($option)
{
	$prof = DBGet(DBQuery('SELECT ' . $option . ' FROM staff WHERE STAFF_ID=' . UserStaffID()));
	return $prof[1][$option];
}
function SelfStaffProfile($option)
{
	$prof = DBGet(DBQuery('SELECT ' . $option . ' FROM staff WHERE STAFF_ID=' . UserID()));
	return $prof[1][$option];
}
function Preferences($item, $program = 'Preferences')
{
	global $_HaniIMS;

	if ($_SESSION['STAFF_ID'] && !$_HaniIMS['Preferences'][$program]) {
		if ($program == 'Gradebook')
			$QI = DBQuery('SELECT TITLE,VALUE FROM program_user_config WHERE USER_ID=' . $_SESSION['STAFF_ID'] . ' AND PROGRAM=\'' . $program . '\' AND VALUE LIKE \'%_' . UserCoursePeriod() . '\'');
		else
			$QI = DBQuery('SELECT TITLE,VALUE FROM program_user_config WHERE USER_ID=' . $_SESSION['STAFF_ID'] . ' AND PROGRAM=\'' . $program . '\'');
		$_HaniIMS['Preferences'][$program] = DBGet($QI, array(), array('TITLE'));
	}

	$defaults = array(
		'NAME' => 'Common',
		'SORT' => 'Name',
		'SEARCH' => 'Y',
		'DELIMITER' => 'Tab',
		'COLOR' => '#FFFFCC',
		'HIGHLIGHT' => '#85E1FF',
		'TITLES' => 'gray',
		'THEME' => 'Brushed-Steel',
		'HIDDEN' => 'Y',
		'MONTH' => 'M',
		'DAY' => 'j',
		'YEAR' => 'Y',
		'DEFAULT_ALL_INSTITUTES' => 'N',
		'ASSIGNMENT_SORTING' => 'ASSIGNMENT_ID',
		'ANOMALOUS_MAX' => '100'
	);

	if (!isset($_HaniIMS['Preferences'][$program][$item][1]['VALUE']))
		$_HaniIMS['Preferences'][$program][$item][1]['VALUE'] = $defaults[$item];

	if ($_SESSION['STAFF_ID'] && User('PROFILE') == 'parent' || $_SESSION['STUDENT_ID'])
		$_HaniIMS['Preferences'][$program]['SEARCH'][1]['VALUE'] = 'N';
	if ($program == 'Gradebook') {
		if ($item == 'ANOMALOUS_MAX') {
			$arr = explode('_', $_HaniIMS['Preferences'][$program][$item][1]['VALUE']);
			return $arr[0];
		} else
			return rtrim($_HaniIMS['Preferences'][$program][$item][1]['VALUE'], '_' . UserCoursePeriod());
	} else
		return $_HaniIMS['Preferences'][$program][$item][1]['VALUE'];
}
function StaffCategory($staff_id)
{
	$category = DBGet(DBquery('SELECT CATEGORY FROM staff_institute_info WHERE STAFF_ID=' . $staff_id));
	return ($category[1]['CATEGORY'] == '' ? 'N/A' : $category[1]['CATEGORY']);
}
