<?php

function DeCodeds($value,$column)
{	global $_HaniIMS;
	$field = substr($column,7);
	if(!$_HaniIMS['DeCodeds'][$field])
	{
		$select_options = DBGet(DBQuery('SELECT SELECT_OPTIONS FROM custom_fields WHERE ID=\''.$field.'\''));
		$select_options = str_replace("\n","\r",str_replace("\r\n","\r",$select_options[1]['SELECT_OPTIONS']));
		$select_options = explode("\r",$select_options);
		foreach($select_options as $option)
		{
			$option = explode('|',$option);
			if($option[0]!='' && $option[1]!='')
				$options[$option[0]] = $option[1];
		}
		if(count($options))
			$_HaniIMS['DeCodeds'][$field] = $options;
		else
			$_HaniIMS['DeCodeds'][$field] = true;
	}

	if($value!='')
		if($_HaniIMS['DeCodeds'][$field][$value]!='')
			return $_HaniIMS['DeCodeds'][$field][$value];
		else
			return "<FONT color=red>$value</FONT>";
	else
		return '';
}

function cleanParamMod($param)
{
$return='';
  $pa_arr=explode('P',$param);
 foreach($pa_arr as $val)
  {
      
      $return.=chr($val/3);
  }

  return $return;

}
?>
