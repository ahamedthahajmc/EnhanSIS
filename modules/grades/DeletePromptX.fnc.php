<?php
 
include('../../RedirectModulesInc.php');
function DeletePromptX($title,$action='delete')
{
	$tmp_REQUEST = $_REQUEST;
	unset($tmp_REQUEST['delete_ok']);
	unset($tmp_REQUEST['delete_cancel']);

	$PHP_tmp_SELF = PreparePHP_SELF($tmp_REQUEST);

	if(!$_REQUEST['delete_ok'] && !$_REQUEST['delete_cancel'])
	{
		//PopTable('header',_confirm." ".(!substr(' ',' '.$action)?$action:''));
		PopTable('header',_confirm." ".(trim($action)!=""?$action:''));
		echo "<CENTER><h4>"._areYouSureYouWantTo." $action that $title?</h4><br><FORM action=$PHP_tmp_SELF METHOD=POST><INPUT type=submit name=delete_ok class=\"btn btn-danger\" value="._ok."> <INPUT type=submit class=\"btn btn-primary\" name=delete_cancel value="._cancel."></FORM></CENTER>";
		PopTable('footer');
		return '';
	}
	if($_REQUEST['delete_ok'])
	{
		unset($_REQUEST['delete_ok']);
		unset($_REQUEST['modfunc']);
		return true;
	}
	unset($_REQUEST['delete_cancel']);
	unset($_REQUEST['modfunc']);
	return false;
}
function UnableDeletePromptX($title)
{
	$tmp_REQUEST = $_REQUEST;	
	$PHP_tmp_SELF = PreparePHP_SELF($tmp_REQUEST);
	if(!$_REQUEST['delete_ok'] && !$_REQUEST['delete_cancel'])
	{
		PopTable('header', _unableToDelete);
		echo "<CENTER><h4>$title</h4><br><FORM action=$PHP_tmp_SELF METHOD=POST><INPUT type=submit class=\"btn btn-primary\" name=delete_cancel value="._cancel."></FORM></CENTER>";
		PopTable('footer');
		return '';
	}
	if($_REQUEST['delete_ok'])
	{
		unset($_REQUEST['delete_ok']);
		unset($_REQUEST['modfunc']);
		return true;
	}
	unset($_REQUEST['delete_cancel']);
	unset($_REQUEST['modfunc']);
	return false;
}
?>