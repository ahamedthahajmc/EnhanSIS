<?php
 

include "functions/ParamLibFnc.php";
require_once "Data.php";
include 'RedirectRootInc.php';
include 'ConfigInc.php';
include 'Warehouse.php';

$REQUEST_calendar_id = sqlSecurityFilter($_REQUEST['calendar_id']);
$REQUEST_institute_date = sqlSecurityFilter($_REQUEST['institute_date']);
$REQUEST_month = sqlSecurityFilter($_REQUEST['month']);
$REQUEST_year = sqlSecurityFilter($_REQUEST['year']);
$_REQUEST['event_id'] = sqlSecurityFilter($_REQUEST['event_id']);

//----------------------- modal for event start---------------------//
// $connection = new mysqli($DatabaseServer, $DatabaseUsername, $DatabasePassword, $DatabaseName);
$_REQUEST['event_id'] = mysqli_real_escape_string($connection,optional_param('event_id', '', PARAM_DATA));
$_REQUEST['assignment_id'] = sqlSecurityFilter($_REQUEST['assignment_id']);

if (($_REQUEST['event_id'] || !isset($_REQUEST['event_id'])) && !isset($_REQUEST['assignment_id'])) {
    if ($_REQUEST['event_id'] != 'new' && isset($_REQUEST['event_id'])) {
        $RET = DBGet(DBQuery("SELECT TITLE,DESCRIPTION,INSTITUTE_DATE,CALENDAR_ID FROM calendar_events WHERE ID='$_REQUEST[event_id]'"));
        $title = $RET[1]['TITLE'];
        $calendar_id = $RET[1]['CALENDAR_ID'];
    } else {
        $_REQUEST['event_id'] = 'new';
        $title = _newEvent;
        $RET[1]['INSTITUTE_DATE'] = date('Y-m-d', strtotime($REQUEST_institute_date));
        $RET[1]['CALENDAR_ID'] = '';
        $calendar_id = $REQUEST_calendar_id;
    }
    echo "<FORM name=popform class=\"m-b-0\" id=popform action=Modules.php?modname=institutesetup/Calendar.php&dd=$REQUEST_institute_date&modfunc=detail&event_id=$_REQUEST[event_id]&calendar_id=$calendar_id&month=$REQUEST_month&year=$REQUEST_year METHOD=POST>";
} else {
    $RET = DBGet(DBQuery('SELECT TITLE,STAFF_ID,DATE_FORMAT(DUE_DATE,\'%d-%b-%y\') AS INSTITUTE_DATE,ASSIGNED_DATE,DUE_DATE,DESCRIPTION FROM gradebook_assignments WHERE ASSIGNMENT_ID=\'' . $_REQUEST['assignment_id'] . '\''));
    $title = $RET[1]['TITLE'];
    $RET[1]['STAFF_ID'] = GetTeacher($RET[1]['STAFF_ID']);
}

echo '<div class="modal-body">';

echo '<div id=err_message ></div>';

echo '<div class="form-group">';
echo '<label class="control-label">Date: ' . ProperDate($REQUEST_institute_date) . '</label>';
echo '</div>';

if ($RET[1]['TITLE'] == '') {
    echo '<div class="form-group">';
    echo '<label class="control-label">'._title.' : &nbsp;</label>';
    echo (User('PROFILE') == 'admin' ? TextInputModal($RET[1]['TITLE'], 'values[TITLE]', '', 'id=title placeholder="'._enterTitle.'"') : $RET[1]['TITLE']);
    echo '</div>';
} else {
    echo '<div class="form-group">';
    echo '<label class="control-label">'._title.' : &nbsp;</label>';
    //echo (User('PROFILE') == 'admin' ? TextInputCusIdModal($RET[1]['TITLE'], 'values[TITLE]', '', ' placeholder="'._enterTitle.'"', true, 'title') : $RET[1]['TITLE']);
    echo (User('PROFILE') == 'admin' ? '<input class="form-control" id="values[TITLE]" name="values[TITLE]" value="' . $RET[1]['TITLE'] . '" placeholder="'._enterTitle.'" size="10" type="text">' : $RET[1]['TITLE']);
    echo '</div>';
}

