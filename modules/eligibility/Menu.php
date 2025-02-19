<?php
 
include('../../RedirectModulesInc.php');
$menu['eligibility']['admin'] = array(
						'eligibility/Student.php'=>_studentScreen,
						'eligibility/AddActivity.php'=>_addActivity,
						1=>_reports,
						'eligibility/StudentList.php'=>_studentList,
						'eligibility/TeacherCompletion.php'=>_teacherCompletion,
						2=>_setup,
						'eligibility/Activities.php'=>_activities,
						'eligibility/EntryTimes.php'=>_entryTimes,
					);

$menu['eligibility']['teacher'] = array(
						'eligibility/EnterEligibility.php'=>_enterExtracurricular,
					);

$menu['eligibility']['parent'] = array(
						'eligibility/Student.php'=>_studentScreen,
						'eligibility/StudentList.php'=>_studentList,
					);

$menu['users']['admin'] += array(
						'users/TeacherPrograms.php?include=eligibility/EnterEligibility.php'=>_enterExtracurricular,
					);

$exceptions['eligibility'] = array(
						'eligibility/AddActivity.php'=>true
					);

$exceptions['users'] += array(
						'users/TeacherPrograms.php?include=eligibility/EnterEligibility.php'=>true
					);
?>