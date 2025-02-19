<?php

include('../../RedirectModulesInc.php');

$menu['dashboard']['admin'] = array(
	'dashboard/dashboard.php'=>_subdashboard,
);

$menu['admission']['admin'] = array(
	'admission/admissionApplication.php'=>_addApplication,
	'admission/Documents.php'=> _docs,
	
);
$menu['crm']['admin'] = array(
	'crm/dashboard.php'=>_dashboard,
	'crm/add_calls.php'=>_addCalls,
	'crm/call_logs.php'=>_callLogs,
	'crm/report.php'=>_report,	
);
$menu['laibrary']['admin'] = array(
	'laibrary/add_books.php'=>_addBook,
	'laibrary/view_books.php'=>_viewBook,
	'laibrary/borrowBook.php'=>_borrowBook,
	'laibrary/reports.php'=>_report,

);
$menu['noDue']['admin'] = array(
'NoDue/ViewNoDue.php'=>_ViewNoDue,
'NoDue/addNoDue.php'=>_addNoDue,
'NoDue/NoDueCertificate.php'=>_noDueCertificate,
'NoDue/report.php'=>_report,
);

$menu['accounts']['admin'] = array(
	// 'account/dashboard.php' =>  _dashboard,
	'account/bill.php' => _bill,
	'account/vouchers.php' =>  _vouchers,
	// 'account/receipt.php' =>  _receipt,
	'account/payments.php' => _payments,
	'account/bankEntry.php' =>_bankentry,
);
$menu['onlinelLeave']['admin']=array(
	'onlineleave/leaveapplication.php'=>_leaveapplication,
	'onlineleave/leaverequestlist.php'=>_leaverequestlist,
	'onlineleave/leaverecord.php'=>_leaverecord,
	'onlineleave/leaveattendance.php'=>_leaveattendance,

	);
	$menu['onlinelLeave']['parent']=array(
		'onlineleave/leaveapplication.php'=>_leaveapplication,
		// 'onlineleave/leaverequestlist.php'=>_leaverequestlist,
		// 'onlineleave/leaverecord.php'=>_leaverecord,
		// 'onlineleave/leaveattendance.php'=>_leaveattendance,
	
		);
	$menu['hr']['admin'] = array(
		'hr/Attendance.php'=>_attendance,
		'hr/AttendanceHistory.php'=>_attendanceHistory,
		// 'institutesetup/Calendar.php'=>_time,
		// 'institutesetup/MarkingPeriods.php'=>_profile,
		'hr/SalarySlip.php'=>_salary_slip,
		'hr/LeaveManagement.php'=>_leave,
		// 'institutesetup/PrintCatalog.php'=>_documents,
	);
	
$menu['store']['admin'] = array(
 	'store/store.php'=>_store,
	
);
$menu['securitypass']['admin'] = array(
	
	'SecurityGatePass/visitorEntryPass.php'=>_visitorEntryPass,
	'SecurityGatePass/StudentOutPass.php'=>_studentOutPass,
	'SecurityGatePass/report.php'=>_report,
	
);
$menu['maintenance']['admin'] = array(
	'Maintenance/maintenance.php'=>_maintenance,
	 );
$menu['event']['admin'] = array(
	// 'event/dashboard.php' =>_dashboard,
	'event/eventListView.php' =>_eventView,
	'event/participant.php' =>_participant,
);

$menu['institutesetup']['admin'] = array(
						'institutesetup/PortalNotes.php'=>_portalNotes,
						'institutesetup/MarkingPeriods.php'=>_markingPeriods,
						'institutesetup/Calendar.php'=>_calendars,
						'institutesetup/Periods.php'=>_periods,
						'institutesetup/GradeLevels.php'=>_gradeLevels,
                                                'institutesetup/Sections.php'=>_sections,
                                                'institutesetup/Rooms.php'=>_rooms,
                         1=>_institute,
                        'institutesetup/Institutes.php'=>_instituteInformation,
						'institutesetup/Institutes.php?new_institute=true'=>_addAInstitute,
						'institutesetup/CopyInstitute.php'=>_copyInstitute,
						'institutesetup/SystemPreference.php'=>_systemPreference,
                                                'institutesetup/InstituteCustomFields.php'=>_instituteCustomFields,
                         2=>_courses,
                        'institutesetup/Courses.php'=>_courseManager,
                        'institutesetup/CourseCatalog.php'=>_courseCatalog,
						'institutesetup/PrintCatalog.php'=>_printCatalogByTerm,
						'institutesetup/PrintCatalogGradeLevel.php'=>_printCatalogByGradeLevel,
						'institutesetup/PrintAllCourses.php'=>_printAllCourses,
                        'institutesetup/TeacherReassignment.php'=>_teacherReAssignment
              );

$menu['institutesetup']['teacher'] = array(
						'institutesetup/Institutes.php'=>_instituteInformation,
						'institutesetup/MarkingPeriods.php'=>_markingPeriods,
						'institutesetup/Calendar.php'=>_calendar,
						1=>_courses,
                        'institutesetup/Courses.php'=>_courseManager,
                        'institutesetup/CourseCatalog.php'=>_courseCatalog,
						'institutesetup/PrintCatalog.php'=>_printCatalogByTerm,
						'institutesetup/PrintAllCourses.php'=>_printAllCourses
					);

$menu['institutesetup']['parent'] = array(
						'institutesetup/Institutes.php'=>_instituteInformation,
						'institutesetup/Calendar.php'=>_calendar
					);

$exceptions['institutesetup'] = array(
						'institutesetup/PortalNotes.php'=>true,
						'institutesetup/Institutes.php?new_institute=true'=>true,
						'institutesetup/Rollover.php'=>true
					);
