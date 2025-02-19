<?php


include('../../RedirectModulesInc.php');
include('lang/language.php');

if (clean_param($_REQUEST['values'], PARAM_NOTAGS) && ($_POST['values'] || $_REQUEST['ajax']) && AllowEdit()) {
    $mflag = 0;
    foreach ($_REQUEST['values'] as $id => $columns) {
        $title = '';
        if (!(isset($columns['TITLE']) && trim($columns['TITLE']) == '')) {
            ##############################################################################################################
            if ($id != 'new') {
                $sql = "UPDATE rooms SET ";

                foreach ($columns as $column => $value) {
                    if ($column == 'TITLE') {
                        $title = $value;
                    }

                    if ($column != 'SORT_ORDER')
                        $value = trim(paramlib_validation($column, $value));
                    if ($column == 'CAPACITY') {
                        $assoc_check = DBGet(DBQuery('SELECT DISTINCT cp.COURSE_PERIOD_ID,cp.TOTAL_SEATS,cp.FILLED_SEATS FROM course_periods cp,course_period_var cpv WHERE cp.COURSE_PERIOD_ID=cpv.COURSE_PERIOD_ID AND cpv.ROOM_ID=' . $id));
                        if (count($assoc_check) == 0) {
                            $sql .= $column . '=\'' . singleQuoteReplace("'", "''", $value) . ' \',';
                        } else {
                            $total_seat = array();
                            $go_tot_seat = 'n';
                            foreach ($assoc_check as $ai => $ad) {
                                if ($ad['FILLED_SEATS'] <= $value)
                                    $go_tot_seat = 'y';
                                else {
                                    $go_tot_seat = 'n';
                                    break;
                                }
                            }
                            unset($ai);
                            unset($ad);
                            if ($go_tot_seat == 'y') {
                                $sql .= $column . '=\'' . str_replace("'", "''", $value) . ' \',';
                                foreach ($assoc_check as $ai => $ad) {
                                    DBQuery('UPDATE course_periods SET TOTAL_SEATS=' . $value . ' WHERE COURSE_PERIOD_ID=' . $ad['COURSE_PERIOD_ID']);
                                }
                            } else {
                                echo '<div class="alert bg-danger alert-styled-left">';
                                echo '<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">' . _close . '</span></button>';
                                echo 'cannot Change Room Capacity As It HasAssociation';
                                echo '</div>';
                            }
                        }
                    }
                    if ($column != 'CAPACITY' && $column != 'SORT_ORDER')
                        $sql .= $column . '=\'' . singleQuoteReplace("'", "''", $value) . ' \',';
                    if ($column == 'SORT_ORDER') {
                        $srt_odr = singleQuoteReplace("'", "''", $value);
                        $validate_srt_odr = DBGet(DBQuery('SELECT *  FROM rooms WHERE  SORT_ORDER=\'' . $srt_odr . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\''));
                        $sql .= $column . ($value != '' ? '=\'' . singleQuoteReplace("'", "''", $value) . ' \',' : '=NULL,');
                    }
                }
                $sql = substr($sql, 0, -1) . " WHERE room_id='$id'";
                $sql = str_replace('&amp;', "", $sql);
                $sql = str_replace('&quot', "", $sql);
                $sql = str_replace('&#039;', "", $sql);
                $sql = str_replace('&lt;', "", $sql);
                $sql = str_replace('&gt;', "", $sql);
                //echo $sql;
                //echo 'SELECT *  FROM rooms WHERE  TITLE=\'' . $title . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\'';
                $validate_title = DBGet(DBQuery('SELECT *  FROM rooms WHERE  TITLE=\'' . $title . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\''));


                if (count($validate_title) != 0) {
                    $mflag = 1;
                    /*echo '<div class="alert alert-info">';
                    echo '<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>';
                    echo 'Room with similar title already exists.';
                    echo '</div>';*/
                    //                } else if (isset($validate_srt_odr) && count($validate_srt_odr) != 0) {
                    //                    $samedata = DBGet(DBQuery("select SORT_ORDER from rooms  WHERE room_id='$id'"));
                    //                    $samedata = $samedata[1]['SORT_ORDER'];
                    //                    if ($samedata != $srt_odr) {
                    //                        echo '<div class="alert bg-danger alert-styled-left">';
                    //                        echo '<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>';
                    //                        echo 'Unable to save data, because sort order already exists.';
                    //                        echo '</div>';
                    //                    }

                }
                //else {

                DBQuery($sql);
                //}
            } else {
                $sql1 = "INSERT INTO rooms ";
                $fields = 'INSTITUTE_ID,';
                $values = "'" . UserInstitute() . "',";
                $go = 0;
                foreach ($columns as $column => $value) {
                    if ($column == 'TITLE') {
                        $title = $value;
                    }
                    if ($column == 'SORT_ORDER') {
                        if ($value != '') {
                            $value = trim(paramlib_validation($column, $value));

                            $fields .= $column . ',';
                            $values .= '\'' . singleQuoteReplace("'", "''", $value) . ' \',';
                            $go = true;
                        }
                    } else {
                        $value = trim(paramlib_validation($column, $value));
                        $fields .= $column . ',';
                        $values .= '\'' . singleQuoteReplace("'", "''", $value) . ' \',';
                        $go = true;
                    }
                }
                $sql1 .= '(' . substr($fields, 0, -1) . ') values(' . substr($values, 0, -1) . ')';


                $validate_title = DBGet(DBQuery('SELECT TITLE  FROM rooms WHERE  TITLE=\'' . $title . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\''));


                if (count($validate_title) != 0) {
                    $mflag = 1;
                }
                if ($go)
                    DBQuery($sql1);
            }
        }
    }

    if ($mflag == 1) {
        echo '<div class="alert alert-warning">';
        echo '<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">' . _close . '</span></button>';
        echo 'Rooms found with similar title.';
        echo '</div>';
    }
}

