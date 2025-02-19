<?php


function setPaginationRequisites($modname, $searchModfunc, $nextModname, $columns, $singular, $plural, $link, $LOGroup, $options, $ListOutputFunction, $ProgramTitle = false) {

	if($ListOutputFunction != '') {
		$pagi_mods = array();

		if($modname != '') {
			$pagi_mods['modname'] = $modname;
		}

		if($searchModfunc != '') {
			$pagi_mods['search_modfunc'] = $searchModfunc;
		}

		if($nextModname != '') {
			$pagi_mods['next_modname'] = $nextModname;
		}

		unset($_SESSION['PEGI_MODS']);
		$_SESSION['PEGI_MODS'] = $pagi_mods;

		unset($_SESSION['PEGI_COLS']);
		$_SESSION['PEGI_COLS'] = $columns;

		unset($_SESSION['PEGI_SINGULAR']);
		$_SESSION['PEGI_SINGULAR'] = $singular;

		unset($_SESSION['PEGI_PLURAL']);
		$_SESSION['PEGI_PLURAL'] = $plural;

		unset($_SESSION['PEGI_LINK']);
		$_SESSION['PEGI_LINK'] = $link;

		unset($_SESSION['PEGI_LOGRP']);
        $_SESSION['PEGI_LOGRP'] = $LOGroup;

        unset($_SESSION['PEGI_OPTION']);
        $_SESSION['PEGI_OPTION'] = $options;

        unset($_SESSION['LISTOUTPUT_FUNC']);
        $_SESSION['LISTOUTPUT_FUNC'] = $ListOutputFunction;

        unset($_SESSION['PROGRAM_TITLE']);
        if($ProgramTitle != '') {
        	$_SESSION['PROGRAM_TITLE'] = $ProgramTitle;
        } else {
        	$_SESSION['PROGRAM_TITLE'] = 'HaniIMS';
        }
	}
}

function keepRequestParams($requests) {
	unset($_SESSION['PEGI_REQUESTS']);

	$_SESSION['PEGI_REQUESTS'] = array();
	
	$_SESSION['PEGI_REQUESTS'] = $requests;
}

function keepExtraParams($extra) {
	unset($_SESSION['PEGI_EXTRA']);
	
	$_SESSION['PEGI_EXTRA'] = $extra;
}

function checkPagesForPrint($modname) {
	if($modname != '') {
		$pagesForPrint = array(
	        // Students
	        'students/MailingLabels.php', 
	        'students/StudentLabels.php', 
	        'students/PrintStudentInfo.php', 
	        'students/PrintStudentContactInfo.php', 
	        'students/GoalReport.php', 
	        // Scheduling
	        'scheduling/PrintSchedules.php', 
	        // Grades
	        'grades/ReportCards.php', 
	        'grades/AdminProgressReports.php', 
	        'grades/ProgressReports.php', 
	        'grades/Transcripts.php'
	    );

		if(in_array($modname, $pagesForPrint)) {
			return true;
		} else {
			return false;
		}

	} else {
		return false;
	}
}

function checkNoNeedPaging($modname) {
	if($modname != '') {
		$noNeedPaging = array(
	        // Teacher Programs
	        'users/TeacherPrograms.php?include=grades/InputFinalGrades.php', 
	        'users/TeacherPrograms.php?include=grades/Grades.php', 
	        'users/TeacherPrograms.php?include=grades/ProgressReports.php', 
	        // Grades
	        'grades/ProgressReports.php', 
	        'grades/Transcripts.php', 
	        // Users
	        'users/TeacherPrograms.php?include=eligibility/EnterEligibility.php', 
	        // Eligibility
	        'eligibility/EnterEligibility.php', 
	        // Attendance
	        'attendance/DailySummary.php'
	    );

		if(in_array($modname, $noNeedPaging)) {
			return true;
		} else {
			return false;
		}

	} else {
		return false;
	}
}

?>