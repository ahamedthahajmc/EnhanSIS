<?php

function PDFStart($options="--webpage --quiet -t pdf12 --jpeg --no-links --portrait --footer t --header . --left 0.5in --top 0.5in")
{$_REQUEST['HaniIMS_PDF'] = 1;
	$pdfitems['options']=$options;
	ob_start();
	echo "<link rel='stylesheet' type='text/css' href='assets/css/export_print.css'><body style=\" font-family:Arial;\">"; 
	return $pdfitems;
}
?>
