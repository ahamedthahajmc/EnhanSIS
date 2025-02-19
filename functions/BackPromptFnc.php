<?php

function BackPrompt($message)
{
	echo "<SCRIPT language=javascript>alert(\"$message\");window.history.back();</SCRIPT>";
	exit();
}
?>