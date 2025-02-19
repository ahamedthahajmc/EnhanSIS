<?php


function array_rwalk(&$array, $function)
{
	// db_start() is global new mysqli conection it returns the mysqli object
	$connection = db_start();
	if ($connection->connect_errno > 0)
		die('Not connected');
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			array_rwalk($value, $function);
			$array[$key] = $value;
		} else {
			$val = mysqli_real_escape_string($connection, $value);
			$array[$key] = $function($val);
		}
	}
}
