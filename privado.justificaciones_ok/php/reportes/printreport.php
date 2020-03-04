<?php 
	
	/*include($_SERVER['DOCUMENT_ROOT']."/utils/mpdf/mpdf.php");

	$mpdf = new mPDF('c','LETTER','','' , 0 , 0 , 0 , 0 , 0 , 0); 
 
 	$mpdf->debug = true;

	$mpdf->SetDisplayMode('fullpage');
 
	$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list

	$mpdf->WriteHTML(file_get_contents($_SERVER['DOCUMENT_ROOT']."/privado/php/reportes/horarios/reporteHorario.php"));
    
	$mpdf->Output();*/

	session_start();
	echo($_SESSION["id_personal"]);
?>