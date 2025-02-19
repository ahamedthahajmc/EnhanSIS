<?php

include('../../RedirectModulesInc.php');
include('lang/language.php');
if (clean_param($_REQUEST['values'], PARAM_NOTAGS) && ($_POST['values'] || $_REQUEST['ajax'])) {
    foreach ($_REQUEST['values'] as $vi => $vd) {
        if ($vi == 'new') {
            if ($vd['NAME'] != '') {
                $check_name = DBGet(DBQuery('SELECT COUNT(*) as REC_EX FROM institute_gradelevel_sections WHERE NAME=\'' . str_replace("'", "''", $vd['NAME']) . '\' AND INSTITUTE_ID=' . UserInstitute()));
                if ($check_name[1]['REC_EX'] > 0) {
                    $err_msg = _sectionAlreadyExists . '.';
                    break;
                }
            }
            if ($vd['NAME'] != '' && $vd['SORT_ORDER'] != '')
                DBQuery('INSERT INTO institute_gradelevel_sections (INSTITUTE_ID,NAME,SORT_ORDER) VALUES (' . UserInstitute() . ',\'' . str_replace("'", "''", $vd['NAME']) . '\',\'' . $vd['SORT_ORDER'] . '\')');
            elseif ($vd['NAME'] != '' && $vd['SORT_ORDER'] == '')
                DBQuery('INSERT INTO institute_gradelevel_sections (INSTITUTE_ID,NAME) VALUES (' . UserInstitute() . ',\'' . str_replace("'", "''", $vd['NAME']) . '\')');
        } elseif ($vi != 'new') {
            $go = 1;
            if ($vd['NAME'] != '') {
                $check_name = DBGet(DBQuery('SELECT COUNT(*) as REC_EX FROM institute_gradelevel_sections WHERE NAME=\'' . str_replace("'", "''", $vd['NAME']) . '\' AND INSTITUTE_ID=' . UserInstitute() . ' AND ID!=' . $vi));
                if ($check_name[1]['REC_EX'] > 0) {
                    $err_msg = _sectionAlreadyExists . '.';
                    break;
                } else
                    $go++;
            }
            $qry = 'UPDATE institute_gradelevel_sections SET ';

            if ($vd['NAME'] != '')
                $qry .= ' NAME=\'' . str_replace("'", "''", $vd['NAME']) . '\',';

            if ($vd['SORT_ORDER'] != '')
                $qry .= ' SORT_ORDER=\'' . $vd['SORT_ORDER'] . '\',';

            if ($vd['SORT_ORDER'] == '')
                $qry .= ' SORT_ORDER=\'' . $vd['SORT_ORDER'] . '\',';

            $qry = substr($qry, 0, -1);
            $qry .= ' WHERE ID=' . $vi;
            if ($go = 1)
                DBQuery($qry);
        }
    }
}
DrawBC("" . _instituteSetup . " > " . ProgramTitle());

if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'remove') {
    $sec_id = paramlib_validation($colmn = 'PERIOD_ID', $_REQUEST['id']);
    $has_assigned_RET = DBGet(DBQuery('SELECT COUNT(*) AS TOTAL_ASSIGNED FROM student_enrollment WHERE SECTION_ID=\'' . $sec_id . '\''));
    $has_assigned = $has_assigned_RET[1]['TOTAL_ASSIGNED'];
    if ($has_assigned > 0) {
        UnableDeletePrompt(_cannotDeleteBecauseSectionsAreAssociated . '.');
    } else {
        if (DeletePrompt_Sections('section')) {
            DBQuery('DELETE FROM institute_gradelevel_sections WHERE ID=' . $sec_id);
            unset($_REQUEST['modfunc']);
        }
    }
}

if ($_REQUEST['modfunc'] != 'remove') {
    $sql = 'SELECT * FROM institute_gradelevel_sections WHERE INSTITUTE_ID=\'' . UserInstitute() . '\' ORDER BY SORT_ORDER';
    $sec_RET = DBGet(DBQuery($sql), array('NAME' => 'makeTextInput', 'SORT_ORDER' => 'makeTextInput'));

    $columns = array('NAME' => _section, 'SORT_ORDER' => _sortOrder);
    $link['add']['html'] = array('NAME' => makeTextInput('', 'NAME'), 'SORT_ORDER' => makeTextInputMod2('', 'SORT_ORDER'));
    $link['remove']['link'] = "Modules.php?modname=$_REQUEST[modname]&modfunc=remove";
    $link['remove']['variables'] = array('id' => 'ID');
    if ($err_msg) {
        echo "<b style='color:red'>" . $err_msg . "</b>";

        unset($err_msg);
    }
    echo "<FORM name=F1 id=F1 action=Modules.php?modname=" . strip_tags(trim($_REQUEST['modname'])) . "&modfunc=update  onSubmit='return formcheck_institute_sections();'  method=POST>";

    $section_ids = array('new');
    foreach ($sec_RET as $li => $ld)
        $section_ids[] = $ld['ID'];

    echo '<div class="panel panel-default">';
    ListOutput($sec_RET, $columns, _section, _sections, $link, true, array('search' => false));
    echo '<hr class="no-margin"/>';
    echo '<div class="panel-body text-right">';
    echo '<input type=hidden value="' . implode('_', $section_ids) . '" id="get_ids" />';
    echo '<INPUT class="btn btn-primary" type=submit value=' . _save . ' onclick="self_disable(this)">';
    echo '</div>'; //.panel-footer
    echo '</div>'; //.panel
    echo '</FORM>';
}

function makeTextInput($value, $name)
{
    global $THIS_RET;

    if ($THIS_RET['ID'])
        $id = $THIS_RET['ID'];
    else
        $id = 'new';


    $extra = 'class=cell_floating size=25';

    return TextInput($value, 'values[' . $id . '][' . $name . ']', '', $extra);
}


function makeTextInputMod2($value, $name)
{
    global $THIS_RET;
    if ($THIS_RET['ID'])
        $id = $THIS_RET['ID'];
    else
        $id = 'new';

    if ($id == 'new')
        $extra = 'size=25 maxlength=5 class=cell_floating onKeyDown="return numberOnly(event);"';
    else
        $extra = 'size=25 maxlength=5 class=cell_floating onKeyDown=\"return numberOnly(event);\"';



    return TextInput($value, 'values[' . $id . '][' . $name . ']', '', $extra);
}
