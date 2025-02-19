<?php
function PDFStop($handle)
{	global $OutputType,$htmldocAssetsPath;

	if($OutputType=="PDF")
	{
		$html = ob_get_contents();
		ob_end_clean();
		$html =  '<HTML><BODY>'.$html.'</BODY></HTML>';
		require_once("dompdf/dompdf_config.inc.php");
		
		
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->render();
		$dompdf->stream(ProgramTitle().".pdf", array("Attachment" => 0));
		
	}
	else
	{
	 	
		$html = ob_get_contents();
		ob_end_clean();
		$html =  '<HTML><BODY>'.$html.'</BODY></HTML>';
		echo $html;
	}
}
?>