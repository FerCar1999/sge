<?php 
    
    include($_SERVER['DOCUMENT_ROOT']."/utils/mpdf/mpdf.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/reportes/horarios/classHorarioDocente.php");

    session_start();
    $pk_id = $_SESSION["id_personal"];

    $bloque = 0;
    $diasPasados = 0;

    horariosDocente::horarioDocente($pk_id);
    $tiempos = horariosDocente::getTiempos();
    $lunes = horariosDocente::getDia(1);
    $martes = horariosDocente::getDia(2);
    $miercoles = horariosDocente::getDia(3);
    $jueves = horariosDocente::getDia(4);
    $viernes = horariosDocente::getDia(5);
    $bloques = horariosDocente::formatBloque($lunes,$martes,$miercoles,$jueves,$viernes);


    $mpdf = new mPDF('c','LETTER','','' , 0 , 0 , 0 , 0 , 0 , 0); 
 
    $mpdf->debug = true;

    $mpdf->SetDisplayMode('fullpage');
 
    $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list

    $horarioHtml = 
'<html>
    <head>
        <title>Instituto Técnico Ricaldone | Horario de clases </title>
        
        <style>
            *{
                margin:0;
                padding:0;
                font-family:Arial;
                font-size:10pt;
                color:#000;
                font-style: justify;
            }
            body
            {
                width:100%;
                font-family:Arial;
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
                width: 180mm;
                margin: 0 15mm;
            }
            .page
            {
                height:297mm;
                width:210mm;
                page-break-after:always;
            }
            #wrapper
            {
                width:180mm;
                margin: 0 15mm;
            }
            img{
                margin-top: -17mm;
            }
            /*tbody tr:nth-child(odd) {
                background-color: #C0C0C0;
            }*/
        </style>
    </head>
    <body>

        <div class="content">

            <img src="'.$_SERVER['DOCUMENT_ROOT'].'/media/img/adastra.png" style="width:20mm;padding-left:6mm;padding-top:30mm;">
            <p style="text-align:center;font-weight:bold;padding-top:-16mm;padding-left:10mm;font-size:13pt;">INSTITUTO TÉCNICO RICALDONE</p>';
            $docente = horariosDocente::encabezado($pk_id);
            $horarioHtml .=  
            '<p style="text-align:center;font-weight:bold;padding-top:0mm;padding-left:10mm;font-size:11pt;">HORARIO 2017 - DOCENTE: '.$docente[0].'</p>';
            $horarioHtml .=  
            '<br>
            <table style="width:190mm;margin-left:6mm;border-collapse:collapse;margin-top:10mm;" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <th style="height:8mm;border:solid 1px black;width:20mm;background-color:#eeeeee;">Hora</th>
                    <th style="height:8mm;border:solid 1px black;width:40mm;background-color:#eeeeee;">Lunes</th>
                    <th style="height:8mm;border:solid 1px black;width:40mm;background-color:#eeeeee;">Martes</th>
                    <th style="height:8mm;border:solid 1px black;width:40mm;background-color:#eeeeee;">Miércoles</th>
                    <th style="height:8mm;border:solid 1px black;width:40mm;background-color:#eeeeee;">Jueves</th>
                    <th style="height:8mm;border:solid 1px black;width:40mm;background-color:#eeeeee;">Viernes</th>
                </tr>';
             
            for ($i = 0; $i < count($tiempos); $i++) {
                $minutes = 0;
                if ($i < count($tiempos)-1) {
                    $time1 = strtotime($tiempos[$i][2]);
                    $time2 = strtotime($tiempos[$i+1][1]);
                    $interval = abs($time2 - $time1);
                    $minutes = round($interval/60);
                }
                $horarioHtml .= '
                <tr>';
                if ($i == (count($tiempos)))break;
                $horarioHtml .= '
                    <td style="height:12mm;padding-left:5mm;max-height:10mm;border:solid 1px black;background-color:#eeeeee;"><p><b>'.date("H:i",strtotime($tiempos[$i][1])). ' '. date("H:i",strtotime($tiempos[$i][2])).'</b></p>
                    </td>';
                for ($m = $diasPasados; $m < ($diasPasados + 5); $m++) {
                    $horarioHtml .= '
                    <td style="height:10mm;text-align:center;max-height:10mm;border:solid 1px black;padding-top:3mm;padding-bottom:3mm;font-size:9pt;"><p>'.substr($bloques[$m][2], 0,25).' <br>'.$bloques[$m][3].' <br> '.substr($bloques[$m][4], 0,25).' </p></td>';
                }
                $diasPasados += 5;
             $horarioHtml .= '
                </tr>;';
                if ($minutes >= 15 && $minutes < 30) {
            $horarioHtml .=
                '<tr>
                    <td style="height:7mm;border:solid 1px black;background-color:#eeeeee;text-align:center;" colspan="6"><p><b>RECREO</b></p></td>
                </tr>';
                }
                if ($minutes >= 30) {
            $horarioHtml .=
                '<tr>
                    <td style="height:7mm;border:solid 1px black;background-color:#eeeeee;text-align:center;" colspan="6"><p><b>ALMUERZO</b></p></td>
                </tr>';
                }
            }
            $horarioHtml .= '
            </tbody>
            </table>
            
                
        </div>
        <htmlpagefooter name="footer">
            <div align="center" style="padding-bottom:10mm;"><b>{PAGENO}</b></div>
        </htmlpagefooter>
        <sethtmlpagefooter name="footer" value="on" />
    </body>
</html>';

    //$mpdf->WriteHTML(file_get_contents($_SERVER['DOCUMENT_ROOT']."/privado/php/reportes/conducta.php"));
    $mpdf->WriteHTML($horarioHtml);
    
    $mpdf->Output();
?>