<?php

function Config($item)
{	global $_HaniIMS,$HaniIMSTitle,$DefaultSyear;

	if(!$_HaniIMS['Config'])
	{
		$_HaniIMS['Config'][1]['TITLE'] = $HaniIMSTitle;
		$_HaniIMS['Config'][1]['SYEAR'] = $DefaultSyear;
	}
	return $_HaniIMS['Config'][1][$item];
}
?>