DrawBC("" . _instituteSetup . " > " . ProgramTitle());
if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'remove' && AllowEdit()) {
    $room_id = paramlib_validation($colmn = 'PERIOD_ID', $_REQUEST['id']);
    $has_assigned_RET = DBGet(DBQuery("SELECT COUNT(*) AS TOTAL_ASSIGNED FROM course_period_var WHERE room_id='$room_id'"));
    $has_assigned = $has_assigned_RET[1]['TOTAL_ASSIGNED'];
    if ($has_assigned > 0) {
        $qs = 'Modules.php?modname=institutesetup/Rooms.php';
        UnableDeletePromptMod('' . _cannotDeleteBecauseRoomAreAssociated . '.', 'delete', $qs);
    } else {
        $qs = 'Modules.php?modname=institutesetup/Rooms.php';
        if (DeletePromptMod('room', $qs)) {
            DBQuery("DELETE FROM rooms WHERE room_id='$room_id'");
            unset($_REQUEST['modfunc']);
        }
    }
}

if ($_REQUEST['modfunc'] != 'remove') {
    $sql = "SELECT  ROOM_ID,TITLE,TITLE as NAME,CAPACITY,DESCRIPTION,SORT_ORDER FROM rooms WHERE institute_id='" . UserInstitute() . "' ORDER BY sort_order";
    $QI = DBQuery($sql);
    $LO = DBGet(DBQuery($sql));
    $room_id_arr = array();
    foreach ($LO as $ti => $td) {
        array_push($room_id_arr, $td['ROOM_ID']);
    }
    $room_id = implode(',', $room_id_arr);
    $room_ids = '';
    $room_iv = '';
    $rooms_RET = DBGet($QI, array('TITLE' => '_makeTextInput', 'CAPACITY' => '_makeIntInput', 'DESCRIPTION' => '_makeTextInput', 'SORT_ORDER' => '_makeIntInput'));
    $columns = array('TITLE' => _title, 'CAPACITY' => _capacity, 'DESCRIPTION' => _description, 'SORT_ORDER' => _sortOrder);
    $link['add']['html'] = array('TITLE' => _makeTextInput('', 'TITLE'), 'CAPACITY' => _makeTextInput('', 'CAPACITY'), 'DESCRIPTION' => _makeTextInput('', 'DESCRIPTION'), 'SORT_ORDER' => _makeTextInput('', 'SORT_ORDER'));
    $link['remove']['link'] = "Modules.php?modname=$_REQUEST[modname]&modfunc=remove";
    $link['remove']['variables'] = array('id' => 'ROOM_ID');
    echo "<FORM name=F1 id=F1 action=Modules.php?modname=" . strip_tags(trim($_REQUEST['modname'])) . "&modfunc=update method=POST>";
    echo '<input type="hidden" name="h1" id="h1" value="' . $room_id . '">';
    echo '<div class="panel panel-white">';
    $count_room = count($rooms_RET);
    if ($count_room > 0) {
        $count_room = DBGet(DBQuery("Select max(ROOM_ID) as maxid FROM rooms"));
        $count_room = $count_room[1]['MAXID'];
    }
    echo "<input type=hidden id=count_room value=$count_room />";
    ListOutputPeriod($rooms_RET, $columns, _room, _rooms, $link);
    echo '<hr class="no-margin"/><div class="panel-body text-right">' . SubmitButton(_save, '', 'id="setupRoomsBtn" class="btn btn-primary" onclick="return formcheck_rooms(this);"') . '</div>';
    echo '</div>';
    echo '</FORM>';
}

