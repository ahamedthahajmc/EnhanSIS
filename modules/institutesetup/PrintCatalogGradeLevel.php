<?php


include '../../RedirectModulesInc.php';
include 'lang/language.php';
if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'print' && $_REQUEST['report']) {
    echo '<style type="text/css">*{font-family:arial; font-size:12px;}</style>';
    echo '<link rel="stylesheet" type="text/css" href="assets/css/export_print.css" />
    <link rel="shortcut icon" href="assets/images/Plugin_icon_21.svg">';
    if (clean_param($_REQUEST['id'], PARAM_ALPHANUM)) {
        $from = ",courses c";
        $where = ' AND c.course_id=cp.course_id AND c.grade_level=' . $_REQUEST['id'];
    }
    $sql = 'SELECT distinct
                (SELECT title from course_subjects where subject_id=(SELECT subject_id from courses where course_id=cp.course_id)) as subject,
                (SELECT title from courses where course_id=cp.course_id) as COURSE_TITLE,cp.course_id
                from course_periods cp' . $from . ' where cp.institute_id=\'' . UserInstitute() . '\' and cp.syear=\'' . UserSyear() . '\' ' . $where . ' order by subject,COURSE_TITLE';


    $ret = DBGet(DBQuery($sql));

    if (count($ret)) {

        foreach ($ret as $s_id) {
            echo "<table width=100%  style=\" font-family:Arial; font-size:12px;\" >";
            $grade_level_RET = DBGet(DBQuery('SELECT TITLE FROM institute_gradelevels WHERE id=\'' . $_REQUEST['id'] . '\''));
            $grade_title = $grade_level_RET[1]['TITLE'];

            if ($grade_title != '') {
                echo "<tr><td width=105>" . DrawLogo() . "</td><td  style=\"font-size:15px; font-weight:bold; padding-top:20px;\">" . GetInstitute(UserInstitute()) . "<div style=\"font-size:12px;\">" . _courseCatalogByGradeLevel . " : " . $grade_title . "</div></td><td align=right style=\"padding-top:20px;\">" . ProperDate(DBDate()) . "<br />" . _poweredBy . " hani</td></tr><tr><td colspan=3 style=\"border-top:1px solid #333;\">&nbsp;</td></tr></table>";
            } else {
                echo "<tr><td width=105>" . DrawLogo() . "</td><td  style=\"font-size:15px; font-weight:bold; padding-top:20px;\">" . GetInstitute(UserInstitute()) . "<div style=\"font-size:12px;\">" . _courseCatalogByGradeLevel . " : " . _all . "</div></td><td align=right style=\"padding-top:20px;\">" . ProperDate(DBDate()) . "<br />" . _poweredBy . " hani</td></tr><tr><td colspan=3 style=\"border-top:1px solid #333;\">&nbsp;</td></tr></table>";
            }

            echo '<div align="center">';
            echo '<table border="0" width="100%" align="center"><tr><td><font face=verdana size=-1><b>' . $s_id['SUBJECT'] . '</b></font></td></tr>';
            echo '<tr><td align="right"><table border="0" width="97%"><tr><td><font face=verdana size=-1><b>' . $s_id['COURSE_TITLE'] . '</b></font></td></tr>';



            $sql_periods = 'SELECT  cp.COURSE_PERIOD_ID,cp.COURSE_PERIOD_ID as ROOM,cp.COURSE_PERIOD_ID as PERIOD,cp.COURSE_PERIOD_ID as DAYS,cp.SHORT_NAME,(SELECT CONCAT(LAST_NAME,\' \',FIRST_NAME,\' \') from staff where staff_id=cp.TEACHER_ID) as TEACHER from course_periods cp where cp.course_id=' . $s_id['COURSE_ID'] . ' and cp.syear=\'' . UserSyear() . '\' and cp.institute_id=\'' . UserInstitute() . '\'';
            $period_list = DBGet(DBQuery($sql_periods), array('ROOM' => '_makeDetails', 'PERIOD' => '_makeDetails', 'DAYS' => '_makeDetails'));

 ##############################################List Output Generation##################################################

            $columns = array('SHORT_NAME' => _coursePeriod, 'PERIOD' => _time, 'DAYS' => _days, 'ROOM' => _location, 'TEACHER' => _teacher);

            echo '<tr><td colspan="2" valign="top" align="right">';
            PrintCatalog($period_list, $columns, _course, _courses, '', '', array('search' => false));
            echo '</td></tr></table></td></tr></table></td></tr>';

            ######################################################################################################################
            echo '</table></div>';

            echo "<div style=\"page-break-before: always;\"></div>";
        }
    } else
        echo '<table width=100%><tr><td align=center><font color=red face=verdana size=2><strong>' . _noCoursesWereFoundInThisGradeLevel . '</strong></font></td></tr></table>';
} else {
    echo '<div class="row">';
    echo '<div class="col-md-6 col-md-offset-3">';
    PopTable('header', _printCatalogByGradeLevel, 'class="panel panel-default"');
    echo "<FORM id='search' name='search' class='form-horizontal' method=POST action=Modules.php?modname=" . strip_tags(trim($_REQUEST['modname'])) . ">";
    $grade_level_RET = DBGet(DBQuery('SELECT ID,TITLE FROM institute_gradelevels WHERE institute_id=\'' . UserInstitute() . '\''));
    if (count($grade_level_RET)) {
        echo '<div class="form-group"><div class="col-md-12">' . CreateSelect($grade_level_RET, 'id', 'All', _selectGradeLevel . ' : ', 'Modules.php?modname=' . strip_tags(trim($_REQUEST['modname'])) . '&id=') . '</div></div>';
    }

    if (clean_param($_REQUEST['id'], PARAM_ALPHANUM)) {
        $grade_level_RET = DBGet(DBQuery('SELECT TITLE FROM institute_gradelevels WHERE id=\'' . $_REQUEST['id'] . '\''));
        $grade_title = $grade_level_RET[1]['TITLE'];
        echo '<div class="alert bg-success alert-styled-left">' . _reportGeneratedFor . ' ' . $grade_title . ' ' . _gradeLevel . '</div>';
    } else
        echo '<div class="alert bg-success alert-styled-left">' . _reportGeneratedForAllGradeLevels . '</div>';
    echo '</form>';
    echo "<FORM name=exp id=exp action=ForExport.php?modname=" . strip_tags(trim($_REQUEST['modname'])) . "&modfunc=print&id=" . $_REQUEST['id'] . "&HaniIMS_PDF=true&report=true method=POST target=_blank>";
    echo '<div class="text-right"><INPUT type=submit class="btn btn-primary" value=\'' . _print . '\'></div>';
    echo '</form>';
    PopTable('footer');
    echo '</div>'; //.col-md-6.col-md-offset-3
    echo '</div>'; //.row
}

