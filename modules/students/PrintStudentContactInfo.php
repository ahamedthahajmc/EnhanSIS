<?php

include('../../RedirectModulesInc.php');
if (isset($_SESSION['student_id']) && $_SESSION['student_id'] != '') {
	$_REQUEST['search_modfunc'] = 'list';
}
if ($_REQUEST['modfunc'] == 'save') {
	if (count($_REQUEST['st_arr'])) {
		$st_list = '\'' . implode('\',\'', $_REQUEST['st_arr']) . '\'';
		$extra['WHERE'] = ' AND s.STUDENT_ID IN (' . $st_list . ')';



		$extra['FROM'] = ' ,students_join_people sjp,people p,student_address sa';
		$extra['SELECT'] = ' ,sjp.EMERGENCY_TYPE AS CONTACT_TYPE,sjp.RELATIONSHIP AS RELATION,CONCAT(p.Last_Name," " ,p.First_Name) AS RELATION_NAME,sa.STREET_ADDRESS_2 as STREET,sa.STREET_ADDRESS_1 as ADDRESS,sa.CITY,sa.STATE,sa.ZIPCODE AS ZIP,p.WORK_PHONE,p.HOME_PHONE,p.CELL_PHONE,p.EMAIL AS EMAIL_ID';
		$extra['WHERE'] .= ' AND sjp.student_id=ssm.student_id AND sjp.STUDENT_ID=sa.STUDENT_ID AND sjp.PERSON_ID=sa.PEOPLE_ID AND sjp.PERSON_ID=p.STAFF_ID';
		$extra['ORDER'] = ' ,sa.ID';

		$RET = GetStuList($extra);

		if (count($RET)) {
			$column_name = array(
				'STUDENT_ID' => _studentId,
				'ALT_ID' => _alternateId,
				'FULL_NAME' => _student,
				'CONTACT_TYPE' => _type,
				'RELATION' => _relation,
				'RELATION_NAME' => _relationSName,
				'STREET' => _street,
				'ADDRESS' => _address,
				'CITY' => _city,
				'STATE' => _state,
				'ZIP' => _zip,
				'WORK_PHONE' => _workPhone,
				'HOME_PHONE' => _homePhone,
				'CELL_PHONE' => _cellPhone,
				'EMAIL_ID' => _emailAddress,
			);
			$singular = _studentContact;
			$plural = _studentContacts;
			$options = array('search' => false);

			ListOutputPrint($RET, $column_name, $singular, $plural, $link = false, $group = false, $options);
		} else {
			ShowErrPhp(_noContactsWereFound);
			for_error();
		}
	} else {
		ShowErrPhp(_youMustChooseAtLeastOneStudent);
		for_error();
	}
	// unset($_SESSION['student_id']);

	$_REQUEST['modfunc'] = true;
}

if (!$_REQUEST['modfunc']) {
	DrawBC("" . _students . " > " . ProgramTitle());

	if ($_REQUEST['search_modfunc'] == 'list') {
		echo "<FORM action=ForExport.php?modname=$_REQUEST[modname]&modfunc=save&include_inactive=$_REQUEST[include_inactive]&_search_all_institutes=$_REQUEST[_search_all_institutes]&HaniIMS_PDF=true target=_blank method=POST>";
	}

	$extra['link'] = array('FULL_NAME' => false);
	$extra['SELECT'] = ',s.STUDENT_ID AS CHECKBOX';
	if (isset($_SESSION['student_id']) && $_SESSION['student_id'] != '') {
		$extra['WHERE'] .= ' AND s.STUDENT_ID=' . $_SESSION['student_id'];
	}
	$extra['functions'] = array('CHECKBOX' => '_makeChooseCheckbox');
	$extra['columns_before'] = array('CHECKBOX' => '</A><INPUT type=checkbox value=Y name=controller onclick="checkAllDtMod(this,\'st_arr\');"><A>');
	$extra['options']['search'] = false;
	$extra['new'] = true;



	Search('student_id', $extra);
	if ($_REQUEST['search_modfunc'] == 'list') {
		echo '<div class="text-right p-r-20 p-b-20"><INPUT type=submit class="btn btn-primary" value=\'' . _printContactInfoForSelectedStudents . '\'></div>';
		echo "</FORM>";
	}
}

// GetStuList by default translates the grade_id to the grade title which we don't want here.
// One way to avoid this is to provide a translation function for the grade_id so here we
// provide a passthru function just to avoid the translation.

function _makeChooseCheckbox($value, $title)
{
	//	return '<INPUT type=checkbox name=st_arr[] value='.$value.' checked>';
	global $THIS_RET;
	return "<input name=unused[$THIS_RET[STUDENT_ID]] value=" . $THIS_RET['STUDENT_ID'] . "  type='checkbox' id=$THIS_RET[STUDENT_ID] onClick='setHiddenCheckboxStudents(\"st_arr[]\",this,$THIS_RET[STUDENT_ID]);' />";
}
