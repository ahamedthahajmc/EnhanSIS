<?php

function DrawHeaderHome($left='',$right='',$center='')
{	global $_HaniIMS;
	

	if(!isset($_HaniIMS['DrawHeader']))
        {
		$_HaniIMS['DrawHeader'] = '';
        }
	if($_HaniIMS['DrawHeader'] == '')
	{
		$attribute = 'B';
		$font_color = '436477';
	}
	else
	{
		$attribute = 'FONT size=-1';
		$font_color = '000000';
	}
        
	if($left)
		echo $left;
	if($center)
		echo $center;
	if($right)
		echo $right;
        

	/*if($_HaniIMS['DrawHeaderHome'] == '' && !$_REQUEST['HaniIMS_PDF'])
		$_HaniIMS['DrawHeaderHome'] = ' style="border:0;border-style: none none none none;"';
	else
		$_HaniIMS['DrawHeaderHome'] = '';*/
}
?>