if ($RET[1]['STAFF_ID']) {
    echo '<div class="form-group"><label class="control-label">'._teacher.' : &nbsp;</label>' . (User('PROFILE') == 'admin' ? TextAreaInput($RET[1]['STAFF_ID'], 'values[STAFF_ID]', '', 'placeholder="'._enterTeacher.'"') : $RET[1]['STAFF_ID']) . '</div>';
}

if ($RET[1]['ASSIGNED_DATE']) {
    echo '<div class="form-group"><label class="control-label">'._assignedDate.' : &nbsp;</label>' . (User('PROFILE') == 'admin' ? TextAreaInput($RET[1]['ASSIGNED_DATE'], 'values[ASSIGNED_DATE]', '', 'placeholder="'._enterAssignedDate.'"') : $RET[1]['ASSIGNED_DATE']) . '</div>';
}

if ($RET[1]['DUE_DATE']) {
    echo '<div class="form-group"><label class="control-label">'._dueDate.' : &nbsp;</label>' . (User('PROFILE') == 'admin' ? TextAreaInput($RET[1]['DUE_DATE'], 'values[DUE_DATE]', '', 'placeholder="'._enterDueDate.'"') : $RET[1]['DUE_DATE']) . '</div>';
}
echo '<div class="form-group">';
echo '<label class="control-label">'._notes.' : &nbsp;</label>';
//echo (User('PROFILE') == 'admin' ? TextAreaInputModal(html_entity_decode($RET[1]['DESCRIPTION']), 'values[DESCRIPTION]', '', 'style=height:200px;  placeholder="'._enterNotes.'"') : html_entity_decode($RET[1]['DESCRIPTION']));
if ($RET[1]['DESCRIPTION'] != '') {
    $RET[1]['DESCRIPTION'] = html_entity_decode(html_entity_decode($RET[1]['DESCRIPTION']));
    $RET[1]['DESCRIPTION'] = strip_tags($RET[1]['DESCRIPTION']);
}
echo (User('PROFILE') == 'admin' ? '<input class="form-control" id="values[DESCRIPTION]" name="values[DESCRIPTION]" value="' . $RET[1]['DESCRIPTION'] . '" placeholder="'._enterNotes.'" size="10" type="textArea">' : $RET[1]['DESCRIPTION']);
echo '</div>';

//        if (AllowEdit())
if (User('PROFILE') == 'admin')
{
    if($_REQUEST['event_id']!='new')
    {
    if($RET[1]['CALENDAR_ID']==0)
    $RET[1]['CALENDAR_ID']=1;
    else
    $RET[1]['CALENDAR_ID']=0;
    }
    echo '<div class="form-group">' . CheckboxInputSwitchModal($RET[1]['CALENDAR_ID'], 'new', _showEventsSystemWide, '', false, 'Yes', 'No', '', 'switch-success') . '</div>';
}
//else
//echo '<div class="form-group">' . ($RET[1]['CALENDAR_ID'] == '' ? '<i class="icon-checkbox-checked"></i>' : '<i class="icon-checkbox-unchecked"></i>') . '</div>';

echo '</div>'; //.modal-body

if (User('PROFILE') == 'admin') {
    echo '<div class="modal-footer">';
    echo '<INPUT type=submit class="btn btn-primary" name=button value='._save.' >';
    echo '&nbsp;';
    if ($_REQUEST['event_id'] != 'new') {
        echo '<INPUT type=submit name=button class="btn btn-white" value='._delete.' >';
    }
    echo '</div>'; //.modal-footer
}

echo '</FORM>';

unset($_REQUEST['values']);
unset($_SESSION['_REQUEST_vars']['values']);
unset($_REQUEST['button']);
unset($_SESSION['_REQUEST_vars']['button']);
