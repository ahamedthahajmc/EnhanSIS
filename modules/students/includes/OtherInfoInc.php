<?php

include('../../../RedirectIncludes.php');
include_once('modules/students/includes/FunctionsInc.php');
$fields_RET = DBGet(DBQuery('SELECT ID,TITLE,TYPE,SELECT_OPTIONS,DEFAULT_SELECTION,REQUIRED,HIDE FROM custom_fields WHERE SYSTEM_FIELD = \'N\' AND CATEGORY_ID=\'' . $_REQUEST['category_id'] . '\' ORDER BY SORT_ORDER,TITLE'));

if (UserStudentID()) {
    $custom_RET = DBGet(DBQuery('SELECT * FROM students WHERE STUDENT_ID=\'' . UserStudentID() . '\''));
    $value = $custom_RET[1];
}
$num_field_gen = true;
if (count($fields_RET)) {
    echo '<div class="row">';
    $row = 1;
    $i = 1;
    foreach ($fields_RET as $field) {
        if ($row == 3) {
            echo '</div><div class="row">';
            $row = 1;
        }
        if ($field['HIDE'] == 'Y')
            continue;
        if ($field['REQUIRED'] == 'Y') {
            $req = '<font color=red>*</font> ';
        } else {
            $req = '';
        }
        switch ($field['TYPE']) {
            case 'text':
                echo '<div class="col-md-6">';
                echo '<div class="form-group">';
                echo '<label class="control-label col-lg-4 text-right" for="CUSTOM_' . $field['ID'] . '">' . $field['TITLE'] . ' ' . $req . '</Label>';
                echo '<div class="col-lg-8">';
                echo _makeTextInput('CUSTOM_' . $field['ID'], '', '');
                echo '</div>'; //.col-lg-8
                echo '</div>'; //.form-group
                echo '</div>'; //.col-md-6
                break;

            case 'autos':
                echo '<div class="col-md-6">';
                echo '<div class="form-group">';
                echo '<label class="control-label col-lg-4 text-right" for="CUSTOM_' . $field['ID'] . '">' . $field['TITLE'] . ' ' . $req . '</label>';
                echo '<div class="col-lg-8">';
                echo _makeAutoSelectInput('CUSTOM_' . $field['ID'], '');
                echo '</div>'; //.col-lg-8
                echo '</div>'; //.form-group
                echo '</div>'; //.col-md-6
                break;

            case 'edits':
                echo '<div class="col-md-6">';
                echo '<div class="form-group">';
                echo '<label class="control-label col-lg-4 text-right" for="CUSTOM_' . $field['ID'] . '">' . $field['TITLE'] . ' ' . $req . '</label>';
                echo '<div class="col-lg-8">';
                echo _makeAutoSelectInput('CUSTOM_' . $field['ID'], '');
                echo '</div>'; //.col-lg-8
                echo '</div>'; //.form-group
                echo '</div>'; //.col-md-6
                break;

            case 'numeric':
                echo '<div class="col-md-6">';
                echo '<div class="form-group">';
                echo '<label class="control-label col-lg-4 text-right" for="CUSTOM_' . $field['ID'] . '">' . $field['TITLE'] . ' ' . $req . '</label>';
                echo '<div class="col-lg-8">';
                echo _makeTextInput('CUSTOM_' . $field['ID'], '', 'maxlength=10 ' . ($value['CUSTOM_' . $field['ID']] != '' ? 'onkeydown=\"return numberOnly(event);\"' : 'onkeydown="return numberOnly(event);"'));
                echo '</div>'; //.col-lg-8
                echo '</div>'; //.form-group
                echo '</div>'; //.col-md-6
                break;

            case 'date':
                echo '<div class="col-md-6">';
                echo '<div class="form-group">';
                echo '<label class="control-label col-lg-4 text-right" for="CUSTOM_' . $field['ID'] . '">' . $field['TITLE'] . ' ' . $req . '</label>';
                echo '<div class="col-lg-8">';
                echo DateInputAY(($value['CUSTOM_' . $field['ID']] == '0000-00-00' ? '' : $value['CUSTOM_' . $field['ID']]), 'CUSTOM_' . $field['ID'], $field['ID']);
                echo '<input type=hidden name=custom_date_id[] value="' . $field['ID'] . '" />';
                echo '</div>'; //.col-lg-8
                echo '</div>'; //.form-group
                echo '</div>'; //.col-md-6
                break;

            case 'codeds':
            case 'select':
                echo '<div class="col-md-6">';
                echo '<div class="form-group">';
                echo '<label class="control-label col-lg-4 text-right" for="CUSTOM_' . $field['ID'] . '">' . $field['TITLE'] . ' ' . $req . '</label>';
                echo '<div class="col-lg-8">';
                echo _makeSelectInput('CUSTOM_' . $field['ID'], 'class=form-control');
                echo '</div>'; //.col-lg-8
                echo '</div>'; //.form-group
                echo '</div>'; //.col-md-6
                break;

            case 'multiple':
                echo '<div class="col-md-6">';
                echo '<div class="form-group">';
                echo '<label class="control-label col-lg-4 text-right" for="CUSTOM_' . $field['ID'] . '">' . $field['TITLE'] . ' ' . $req . '</label>';
                echo '<div class="col-lg-8">';
                echo _makeMultipleInput('CUSTOM_' . $field['ID'], '');
                echo '</div>'; //.col-lg-8
                echo '</div>'; //.form-group
                echo '</div>'; //.col-md-6
                break;

            case 'radio':
                echo '<div class="col-md-6">';
                echo '<div class="form-group">';
                echo '<label class="control-label col-lg-4 text-right" for="CUSTOM_' . $field['ID'] . '">' . $field['TITLE'] . ' ' . $req . '</label>';
                echo '<div class="col-lg-8">';
                echo _makeCheckboxInput('CUSTOM_' . $field['ID'], '');
                echo '</div>'; //.col-lg-8
                echo '</div>'; //.form-group
                echo '</div>'; //.col-md-6
                break;

            case 'textarea':
                echo '<div class="col-md-6">';
                echo '<div class="form-group">';
                echo '<label class="control-label col-lg-4 text-right" for="CUSTOM_' . $field['ID'] . '">' . $field['TITLE'] . ' ' . $req . '</label>';
                echo '<div class="col-lg-8">';
                echo _makeTextareaInput('CUSTOM_' . $field['ID'], '');
                echo '</div>'; //.col-lg-8
                echo '</div>'; //.form-group
                echo '</div>'; //.col-md-6
                break;
        }
        $row++;
    }
    echo '</div>';
}