function _makeTextInput($value, $name)
{
    global $THIS_RET;

    if ($THIS_RET['ROOM_ID'])
        $id = $THIS_RET['ROOM_ID'];
    else
        $id = 'new';

    if ($name != 'TITLE')
        $extra = 'size=5 maxlength=10 placeholder=' . ucwords(strtolower(str_replace('_', ' ', $name))) . ' class=form-control  id=' . $name . '_' . $id . '';
    else { # added else for the first textbox merlinvicki

        $extra = 'class=form-control placeholder=' . ucwords(strtolower(str_replace('_', ' ', $name))) . ' id=' . $name . '_' . $id . ' ';
        if ($id != "new")
            $extra .= ' onkeyup=\"fill_rooms(this,' . $id . ');\"  placeholder=' . ucwords(strtolower(str_replace('_', ' ', $name)));
    }
    if ($name == 'SORT_ORDER')
        $extra = ' size=5 maxlength=10 class=form-control placeholder=' . ucwords(strtolower(str_replace('_', ' ', $name))) . ' id=' . $name . '_' . $id . ' onkeydown="return numberOnly(event);"';
    if ($name == 'CAPACITY')
        $extra = ' size=5 maxlength=10 class=form-control placeholder=' . ucwords(strtolower(str_replace('_', ' ', $name))) . ' id=' . $name . '_' . $id . ' onkeydown="return numberOnly(event);"';
    if ($name == 'DESCRIPTION')
        $extra = 'size=30 placeholder=' . ucwords(strtolower(str_replace('_', ' ', $name)));
    return TextInput($value, 'values[' . $id . '][' . $name . ']', '', $extra);
}

function _makeIntInput($value, $name)
{
    global $THIS_RET;
    if ($THIS_RET['ROOM_ID'])
        $id = $THIS_RET['ROOM_ID'];
    else
        $id = 'new';
    if ($value != '')
        $extra = 'size=5 maxlength=10 class=form-control placeholder=' . ucwords(strtolower(str_replace('_', ' ', $name))) . ' onkeydown=\"return numberOnly(event);\"';
    else
        $extra = 'size=5 maxlength=10 class=form-control placeholder=' . ucwords(strtolower(str_replace('_', ' ', $name))) . ' onkeydown="return numberOnly(event);"';

    return TextInput($value, 'values[' . $id . '][' . $name . ']', '', $extra);
}
