<?php

function SubmitButton($value=_submit,$name='',$options='')
{
	if(AllowEdit() || $_SESSION['take_mssn_attn'])
		return "<INPUT type=submit value='$value'".($name?" name='$name'":'').($options?' '.$options:'').">";
	else
		return '';
}
function SubmitButtonModal($value=_submit,$name='',$options='')
{
	
		return "<INPUT type=submit value='$value'".($name?" name='$name'":'').($options?' '.$options:'').">";
	
}

function ResetButton($value=_reset,$options='')
{
	if(AllowEdit())
		return "<INPUT type=reset value='$value'".($options?' '.$options:'').'>';
	else
		return '';
}
?>