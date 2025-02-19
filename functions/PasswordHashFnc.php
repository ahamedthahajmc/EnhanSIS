<?php


function VerifyHash($pwd,$hash)
{
	$details = password_verify($pwd,$hash);
	if(empty($details)) { $details=0; }
	return $details;
}

function GenerateNewHash($pwd)
{
	$newpassword = password_hash($pwd,PASSWORD_DEFAULT);
	return $newpassword; 
}
?>