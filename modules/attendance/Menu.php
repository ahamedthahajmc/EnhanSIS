<?php

include('../../RedirectModulesInc.php');
$menu['attendance']['admin'] = array(
						'attendance/Administration.php'=>_administration,
						'attendance/AddAbsences.php'=>_addAbsences,
						1=>_reports,
						'attendance/AttendanceData.php?list_by_day=true'=>_attendanceReport,
						'attendance/Percent.php'=>_averageDailyAttendance,
						'attendance/Percent.php?list_by_day=true'=>_averageAttendanceByDay,
						'attendance/DailySummary.php'=>_attendanceChart,
						'attendance/StudentSummary.php'=>_absenceSummary,
						'attendance/TeacherCompletion.php'=>_teacherCompletion,
						2=>_utilities,
						'attendance/FixDailyAttendance.php'=>_recalculateDailyAttendance,
						'attendance/DuplicateAttendance.php'=>_deleteDuplicateAttendance,
						3=>_setup,
						'attendance/AttendanceCodes.php'=>_attendanceCodes,
					);

$menu['attendance']['teacher'] = array(
						'attendance/TakeAttendance.php'=>_takeAttendance,
						'attendance/DailySummary.php'=>_attendanceChart,
						'attendance/StudentSummary.php'=>_absenceSummary,
					);

$menu['attendance']['parent'] = array(
						'attendance/StudentSummary.php'=>_absences,
						'attendance/DailySummary.php'=>_dailySummary,
					);

$menu['users']['admin'] += array(
						'users/TeacherPrograms.php?include=attendance/TakeAttendance.php'=>_takeAttendance,
						'users/TeacherPrograms.php?include=attendance/MissingAttendance.php'=>_missingAttendance,
					);

$exceptions['attendance'] = array(
						'attendance/AddAbsences.php'=>true
					);
?>
