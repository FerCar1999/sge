<?php 
    date_default_timezone_set('America/El_Salvador');
    ini_set('memory_limit', '-1');
    include($_SERVER['DOCUMENT_ROOT']."/utils/mpdf/mpdf.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/reportes/listas/classListas.php");
    
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

    session_start();

    $mpdf = new mPDF('c',array(215,278),'', '' , 10 , 0 , 10 , 15 , 0 , 0);
 
    $mpdf->debug = true;

    $mpdf->SetDisplayMode('fullpage');
 
    $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list

    $mpdf->setFooter('{PAGENO}');

    //CORRELATIVO
    $n = 0;
    $alumnos = listas::ListaGeneral();

    $html = '<html>
        <head>
            <title>Instituto Técnico Ricaldone | Reporte de conducta </title>
            
            <style>
                *{
                    margin:0;
                    padding:0;
                    font-family:Helvetica;
                    font-size:10pt;
                    color:#000;
                    font-style: justify;
                }
                body
                {
                    width:100%;
                    font-family:Helvetica;
                    font-size:10pt;
                    margin:0;
                    padding:0;
                }
                p
                {
                    margin:0;
                    padding:0;
                }
                .content{
                    width: 190mm;
                }
                .page
                {
                    height:297mm;
                    width:210mm;
                    page-break-after:always;
                }
                #wrapper
                {
                    width:278mm;
                }
                #tablaLista{
                    border-collapse: collapse;
                    width:185mm;
                    font-size:9pt;
                    margin-top: 10mm;
                }
                #tablaLista tr th, #tablaLista tr td{
                    border: 1px solid black;
                    padding-left:1mm;
                }
                #tablaLista tr#headers td{
                    font-weight: bold;
                }
                #tablaLista tr td.noBorderCell{
                    border: no;
                }
                #tablaLista tr td.noBorderTop{
                    border-top:no;
                }
                #tablaLista tr td.tdAlto{
                    border-bottom:0px!important;
                    border-left:0px!important;
                }
                #tablaLista tr td.tdEncabezado{
                    border-bottom:0px!important;
                    border-right:0px;
                }
            </style>
        </head>
        <body>

            <div class="content">
                <img src="'.$_SERVER['DOCUMENT_ROOT'].'/media/img/logo.png" style="width:18mm;float:left">
                <p style="margin-left:4mm;float:right;margin-top:-1mm; font-size:9pt;">
                    Instituto Técnico Ricaldone
                    <br>
                    <br>
                    <b>LISTADO DE ESTUDIANTES ACTIVOS</b>
                </p>
                <p style="margin-left:43mm;margin-top:2mm;"><b>AÑO '.date('Y').'</b></p>
                <table id="tablaLista" cellspacing="0" cellpadding="0">
                    <tr id="headers">
                        <td class="tdEncabezado" style="width:7mm;">No.</td>
                        <td class="tdEncabezado" style="width:17mm;"> Codigo</td>
                        <td class="tdEncabezado" style="text-align:center;width:80mm;">Nombre</td>
                        <td class="tdEncabezado" style="text-align:center;width:35mm;">Grado</td>
                        <td class="tdEncabezado" style="text-align:center;width:47mm;">Especialidad</td>
                        <td class="tdEncabezado" style="text-align:center;width:23mm;">Sección académica</td>
                        <td class="tdEncabezado" style="text-align:center;width:23mm;border-right:1px solid black;">Sección técnica</td>
                    </tr>';
    		foreach ($alumnos as $alumno) {
    			$n++;
    			$html .= '
    				<tr>
                        <td style="border-right:0px;">'.$n.'</td>
                        <td style="border-right:0px;">'.$alumno["codigo"].'</td>
                        <td style="border-right:0px;">'.$alumno["apellidos"].', '.$alumno["nombres"].'</td>
                        <td style="border-right:0px;">'.$alumno["grado"].'</td>
                        <td style="border-right:0px;">'.$alumno["especialidad"].'</td>
                        <td style="border-right:0px;text-align:center;">'.$alumno["seccionAcad"].'</td>
                        <td style="text-align:center;">'.$alumno["seccionTec"].'</td>
                    </tr>';
    		}
            $html.=
            	'</table>
                <div style="width:90mm;float:left;">
                    <p style="font-size:7pt; float:left;">Reporte Generado por: '.mb_strtoupper($_SESSION["apellido"],"utf-8").', '.mb_strtoupper($_SESSION["nombre"],"utf-8").'</p>
                </div>
                <div style="width:58mm;float:left;">
                    <p style="font-size:7pt; float:left;">Fecha: '.date('d-m-Y').'</p>
                </div>
            </div>
        </body>
    </html>';

    $mpdf->WriteHTML($html);
    
    $mpdf->Output();
?>