<?php



include('../../RedirectModulesInc.php');
include('lang/language.php');

if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'print_all' && $_REQUEST['report']) {
    echo '<link rel="stylesheet" type="text/css" href="assets/css/export_print.css" />
    <link rel="shortcut icon" href="assets/images/Plugin_icon_21.svg">';
    $sql_subject = 'SELECT SUBJECT_ID,TITLE FROM  course_subjects WHERE
                                        INSTITUTE_ID=' . UserInstitute() . ' AND SYEAR= ' . UserSyear();
    $sql_subject_ret = DBGet(DBQuery($sql_subject));
    if (count($sql_subject_ret)) {
        foreach ($sql_subject_ret as $subject) {
            echo "<table width=100%  style=\" font-family:Arial; font-size:12px;\" >";
            echo "<tr><td width=105>" . DrawLogo() . "</td><td  style=\"font-size:15px; font-weight:bold; padding-top:20px;\">" . GetInstitute(UserInstitute()) . "<div style=\"font-size:12px;\">" . _allCourses . "</div></td><td align=right style=\"padding-top:20px;\">" . ProperDate(DBDate()) . "<br />" . _poweredBy . " hani</td></tr><tr><td colspan=3 style=\"border-top:1px solid #333;\">&nbsp;</td></tr></table>";
            echo '<table border="0" width="100%" align="center"><tr><td><font face=verdana size=-1><b>' . $subject['TITLE'] . '</b></font></td></tr><tr>';

            $sql_course = 'SELECT COURSE_ID,TITLE FROM  courses WHERE
                                        INSTITUTE_ID=' . UserInstitute() . ' AND SYEAR= ' . UserSyear() . ' AND SUBJECT_ID=' . $subject['SUBJECT_ID'];

            $sql_course_ret = DBGet(DBQuery($sql_course));
            foreach ($sql_course_ret as $course) {
                echo '<table border="0"><tr><td style=padding-left:40px;><font face=verdana size=-1><b>' . $course['TITLE'] . '</b></font></td></tr></table>';

                $sql_course_period = 'SELECT TITLE FROM  course_periods WHERE
                                        INSTITUTE_ID=' . UserInstitute() . ' AND SYEAR= ' . UserSyear() . ' AND COURSE_ID=' . $course['COURSE_ID'];

                $sql_course_period_ret = DBGet(DBQuery($sql_course_period));
                foreach ($sql_course_period_ret as $course_period) {
                    echo '<table border="0" width="100%"><tr><td style=padding-left:80px;><font face=verdana size=-1><b>' . $course_period['TITLE'] . '</b></font></td></tr></table>';
                }
            }
            echo '</tr><tr><td colspan="2" valign="top" align="right">';
            echo '</td></tr></table>';
            echo "<div style=\"page-break-before: always;\"></div>";
        }
    }
} else {
    echo '<div class="row">';
    echo '<div class="col-md-6 col-md-offset-3">';
    PopTable('header', _printAllCourses, 'class="panel panel-default"');
    echo '<div class="alert bg-success alert-styled-left">' . _reportGenerated . '</div>';
    echo "<FORM name=exp id=exp action=ForExport.php?modname=" . strip_tags(trim($_REQUEST['modname'])) . "&modfunc=print_all&HaniIMS_PDF=true&report=true method=POST target=_blank>";
    echo '<div class="text-right"><INPUT type=submit class="btn btn-primary" value=\'' . _print . '\'></div>';
    echo '</form>';
    PopTable('footer');
    echo '</div>'; //.col-md-6.col-md-offset-3
    echo '</div>'; //.row
}
