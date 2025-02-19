<?php
 
include('../../RedirectModulesInc.php');
$menu['scheduling']['admin'] = array(
						'scheduling/Schedule.php'=>_studentSchedule,
                                                'scheduling/ViewSchedule.php'=>_viewSchedule,
						'scheduling/Requests.php'=>_studentRequests,
						'scheduling/MassSchedule.php'=>_groupSchedule,
						'scheduling/MassRequests.php'=>_groupRequests,
						'scheduling/MassDrops.php'=>_groupDrops,
						'scheduling/MassDelete.php'=>_groupDelete,
						1=>_reports,
						'scheduling/InstitutewideScheduleReport.php'=>_institutewideScheduleReport,
						'scheduling/PrintSchedules.php'=>_printSchedules,
						'scheduling/PrintClassLists.php'=>_printClassLists,
						'scheduling/PrintClassPictures.php'=>_printClassPictures,
						'scheduling/PrintRequests.php'=>_printRequests,
						'scheduling/ScheduleReport.php'=>_scheduleReport,
						'scheduling/RequestsReport.php'=>_requestsReport,
						'scheduling/UnfilledRequests.php'=>_unfilledRequests,
						'scheduling/IncompleteSchedules.php'=>_incompleteSchedules,
						'scheduling/AddDrop.php'=>_addDropReport,
						2=>_setup,
						
						'scheduling/Scheduler.php'=>_runScheduler,
					);

$menu['scheduling']['teacher'] = array(
						// 'scheduling/Schedule.php'=>_schedule,
                        'scheduling/ViewSchedule.php'=>_viewSchedule,
						1=>_reports,
						'scheduling/PrintSchedules.php'=>_printSchedules,
						'scheduling/PrintClassLists.php'=>_printClassLists,
						'scheduling/PrintClassPictures.php'=>_printClassPictures,
					);

$menu['scheduling']['parent'] = array(
						'scheduling/ViewSchedule.php'=>_schedule,
						'scheduling/PrintClassPictures.php'=>_classPictures,
						'scheduling/Requests.php'=>_studentRequests,
                        'scheduling/StudentScheduleReport.php'=>_scheduleReport,
					);

$exceptions['scheduling'] = array(
						'scheduling/Requests.php'=>true,
						'scheduling/MassRequests.php'=>true,
						'scheduling/Scheduler.php'=>true,
                        'scheduling/StudentScheduleReport.php'=>_scheduleReport,
					);
?>
