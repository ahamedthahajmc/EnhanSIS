<?php

function Buttons($value1,$value2='',$value1Extra=false)
{
	$buttons = '<INPUT type=SUBMIT class="btn btn-primary" '.$value1Extra.' value="'.$value1.'"> &nbsp;';
	if($value2!='') 
		$buttons .= ' <INPUT type=RESET class="btn btn-default" value="'.$value2.'">';
	
	return $buttons;
}
?>