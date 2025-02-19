<?php


function BlockDelete($item) {
    switch ($item) {
        case 'institute':
            $find_student = DBGet(DBQuery('SELECT COUNT(STUDENT_ID) AS STUDENT_EXIST FROM student_enrollment WHERE INSTITUTE_ID=\'' . UserInstitute() . '\''));
            $find_student = $find_student[1]['STUDENT_EXIST'];
            $find_staff = DBGet(DBQuery('SELECT COUNT(STAFF_ID) AS STAFF_EXIST FROM staff WHERE CURRENT_INSTITUTE_ID=\'' . UserInstitute() . '\''));
            $find_staff = $find_staff[1]['STAFF_EXIST'];
            if ($find_student > 0 && $find_staff > 0) {
                PopTable('header', _unableToDelete);
                echo '<h5 class="text-danger text-center">This Institute cannot be deleted. There are Students and Teachers in this Institute</h5>';
                $btn = '<a href=Modules.php?modname=institutesetup/Institutes.php&institute_id=' . UserInstitute() . ' style="text-decoration:none">back to Institute Information</a>';
                PopTable('footer', $btn, 'style="text-align:center"');
                return false;
            } else
                return true;
            break;

        case 'subject':
            $find_student = DBGet(DBQuery('SELECT COUNT(sch.STUDENT_ID) AS STUDENT_EXIST FROM schedule sch,course_periods cp, courses c WHERE c.SUBJECT_ID=\'' . $_REQUEST['subject_id'] . '\''));
            $find_student = $find_student[1]['STUDENT_EXIST'];
            if ($find_student > 0) {
                PopTable('header', _unableToDelete);
                echo '<h5 class="text-danger text-center">Subject cannot be deleted. There are <b>' . $find_student . '</b> Students Enrolled</h5>';
                $btn = '<a href=Modules.php?modname=institutesetup/Courses.php&subject_id=' . strip_tags(trim($_REQUEST['subject_id'])) . ' class="btn btn-default"><i class="icon-arrow-left7"></i> Back to Subject</a>';
                PopTable('footer', $btn, 'style="text-align:center"');
                return false;
            } else
                return true;
            break;

        case 'course':
            $find_student = DBGet(DBQuery('SELECT COUNT(sch.STUDENT_ID) AS STUDENT_EXIST FROM schedule sch,course_periods cp, courses c WHERE sch.COURSE_ID=\'' . $_REQUEST['course_id'] . '\' AND sch.COURSE_ID=c.COURSE_ID AND c.SUBJECT_ID=\'' . $_REQUEST['subject_id'] . '\''));
            $find_student = $find_student[1]['STUDENT_EXIST'];
            if ($find_student > 0) {
                PopTable('header', _unableToDelete);
                echo '<h5 class="text-danger text-center">Course cannot be deleted. There are <b>' . $find_student . '</b> Students Enrolled</h5>';
                $btn = '<a href=Modules.php?modname=institutesetup/Courses.php&subject_id=' . strip_tags(trim($_REQUEST['subject_id'])) . '&course_id=' . strip_tags(trim($_REQUEST['course_id'])) . ' class="btn btn-default"><i class="icon-arrow-left7"></i> Back to Course</a>';
                PopTable('footer', $btn, 'style="text-align:center"');
                return false;
            } else
                return true;
            break;

        case 'course period':
            $find_student = DBGet(DBQuery('SELECT COUNT(sch.STUDENT_ID) AS STUDENT_EXIST FROM schedule sch,course_periods cp, courses c WHERE sch.COURSE_ID=\'' . $_REQUEST['course_id'] . '\' AND sch.COURSE_ID=c.COURSE_ID AND sch.COURSE_PERIOD_ID=\'' . $_REQUEST['course_period_id'] . '\' AND sch.COURSE_PERIOD_ID=cp.COURSE_PERIOD_ID AND c.SUBJECT_ID=\'' . $_REQUEST['subject_id'] . '\' AND sch.DROPPED=\'N\''));
            $find_student = $find_student[1]['STUDENT_EXIST'];
            if ($find_student > 0) {
                PopTable('header', _unableToDelete);
                echo '<h5 class="text-danger text-center">Course period cannot be deleted. There are <b>' . $find_student . '</b> Students Enrolled</h5>';
                $btn = '<a href=Modules.php?modname=institutesetup/Courses.php&subject_id=' . strip_tags(trim($_REQUEST['subject_id'])) . '&course_id=' . strip_tags(trim($_REQUEST['course_id'])) . '&course_period_id=' . strip_tags(trim($_REQUEST['course_period_id'])) . ' class="btn btn-default"><i class="icon-arrow-left7"></i> Back to course period</a>';
                PopTable('footer', $btn, 'style="text-align:center"');
                return false;
            } else
                return true;
            break;
        case 'calendar':
        case 'marking_period':
        case 'grade_level':
    }
}

?>