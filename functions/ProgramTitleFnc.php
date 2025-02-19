<?php

function ProgramTitle()
{	global $_HaniIMS;
	if(!$_HaniIMS['Menu'])
		include 'Menu.php';
	foreach($_HaniIMS['Menu'] as $modcat=>$programs)
	{
		if(count($programs))
		{
			foreach($programs as $program=>$title)
			{
				if($_REQUEST['modname']==$program)
					return $title;
			}
		}
	}
	return 'HaniIMS';
}
?>