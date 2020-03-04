<?php 
    
    include($_SERVER['DOCUMENT_ROOT']."/utils/mpdf/mpdf.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/publico/php/reportes/classHorarioAlumno.php");

    session_start();
    //GRUPO TECNICO, GRUPO ACADEMICO, GRADO, SECCION
    $alumno = horariosAlumno::getAlumno($_SESSION["codigo"]);
    foreach ($alumno as $value) {
        horariosAlumno::horarioAlumno("",$value[0],$value[2],$value[3],$value[4],$value[6]);
    }

    $tiempos = horariosAlumno::getTiempos();
    $lunes = horariosAlumno::getDia(1);
    $martes = horariosAlumno::getDia(2);
    $miercoles = horariosAlumno::getDia(3);
    $jueves = horariosAlumno::getDia(4);
    $viernes = horariosAlumno::getDia(5);
    $bloques = horariosAlumno::formatBloque($lunes,$martes,$miercoles,$jueves,$viernes);
    $diasPasados = 0;


    $mpdf = new mPDF('c','LETTER','','' , 0 , 0 , 0 , 0 , 0 , 0); 
 
    $mpdf->debug = true;

    $mpdf->SetDisplayMode('fullpage');
 
    $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list

    $horarioHtml = 
'<html>
    <head>
        <title>Kinder Pedro Ricaldone | Horario de clases </title>
        
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
        </style>
    </head>
    <body>

        <div class="content">

            <img src="'.$_SERVER['DOCUMENT_ROOT'].'/media/img/logo.png" style="width:20mm;padding-left:6mm;padding-top:30mm;">
            <p style="text-align:center;font-weight:bold;padding-top:-16mm;padding-left:10mm;font-size:13pt;">KINDER PEDRO RICALDONE</p>
            <p style="text-align:center;font-weight:bold;padding-left:10mm;font-size:13pt;">'.$alumno[0][5].'</p>
            <br>
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
             
            /*for ($i = 0; $i < count($tiempos); $i++) {
                $horarioHtml .= '
                <tr>';
                if ($i == (count($tiempos))) {
                    break;
                }
                $horarioHtml .= '
                    <td style="height:12mm;padding-left:5mm;max-height:10mm;border:solid 1px black;background-color:#eeeeee;"><p><b>'.date("H:i",strtotime($tiempos[$i][1])). ' '. date("H:i",strtotime($tiempos[$i][2])).'</b></p>
                    </td>';
                for ($m = $diasPasados; $m < ($diasPasados + 5); $m++) {
                    $horarioHtml .= '
                    <td style="height:10mm;text-align:center;max-height:10mm;border:solid 1px black;padding-top:3mm;padding-bottom:3mm;font-size:9pt;"><p>'.substr($bloques[$m][2], 0,25).' <br>'.substr($bloques[$m][3],0,25).' <br> '.substr($bloques[$m][4], 0,25).' </p></td>';
                }
                $diasPasados += 5;
             $horarioHtml .= '
                </tr>;';
                if ($i == 1 || $i == 3 || $i == 7) {
            $horarioHtml .=
                '<tr>
                    <td style="height:7mm;border:solid 1px black;background-color:#eeeeee;text-align:center;" colspan="6"><p><b>RECREO</b></p></td>
                </tr>';
                }
                if ($i == 5) {
            $horarioHtml .=
                '<tr>
                    <td style="height:7mm;border:solid 1px black;background-color:#eeeeee;text-align:center;" colspan="6"><p><b>ALMUERZO</b></p></td>
                </tr>';
                }
            }*/
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
                if ($minutes == 15 || $minutes == 20) {
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
    $mpdf->WriteHTML($horarioHtml);
    
    $mpdf->Output();
?>