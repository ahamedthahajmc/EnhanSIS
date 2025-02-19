<?php
 
if (!$_SESSION['STAFF_ID'] && !$_SESSION['STUDENT_ID'] && (strpos($_SERVER['PHP_SELF'], 'index.php')) === false) {
	header('Location: index.php');
	exit();
}
if (!$_HaniIMS['Menu']) {
	foreach ($HaniIMSModules as $module => $include)
		if ($include) {
			include "modules/$module/Menu.php";
		}

	$profile = User('PROFILE');

	if ($profile != 'student')
		if (User('PROFILE_ID') != '') {

			$can_use_RET = DBGet(DBQuery("SELECT MODNAME FROM profile_exceptions WHERE PROFILE_ID='" . User('PROFILE_ID') . "' AND CAN_USE='Y'"), array(), array('MODNAME'));
		} else {
			$profile_id_mod = DBGet(DBQuery("SELECT PROFILE_ID FROM staff WHERE USER_ID='" . User('STAFF_ID')));
			$profile_id_mod = $profile_id_mod[1]['PROFILE_ID'];
			if ($profile_id_mod != '')
				$can_use_RET = DBGet(DBQuery("SELECT MODNAME FROM profile_exceptions WHERE PROFILE_ID='" . $profile_id_mod . "' AND CAN_USE='Y'"), array(), array('MODNAME'));
		}
	else {
		$can_use_RET = DBGet(DBQuery("SELECT MODNAME FROM profile_exceptions WHERE PROFILE_ID='3' AND CAN_USE='Y'"), array(), array('MODNAME'));
		$profile = 'parent';
	}

	foreach ($menu as $modcat => $profiles) {
		$menuprof = $menu;
		$programs = $profiles[$profile];
		foreach ($programs as $program => $title) {
			if (!is_numeric($program)) {
				if ($can_use_RET[$program] && ($profile != 'admin' || !$exceptions[$modcat][$program] || AllowEdit($program)))
					$_HaniIMS['Menu'][$modcat][$program] = $title;
			} else {
				$_HaniIMS['Menu'][$modcat][$program] = $title;
			}
		}
	}

	if (User('PROFILE') == 'student')
		unset($_HaniIMS['Menu']['users']);
}
