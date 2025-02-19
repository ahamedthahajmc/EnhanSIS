<?php

function GetGrade($grade,$column='TITLE')
{	global $_HaniIMS;
		if($column!='TITLE' && $column!='SHORT_NAME' && $column!='SORT_ORDER')
		$column = 'TITLE';
	if(!$_HaniIMS['GetGrade'])
	{
		$QI=DBQuery('SELECT ID,TITLE,SORT_ORDER,SHORT_NAME FROM institute_gradelevels');
		$_HaniIMS['GetGrade'] = DBGet($QI,array(),array('ID'));
	}
	if($column=='TITLE')
		$extra = '<!-- '.$_HaniIMS['GetGrade'][$grade][1]['SORT_ORDER'].' -->';

	return $extra.$_HaniIMS['GetGrade'][$grade][1][$column];
}
?>