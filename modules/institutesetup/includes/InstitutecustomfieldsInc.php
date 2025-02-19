<?php


include('../../../RedirectIncludes.php');
include_once('modules/institutesetup/includes/Functions.php');

$fields_RET = DBGet(DBQuery("SELECT ID,TITLE,TYPE,SELECT_OPTIONS,DEFAULT_SELECTION,REQUIRED,HIDE FROM institute_custom_fields WHERE SYSTEM_FIELD = 'N' AND (INSTITUTE_ID='" . UserInstitute() . "' OR INSTITUTE_ID=0) ORDER BY SORT_ORDER,TITLE"));

if (UserInstitute()) {
	$custom_RET = DBGet(DBQuery("SELECT * FROM institutes WHERE ID='" .  UserInstitute() . "'"));
	$value = $custom_RET[1];
}

$num_field_gen = true;

if (count($fields_RET)) {
	$i = 1;
	echo '<div class="row">';
	$row = 1;
	$req_field_ids = '';
	$req_field_titles = '';
	foreach ($fields_RET as $field) {
		if ($row == 3) {
			echo '</div><div class="row">';
			$row = 1;
		}
		if ($field['HIDE'] == 'Y')
			continue;
		if ($field['REQUIRED'] == 'Y') {
			$req = '';
			$req_field_ids .= 'values[CUSTOM_' . $field['ID'] . '],';
			$req_field_titles .= $field['TITLE'] . ',';
		} else {
			$req = '';
		}
		switch ($field['TYPE']) {
			case 'text':
				echo '<div class="col-md-6">';
				echo '<div class="form-group">';
				echo '<label class="col-md-4 control-label text-right">' . $req . $field['TITLE'] . '' . ($field['REQUIRED'] == 'Y' ? '<span class="text-danger"> *</span>' : '') . '</label>';
				echo '<div class="col-md-8">';
				echo _makeTextInputSchl('CUSTOM_' . $field['ID'], '', '');
				echo '</div>'; //.col-md-8
				echo '</div>'; //.form-group
				echo '</div>'; //.col-md-6
				$i++;
				break;

			case 'autos':
				echo '<div class="col-md-6">';
				echo '<div class="form-group">';
				echo '<label class="col-md-4 control-label text-right">' . $req . $field['TITLE'] . '' . ($field['REQUIRED'] == 'Y' ? '<span class="text-danger"> *</span>' : '') . '</label>';
				echo '<div class="col-md-8">';
				echo _makeAutoSelectInputSchl('CUSTOM_' . $field['ID'], '', 'values');
				echo '</div>'; //.col-md-8
				echo '</div>'; //.form-group
				echo '</div>'; //.col-md-6
				$i++;
				break;

			case 'edits':
				echo '<div class="col-md-6">';
				echo '<div class="form-group">';
				echo '<label class="col-md-4 control-label text-right">' . $req . $field['TITLE'] . '' . ($field['REQUIRED'] == 'Y' ? '<span class="text-danger"> *</span>' : '') . '</label>';
				echo '<div class="col-md-8">';
				echo _makeAutoSelectInputSchl('CUSTOM_' . $field['ID'], '', 'values');
				echo '</div>'; //.col-md-8
				echo '</div>'; //.form-group
				echo '</div>'; //.col-md-6
				$i++;
				break;

			case 'numeric':
				echo '<div class="col-md-6">';
				echo '<div class="form-group">';
				echo '<label class="col-md-4 control-label text-right">' . $req . $field['TITLE'] . '' . ($field['REQUIRED'] == 'Y' ? '<span class="text-danger"> *</span>' : '') . '</label>';
				echo '<div class="col-md-8">';
				echo _makeTextInputSchl('CUSTOM_' . $field['ID'], '', 'size=5 maxlength=10 ' . ($value['CUSTOM_' . $field['ID']] != '' ? 'onkeydown=\"return numberOnly(event);\"' : 'onkeydown="return numberOnly(event);"'));
				echo '</div>'; //.col-md-8
				echo '</div>'; //.form-group
				echo '</div>'; //.col-md-6
				$i++;
				break;

			case 'date':
				echo '<div class="col-md-6">';
				echo '<div class="form-group">';
				echo '<label class="col-md-4 control-label text-right">' . $req . $field['TITLE'] . '' . ($field['REQUIRED'] == 'Y' ? '<span class="text-danger"> *</span>' : '') . '</label>';
				echo '<div class="col-md-8">';
				echo _makeDateInput_modSchl('CUSTOM_' . $field['ID'], '', $field['ID']);
				echo '</div>'; //.col-md-8
				echo '</div>'; //.form-group
				echo '</div>'; //.col-md-6
				$i++;
				break;

			case 'codeds':
			case 'select':
				echo '<div class="col-md-6">';
				echo '<div class="form-group">';
				echo '<label class="col-md-4 control-label text-right">' . $req . $field['TITLE'] . '' . ($field['REQUIRED'] == 'Y' ? '<span class="text-danger"> *</span>' : '') . '</label>';
				echo '<div class="col-md-8">';
				echo _makeSelectInputSchl('CUSTOM_' . $field['ID'], '', 'values');
				echo '</div>'; //.col-md-8
				echo '</div>'; //.form-group
				echo '</div>'; //.col-md-6
				$i++;
				break;

			case 'multiple':
				echo '<div class="col-md-6">';
				echo '<div class="form-group">';
				echo '<label class="col-md-4 control-label text-right">' . $req . $field['TITLE'] . '' . ($field['REQUIRED'] == 'Y' ? '<span class="text-danger"> *</span>' : '') . '</label>';
				echo '<div class="col-md-8">';
				echo _makeMultipleInputSchl('CUSTOM_' . $field['ID'], '', 'values');
				echo '</div>'; //.col-md-8
				echo '</div>'; //.form-group
				echo '</div>'; //.col-md-6
				$i++;
				break;

			case 'radio':
				echo '<div class="col-md-6">';
				echo '<div class="form-group">';
				echo '<label class="col-md-4 control-label text-right">' . $req . $field['TITLE'] . '' . ($field['REQUIRED'] == 'Y' ? '<span class="text-danger"> *</span>' : '') . '</label>';
				echo '<div class="col-md-8">';
				echo _makeCheckboxInputSchl('CUSTOM_' . $field['ID'], '');
				echo '</div>'; //.col-md-8
				echo '</div>'; //.form-group
				echo '</div>'; //.col-md-6
				$i++;
				break;

			case 'textarea':
				echo '<div class="col-md-6">';
				echo '<div class="form-group">';
				echo '<label class="col-md-4 control-label text-right">' . $req . $field['TITLE'] . '' . ($field['REQUIRED'] == 'Y' ? '<span class="text-danger"> *</span>' : '') . '</label>';
				echo '<div class="col-md-8">';
				echo _makeTextareaInputSchl('CUSTOM_' . $field['ID'], '');
				echo '</div>'; //.col-md-8
				echo '</div>'; //.form-group
				echo '</div>'; //.col-md-6
				break;
		}
		$row++;
	}
	$req_field_ids = rtrim($req_field_ids, ',');
	$req_field_titles = rtrim($req_field_titles, ',');

	echo '<input id="custom_sch_field_ids" type="hidden" value="' . $req_field_ids . '">';
	echo '<input id="custom_sch_field_titles" type="hidden" value="' . $req_field_titles . '">';
}
