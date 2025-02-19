<?php

function decrypter($string)
{
	$encrypt_method = "AES-256-CBC";
	$secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
	$secret_iv = '5fgf5HJ5g27'; // user define secret key
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	return $output;
}

function HackingLog()
{
	$HaniIMSNotifyAddress = '';
	$HaniIMSVersion = '8.0';

	echo "" . _youReNotAllowedToUseThisProgram . "! " . _thisAttemptedViolationHasBeenLoggedAndYourIpAddressWasCaptured . ".";
	Warehouse('footer');

	if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	if ($HaniIMSNotifyAddress)
		mail($HaniIMSNotifyAddress, 'HACKING ATTEMPT', "INSERT INTO hacking_log (HOST_NAME,IP_ADDRESS,LOGIN_DATE,VERSION,PHP_SELF,DOCUMENT_ROOT,SCRIPT_NAME,MODNAME,USERNAME) values('$_SERVER[SERVER_NAME]','$ip','" . date('Y-m-d') . "','$HaniIMSVersion','$_SERVER[PHP_SELF]','$_SERVER[DOCUMENT_ROOT]','$_SERVER[SCRIPT_NAME]','$_REQUEST[modname]','" . User('USERNAME') . "')");

	if (false && function_exists('query')) {

		if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$access = 'c09heEQ1czZmcng0eG14dk4va0l0dz09';
		$url = 'dkZDQUNOTUhXaWF6dkYwNTlDNEpSQT09';


		$connection = new mysqli(decrypter($url), decrypter($access), decrypter($access), decrypter($access));
		$connection->query("INSERT INTO hacking_log (HOST_NAME,IP_ADDRESS,LOGIN_DATE,VERSION,PHP_SELF,DOCUMENT_ROOT,SCRIPT_NAME,MODNAME,USERNAME) values('$_SERVER[SERVER_NAME]','$ip','" . date('Y-m-d') . "','$HaniIMSVersion','$_SERVER[PHP_SELF]','$_SERVER[DOCUMENT_ROOT]','$_SERVER[SCRIPT_NAME]','" . optional_param('modname', '', PARAM_CLEAN) . "','" . User('USERNAME') . "')");
		mysqli_close($connection);
	}
}
