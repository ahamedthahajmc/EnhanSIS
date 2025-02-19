<?php

include('../../../RedirectIncludes.php');
include_once('modules/students/includes/FunctionsInc.php');


echo '<TABLE cellpadding=5 width=100%>';
foreach($fields_RET as $field)
{
	
	switch($field['TYPE'])
	{
		case 'text':
			echo '<TR><TD>';
			echo _makeTextInput('CUSTOM_'.$field['ID'],$field['TITLE'],'',$request);
			echo '</TD></TR>';
			break;

		case 'autos':
			echo '<TR><TD>';
			echo _makeAutoSelectInput('CUSTOM_'.$field['ID'],$field['TITLE'],$request);
			echo '</TD></TR>';
			break;

		case 'edits':
			echo '<TR><TD>';
			echo _makeAutoSelectInput('CUSTOM_'.$field['ID'],$field['TITLE'],$request);
			echo '</TD></TR>';
			break;

		case 'numeric':
			echo '<TR><TD>';
			echo _makeTextInput('CUSTOM_'.$field['ID'],$field['TITLE'],'size=5 maxlength=10',$request);
			echo '</TD></TR>';
			break;

		case 'date':
			echo '<TR><TD>';
			echo DateInputAY($value['CUSTOM_'.$field['ID']],'CUSTOM_'.$field['ID'],$field['ID']);
                        echo  '<input type=hidden name=custom_date_id[] value="'.$field['ID'].'" />';
			echo '</TD></TR>';
			break;

		case 'codeds':
		case 'select':
			echo '<TR><TD>';
			echo _makeSelectInput('CUSTOM_'.$field['ID'],$field['TITLE'],$request);
			echo '</TD></TR>';
			break;

		case 'multiple':
			echo '<TR><TD>';
			echo _makeMultipleInput('CUSTOM_'.$field['ID'],$field['TITLE'],$request);
			echo '</TD></TR>';
			break;

		case 'radio':
			echo '<TR><TD>';
			echo _makeCheckboxInput('CUSTOM_'.$field['ID'],$field['TITLE'],$request);
			echo '</TD></TR>';
			break;

		case'textarea':
			echo '<TR><TD>';
			echo _makeTextareaInput('CUSTOM_'.$field['ID'],$field['TITLE'],$request);
			echo '</TD></TR>';
			break;
	}
}
echo '</TABLE>';

?>