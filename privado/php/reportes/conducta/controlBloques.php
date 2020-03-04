<?php 
    date_default_timezone_set('America/El_Salvador');
    include($_SERVER['DOCUMENT_ROOT']."/utils/mpdf/mpdf.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/reportes/conducta/classReporteBloque.php");
    
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

    session_start();

    $mpdf = new mPDF('c',array(278,215),'', '' , 10 , 0 , 9 , 15 , 0 , 0);
 
    $mpdf->debug = true;

    $mpdf->SetDisplayMode('fullpage');
 
    $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list

    $mpdf->setFooter('{PAGENO}');

    $nombreGrado = "";
    $nombreEspecialidad = "";
    $nombreGrupo = "";
    //CORRELATIVO
    $n = 0;
    $fecha;
    $inicio = $_POST['inicio'];
    if ($inicio === "") {
        $fecha = date("Y-m-d");
    }else{
        $fecha = $inicio;
    }

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
                    width: 250mm;
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
                    width:230mm;
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
                    <b>CONTROL DISCIPLINARIO: INASISTENCIAS POR BLOQUE | FECHA: '.date('d-m-Y',strtotime($fecha)).'</b>
                </p>
                <p style="margin-left:43mm;margin-top:2mm;"><b>AÑO '.date('Y').'</b></p>
                <table id="tablaLista" cellspacing="0" cellpadding="0">
                    <tr id="headers">
                        <td class="tdEncabezado" style="width:7mm;" rowspan=2>No.</td>
                        <td class="tdEncabezado" style="width:17mm;" rowspan=2> Codigo</td>
                        <td class="tdEncabezado" style="text-align:center;width:70mm;" rowspan=2>Nombre</td>
                        <td class="tdEncabezado" style="text-align:center;width:30mm;" rowspan=2>Grado</td>
                        <td class="tdEncabezado" style="text-align:center;width:45mm;" rowspan=2>Especialidad</td>
                        <td class="tdEncabezado" style="text-align:center;width:15mm;" rowspan=2>Grupo</td>
                        <td class="tdEncabezado" style="text-align:center;width:17mm;" rowspan=2>Sección</td>
                        <td style="border-right:0px;text-align:center;" colspan=2>B1</td>
                        <td style="border-right:0px;text-align:center;" colspan=2>B2</td>
                        <td style="border-right:0px;text-align:center;" colspan=2>B3</td>
                        <td style="border-right:0px;text-align:center;" colspan=2>B4</td>
                        <td style="text-align:center;" colspan=3>B5</td>
                    </tr>            
                    <tr id="headers">
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H1</td>   
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H2</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H3</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H4</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H5</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H6</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H7</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H8</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H9</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H10</td>
                        <td style="border-top:0px;text-align:center;width:7.5mm;">H11</td>
                    </tr>
                    '.ControlBloque::verificarAsistencia($fecha).'
                </table>
                <table id="tablaLista" cellspacing="0" cellpadding="0">
                    <tr id="headers">
                        <td style="text-align:center;" colspan=18>SUSPENDIDOS</td>
                    </tr>
                    <tr id="headers">
                        <td class="tdEncabezado" style="width:7mm;" rowspan=2>No.</td>
                        <td class="tdEncabezado" style="width:17mm;" rowspan=2> Codigo</td>
                        <td class="tdEncabezado" style="text-align:center;width:70mm;" rowspan=2>Nombre</td>
                        <td class="tdEncabezado" style="text-align:center;width:30mm;" rowspan=2>Grado</td>
                        <td class="tdEncabezado" style="text-align:center;width:45mm;" rowspan=2>Especialidad</td>
                        <td class="tdEncabezado" style="text-align:center;width:15mm;" rowspan=2>Grupo</td>
                        <td class="tdEncabezado" style="text-align:center;width:17mm;" rowspan=2>Sección</td>
                        <td style="border-right:0px;text-align:center;" colspan=2>B1</td>
                        <td style="border-right:0px;text-align:center;" colspan=2>B2</td>
                        <td style="border-right:0px;text-align:center;" colspan=2>B3</td>
                        <td style="border-right:0px;text-align:center;" colspan=2>B4</td>
                        <td style="text-align:center;" colspan=3>B5</td>
                    </tr>            
                    <tr id="headers">
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H1</td>   
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H2</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H3</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H4</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H5</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H6</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H7</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H8</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H9</td>
                        <td style="border-right:0px;border-top:0px;text-align:center;width:7.5mm;">H10</td>
                        <td style="border-top:0px;text-align:center;width:7.5mm;">H11</td>
                    </tr>
                    '.ControlBloque::GetSuspendidos($fecha).'
                </table>
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