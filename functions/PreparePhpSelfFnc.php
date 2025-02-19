<?php

function PreparePHP_SELF($tmp_REQUEST='')
{
	if(!$tmp_REQUEST)
		$tmp_REQUEST = $_REQUEST;
	foreach($_COOKIE as $key=>$value)
		unset($tmp_REQUEST[$key]);
	
	$PHP_tmp_SELF = 'Modules.php?modname=' . $tmp_REQUEST['modname'];
	
	unset($tmp_REQUEST['modname']);
		if(count($tmp_REQUEST))
	{

        
		foreach($tmp_REQUEST as $key=>$value)
		{
			if(is_array($value))
			{
				foreach($value as $key1=>$value1)
				{
					if(is_array($value1))
					{
						foreach($value1 as $key2=>$value2)
						{	
							if(is_array($value2))
							{
								foreach($value2 as $key3=>$value3)
								{
									$PHP_tmp_SELF .= "&amp;".$key.'['.$key1.']['.$key2.']['.$key3.']='.str_replace('\"','"',$value3);
								}
							}
							else
								$PHP_tmp_SELF .= "&amp;".$key.'['.$key1.']['.$key2.']='.str_replace('\"','"',$value2);
						}
					}
					else
						$PHP_tmp_SELF .= "&amp;".$key.'['.$key1.']='.str_replace('\"','"',$value1);
				}
			}
			else
			{
				if($tmp_REQUEST[$key] != '')
				{
					$PHP_tmp_SELF .= "&amp;" . $key . "=" . str_replace('\"','"',$value);
					
				}
			}
		}
	}
	
	return str_replace(' ','+',$PHP_tmp_SELF);
}

function PreparePHP_SELF1($tmp_REQUEST='')
{
	if(!$tmp_REQUEST)
		$tmp_REQUEST = $_FILES;

	foreach($_COOKIE as $key=>$value)
		unset($tmp_REQUEST[$key]);
	
//	$PHP_tmp_SELF = 'Modules.php?modname=' . $tmp_REQUEST['modname'];
	
	unset($tmp_REQUEST['modname']);
	
	if(count($tmp_REQUEST))
	{

        
		foreach($tmp_REQUEST as $key=>$value)
		{
			if(is_array($value))
			{
				foreach($value as $key1=>$value1)
				{
					if(is_array($value1))
					{
						foreach($value1 as $key2=>$value2)
						{	
							if(is_array($value2))
							{
								foreach($value2 as $key3=>$value3)
								{
									$PHP_tmp_SELF .= "&amp;".$key.'['.$key1.']['.$key2.']['.$key3.']='.str_replace('\"','"',$value3);
								}
							}
							else
								$PHP_tmp_SELF .= "&amp;".$key.'['.$key1.']['.$key2.']='.str_replace('\"','"',$value2);
						}
					}
					else
						$PHP_tmp_SELF .= "&amp;".$key.'['.$key1.']='.str_replace('\"','"',$value1);
				}
			}
			else
			{
				if($tmp_REQUEST[$key] != '')
				{
					$PHP_tmp_SELF .= "&amp;" . $key . "=" . str_replace('\"','"',$value);
					
				}
			}
		}
	}
	
	return str_replace(' ','+',$PHP_tmp_SELF);
}
?>