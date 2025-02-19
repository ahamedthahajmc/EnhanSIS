<?php
echo '
<div class="panel-body">
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-15">
        <div class="well">
            <div class="media-left media-middle">' . (AllowUse('institutesetup/Calendar.php') == true ? '<a href="javascript:void(0);" class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-xs btn-icon" onClick="check_content(\'Ajax.php?modname=institutesetup/Calendar.php\');">' : '') . '<i class="icon-calendar3"></i>' . (AllowUse('institutesetup/Calendar.php') == true ? '</a>' : '') . '</div>

            <div class="media-left">
                <h6 class="text-semibold no-margin">' . (AllowUse('institutesetup/Calendar.php') == true ? '<a href="javascript:void(0);" onClick="check_content(\'Ajax.php?modname=institutesetup/Calendar.php\');">' : '') . ''._calendarSetup.' ' . ($cal_setup[1]['REC'] > 0 ? '<small class="display-block no-margin text-success"><i class="icon-checkmark2"></i> '._complete.'</small>' : '<small class="display-block no-margin text-danger"><i class="icon-cross3"></i> '._incomplete.'</small>') . (AllowUse('institutesetup/Calendar.php') == true ? '</a>' : '') . '</h6>
            </div>
        </div>
    </div>


    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-15">
        <div class="well">
            <div class="media-left media-middle">' . (AllowUse('institutesetup/MarkingPeriods.php') == true ? '<a href="javascript:void(0);" class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-xs btn-icon" onClick="check_content(\'Ajax.php?modname=institutesetup/MarkingPeriods.php\');">' : '') . '<i class="icon-tree7"></i>' . (AllowUse('institutesetup/MarkingPeriods.php') == true ? '</a>' : '') . '</div>

            <div class="media-left">
                <h6 class="text-semibold no-margin">' . (AllowUse('institutesetup/MarkingPeriods.php') == true ? '<a href="javascript:void(0);" onClick="check_content(\'Ajax.php?modname=institutesetup/MarkingPeriods.php\');">' : '') . ''._markingPeriodSetup.'</a> ' . ($mp_setup[1]['REC'] > 1 ? '<small class="display-block no-margin text-success"><i class="icon-checkmark2"></i> '._complete.'</small>' : '<small class="display-block no-margin text-danger"><i class="icon-cross3"></i> '._incomplete.'</small>') . (AllowUse('institutesetup/MarkingPeriods.php') == true ? '</a>' : '') . '</h6>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-15">
        <div class="well">
            <div class="media-left media-middle">' . (AllowUse('attendance/AttendanceCodes.php') == true ? '<a href="javascript:void(0);" class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-xs btn-icon" onClick="check_content(\'Ajax.php?modname=attendance/AttendanceCodes.php\');">' : '') . '<i class="icon-clipboard5"></i>' . (AllowUse('attendance/AttendanceCodes.php') == true ? '</a>' : '') . '</div>

            <div class="media-left">
                <h6 class="text-semibold no-margin">' . (AllowUse('attendance/AttendanceCodes.php') == true ? '<a href="javascript:void(0);" onClick="check_content(\'Ajax.php?modname=attendance/AttendanceCodes.php\');">' : '') . ''._attendanceCodeSetup.' ' . ($att_code_setup[1]['REC'] > 0 ? '<small class="display-block no-margin text-success"><i class="icon-checkmark2"></i> '._complete.'</small>' : '<small class="display-block no-margin text-danger"><i class="icon-cross3"></i> '._incomplete.'</small>') . (AllowUse('attendance/AttendanceCodes.php') == true ? '</a>' : '') . '</h6>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-15">
        <div class="well">
            <div class="media-left media-middle">' . (AllowUse('grades/ReportCardGrades.php') == true ? '<a href="javascript:void(0);" class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-xs btn-icon" onClick="check_content(\'Ajax.php?modname=grades/ReportCardGrades.php\');">' : '') . '<i class="icon-stack3"></i>' . (AllowUse('grades/ReportCardGrades.php') == true ? '</a>' : '') . '</div>

            <div class="media-left">
                <h6 class="text-semibold no-margin">' . (AllowUse('grades/ReportCardGrades.php') == true ? '<a href="javascript:void(0);" onClick="check_content(\'Ajax.php?modname=grades/ReportCardGrades.php\');">' : '') . ''._gradeScaleSetup.' ' . ($grade_scale_setup[1]['REC'] > 0 ? '<small class="display-block no-margin text-success"><i class="icon-checkmark2"></i> '._complete.'</small>' : '<small class="display-block no-margin text-danger"><i class="icon-cross3"></i> '._incomplete.'</small>') . (AllowUse('grades/ReportCardGrades.php') == true ? '</a>' : '') . '</h6>
            </div>
        </div>
    </div>
    
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-15">
        <div class="well">
            <div class="media-left media-middle">' . (AllowUse('students/EnrollmentCodes.php') == true ? '<a href="javascript:void(0);" class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-xs btn-icon" onClick="check_content(\'Ajax.php?modname=students/EnrollmentCodes.php\');">' : '') . '<i class="icon-clipboard6"></i>' . (AllowUse('students/EnrollmentCodes.php') == true ? '</a>' : '') . '</div>

            <div class="media-left">
                <h6 class="text-semibold no-margin">' . (AllowUse('students/EnrollmentCodes.php') == true ? '<a href="javascript:void(0);" onClick="check_content(\'Ajax.php?modname=students/EnrollmentCodes.php\');">' : '') . ''._enrollmentCodeSetup.' ' . ($enroll_code_setup[1]['REC'] > 0 ? '<small class="display-block no-margin text-success"><i class="icon-checkmark2"></i> '._complete.'</small>' : '<small class="display-block no-margin text-danger"><i class="icon-cross3"></i> '._incomplete.'</small>') . (AllowUse('students/EnrollmentCodes.php') == true ? '</a>' : '') . '</h6>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-15">
        <div class="well">
            <div class="media-left media-middle">' . (AllowUse('institutesetup/GradeLevels.php') == true ? '<a href="javascript:void(0);" class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-xs btn-icon" onClick="check_content(\'Ajax.php?modname=institutesetup/GradeLevels.php\');">' : '') . '<i class="icon-graph"></i>' . (AllowUse('institutesetup/GradeLevels.php') == true ? '</a>' : '') . '</div>

            <div class="media-left">
                <h6 class="text-semibold no-margin">' . (AllowUse('institutesetup/GradeLevels.php') == true ? '<a href="javascript:void(0);" onClick="check_content(\'Ajax.php?modname=institutesetup/GradeLevels.php\');">' : '') . ''._gradeLevelSetup.' ' . ($grade_level_setup[1]['REC'] > 0 ? '<small class="display-block no-margin text-success"><i class="icon-checkmark2"></i> '._complete.'</small>' : '<small class="display-block no-margin text-danger"><i class="icon-cross3"></i> '._incomplete.'</small>') . (AllowUse('institutesetup/GradeLevels.php') == true ? '</a>' : '') . '</h6>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-15">
        <div class="well">
            <div class="media-left media-middle">' . (AllowUse('institutesetup/Periods.php') == true ? '<a href="javascript:void(0);" class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-xs btn-icon" onClick="check_content(\'Ajax.php?modname=institutesetup/Periods.php\');">' : '') . '<i class="icon-watch2"></i>' . (AllowUse('institutesetup/Periods.php') == true ? '</a>' : '') . '</div>

            <div class="media-left">
                <h6 class="text-semibold no-margin">' . (AllowUse('institutesetup/Periods.php') == true ? '<a href="javascript:void(0);" onClick="check_content(\'Ajax.php?modname=institutesetup/Periods.php\');">' : '') . ''._institutePeriodsSetup.' ' . ($periods_setup[1]['REC'] > 0 ? '<small class="display-block no-margin text-success"><i class="icon-checkmark2"></i> '._complete.'</small>' : '<small class="display-block no-margin text-danger"><i class="icon-cross3"></i> '._incomplete.'</small>') . (AllowUse('institutesetup/Periods.php') == true ? '</a>' : '') . '</h6>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-15">
        <div class="well">
            <div class="media-left media-middle">' . (AllowUse('institutesetup/Rooms.php') == true ? '<a href="javascript:void(0);" class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-xs btn-icon" onClick="check_content(\'Ajax.php?modname=institutesetup/Rooms.php\');">' : '') . '<i class="icon-grid6"></i>' . (AllowUse('institutesetup/Rooms.php') == true ? '</a>' : '') . '</div>

            <div class="media-left">
                <h6 class="text-semibold no-margin">' . (AllowUse('institutesetup/Rooms.php') == true ? '<a href="javascript:void(0);" onClick="check_content(\'Ajax.php?modname=institutesetup/Rooms.php\');">' : '') . ''._roomsSetup.' ' . ($rooms_setup[1]['REC'] > 0 ? '<small class="display-block no-margin text-success"><i class="icon-checkmark2"></i> '._complete.'</small>' : '<small class="display-block no-margin text-danger"><i class="icon-cross3"></i> '._incomplete.'</small>') . (AllowUse('institutesetup/Rooms.php') == true ? '</a>' : '') . '</h6>
            </div>
        </div>
    </div>
</div><!-- /.row -->
</div><!-- //.panel-body -->
             '      
?>