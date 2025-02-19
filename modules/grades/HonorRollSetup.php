<?php

 
include 'modules/grades/DeletePromptX.fnc.php';
DrawBC(""._gradebook." > " . ProgramTitle());
if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'update') {
    if (clean_param($_REQUEST['values'], PARAM_NOTAGS) && ($_POST['values'] || $_REQUEST['ajax'])) {
        foreach ($_REQUEST['values'] as $id => $columns) {
            if ($id != 'new') {
                $sql = 'UPDATE honor_roll SET ';
                foreach ($columns as $column => $value) {
                    $value = paramlib_validation($column, $value);
                    $values .= ' \' ' . trim(singleQuoteReplace("","",$value)) . ' \',';
                    if ($value)
                        $sql .= $column . '=\'' . trim(singleQuoteReplace("","",$value)) . '\',';
                    else
                        $sql .= $column . '=NULL ,';
                }
                $sql = substr($sql, 0, -1) . ' WHERE id=\'' . $id . '\'';
                DBQuery($sql);
            }
            else {
                $sql = 'INSERT INTO honor_roll ';
                $fields = 'INSTITUTE_ID,SYEAR,';
                $values = '\'' . UserInstitute() . '\',\'' . UserSyear() . '\',';

                $go = false;
                foreach ($columns as $column => $value) {
                    if (trim($value) != '') {
                        $value = paramlib_validation($column, $value);
                        $fields .= $column . ',';
                        $values .= '\'' .singleQuoteReplace("","",$value). '\',';
                        $go = true;
                    }
                }
                $sql .= '(' . substr($fields, 0, -1) . ') values(' . substr($values, 0, -1) . ')';
                if ($go)
                    DBQuery($sql);
            }
        }
    }
    unset($_REQUEST['modfunc']);
}
if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'remove') {

    if (DeletePromptX(_honorRoll)) {

        DBQuery("DELETE FROM honor_roll WHERE id='$_REQUEST[id]'");
    }
}

if (!$_REQUEST['modfunc']) {
    $sql = 'SELECT TITLE,VALUE, id as ID FROM honor_roll WHERE INSTITUTE_ID=\'' . UserInstitute() . '\' AND SYEAR=\'' . UserSyear() . '\' ORDER BY VALUE';
    $functions = array('TITLE' => '_makeTextInput', 'VALUE' => 'makeTextInputt');
    $LO_columns = array('TITLE' =>_honorRoll,
        'VALUE' =>_breakoff,
    );
    $link['add']['html'] = array('TITLE' => _makeTextInput('', 'TITLE'), 'VALUE' => makeTextInputt('', 'VALUE'));
    $link['remove']['link'] = "Modules.php?modname=$_REQUEST[modname]&modfunc=remove";
    $link['remove']['variables'] = array('id' => 'ID');
    $link['add']['html']['remove'] = button('add');
    $LO_ret = DBGet(DBQuery($sql), $functions);
    $LO = DBGet(DBQuery($sql));
    $honor_id_arr = array();
    foreach ($LO as $ti => $td) {
        array_push($honor_id_arr, $td[ID]);
    }
    $honor_id = implode(',', $honor_id_arr);
    $tabs = array();
    $tabs[] = array('title' =>_honorRollSetup);
    echo "<FORM class=\"no-margin\" name=F1 id=F1 action=Modules.php?modname=" . strip_tags(trim($_REQUEST[modname])) . "&modfunc=update method=POST>";
    echo '<input type="hidden" name="h1" id="h1" value="' . $honor_id . '">';

    echo '<div class="panel panel-default">';
    echo '<div class="tabbable"><ul class="nav nav-tabs nav-tabs-bottom no-margin-bottom">' . WrapTabs($tabs, "") . '</ul></div>';
    echo '<div id="div_margin" class="panel-body">';
    echo '<div class="tab-content">';
    echo '<div class="table-responsive">';
    ListOutputMod($LO_ret, $LO_columns, '', '', $link, array(), array('count' =>false, 'download' =>false, 'search' =>false));
    echo '</div>'; //.table-responsive
    $count = count($LO_ret);
    echo '</div>';
    echo '</div>';
    echo '<div class="panel-footer p-r-20 text-right">' . SubmitButton(_save, '', 'id="setupHonorBtn" class="btn btn-primary" onclick="formcheck_honor_roll(this);"') . '</div>';
    echo '</div>';
    echo '</FORM>';
}

function _makeTextInput($value, $name) {
    global $THIS_RET;
    if ($THIS_RET['ID'])
        $id = $THIS_RET['ID'];
    else
        $id = 'new';
    $extra = 'size=30 maxlength=50';

    return TextInput($value, 'values[' . $id . '][' . $name . ']', '', $extra);
}

function makeTextInputt($value, $name) {
    global $THIS_RET;
    if ($THIS_RET['ID'])
        $id = $THIS_RET['ID'];
    else
        $id = 'new';

    return TextInput($value, 'values[' . $id . '][' . $name . ']', '', 'class=form-control');
}

?>
