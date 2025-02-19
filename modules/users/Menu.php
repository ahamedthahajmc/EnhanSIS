<?php

include('../../RedirectModulesInc.php');
$menu['users']['admin'] = array(

						'users/Preferences.php'=>_preferences,
                                                1=>_report,
                                                'users/UserAdvancedReport.php'=>_userAdvancedReport,
                                                'users/UserAdvancedReportStaff.php'=>_staffAdvancedReport,
                                                2=>_parent,
						'users/User.php'=>_parentInfo,
                                                3=>_staff,
                                                'users/Staff.php'=>_staffInfo,
                                                'users/Staff.php&staff_id=new'=>_addAStaff,
						4=>_setup,
						'users/Profiles.php'=>_profiles,
						'users/UserFields.php'=>_parentFields,
                                                'users/StaffFields.php'=>_staffFields,
						5=>_teacherPrograms,

					);

$menu['users']['teacher'] = array(
                                                'users/Staff.php'=>_myInfo,
						'users/Preferences.php'=>_preferences,
					);

$menu['users']['parent'] = array(
                                                'users/User.php'=>_myInfo,
						'users/Preferences.php'=>_preferences,
					);

$exceptions['users'] = array(
						'users/User.php?staff_id=new'=>true
					);
?>