##########functions###################

function CreateSelect($val, $name, $opt, $cap, $link)
{
    $html = '<label class="control-label text-uppercase"><b>' . $cap . '</b></label>';
    $html .= "<select name=" . $name . " id=" . $name . " class=\"form-control\" onChange=\"window.location='" . $link . "' + this.options[this.selectedIndex].value;\">";
    $html .= "<option value=''>" . $opt . "</option>";

    foreach ($val as $key => $value) {
        if ($value[strtoupper($name)] == $_REQUEST[$name])
            $html .= "<option selected value=" . $value[strtoupper($name)] . ">" . $value['TITLE'] . "</option>";
        else
            $html .= "<option value=" . $value[strtoupper($name)] . ">" . $value['TITLE'] . "</option>";
    }
    $html .= "</select>";
    return $html;
}

function _makeDetails($value, $coulmn)
{
    $get_dt = DBGet(DBQuery('SELECT * FROM course_period_var WHERE COURSE_PERIOD_ID=' . $value));
    switch ($coulmn) {
        case 'ROOM':
            $room_title = array();
            foreach ($get_dt as $gd) {
                $title = DBGet(DBQuery('SELECT TITLE FROM rooms WHERE ROOM_ID=' . $gd['ROOM_ID']));
                $room_title[] = $title[1]['TITLE'];
            }
            if (count($room_title) > 0)
                $return = implode(',', $room_title);
            else
                $return = 'Room Not Found';
            break;

        case 'PERIOD':
            $period_title = array();
            foreach ($get_dt as $gd) {
                $title = DBGet(DBQuery('SELECT TITLE FROM institute_periods WHERE PERIOD_ID=' . $gd['PERIOD_ID']));
                $period_title[] = $title[1]['TITLE'];
            }
            if (count($period_title) > 0)
                $return = implode(',', $period_title);
            else
                $return = 'Period Not Found';
            break;

        default:
            $day_title = array();
            foreach ($get_dt as $gd) {
                if ($gd['DAYS'] != '')
                    $day_title[] = $gd['DAYS'];
                else {
                    $day_title[DaySname(date('l', $gd['COURSE_PERIOD_DATE']))] = DaySname(date('l', $gd['COURSE_PERIOD_DATE']));
                }
            }
            if (count($day_title) > 0)
                $return = implode(',', $day_title);
            else
                $return = 'Day Not Found';
    }
    return $return;
}
