<?php 
    date_default_timezone_set('America/El_Salvador');
    include($_SERVER['DOCUMENT_ROOT']."/utils/mpdf/mpdf.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/reportes/conducta/reporte.php");
    
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

    session_start();

    $mpdf = new mPDF('c',array(278,215),'', '' , 10 , 0 , 9 , 15 , 0 , 0);
 
    $mpdf->debug = true;

    $mpdf->SetDisplayMode('fullpage');
 
    $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list

    $mpdf->setFooter('{PAGENO}');

    $inicio = date('Y/m/d',strtotime($_POST['inicio']));
    $fin = date('Y/m/d',strtotime($_POST['fin']));
    $nivel = $_POST['id_nivel'];
    
    $nombreNivel = reportesData::getNivel($nivel);

    $html = '<html>
    <head>
        <title>Instituto Técnico Ricaldone | Reporte inasistencias injustificadas </title>
        
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
        img{
            float: right;
            margin-top: -10mm;
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
        
    </style>
    </head>
    <body>

        <div class="content">
            <p style="font-weight:bold;font-size:12pt;padding-top:15mm">REPORTE DE INASISTENCIAS A CLASES INJUSTIFICADAS</p>
            <img src="'.$_SERVER['DOCUMENT_ROOT'].'/media/img/logo.png" style="width:20mm;">
            <p style="font-weight:bold;font-size:12pt;margin-top:-5mm;">'.strtoupper($nombreNivel[0]).'</p>
            <p style="font-weight:bold;font-size:12pt;margin-top:-5mm;">DEL '.date('d/m/Y',strtotime($inicio)).' AL '.date('d/m/Y',strtotime($fin)).'</p>
            <p style="text-align:center;font-weight:bold;font-size:12pt;margin-top:10mm">REPORTE ESTADÍSTICO DE '.strtoupper($nombreNivel[0]).' INASISTENCIAS INJUSTIFICADAS</p>
            <p style="text-align:center;font-weight:bold;font-size:12pt;">DEL '.date('d/m/Y',strtotime($inicio)).' AL '.date('d/m/Y',strtotime($fin)).'</p>';
            $estudiantes = reportesData::inasistenciasClasesInjustificadas($inicio, $fin, $nivel);
            $studentData = array();
            if (count($estudiantes)>0) {
                $c = 0;
                if($nivel == 1){
                    $html .= 
                    '<table style="width:190mm;padding-left:10mm;padding-top:10mm;border-collapse:collapse;margin-top:10mm;" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:10mm;">Nº</th>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:40mm;">GRADO</th>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:20mm;">SECCION</th>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:40mm;">CODIGO</th>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:70mm;">APELLIDOS</th>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:70mm;">NOMBRES</th>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:20mm;">REC</th>
                    </tr>';
                }else{
                    $html .= 
                    '<table style="width:190mm;padding-left:10mm;padding-top:10mm;border-collapse:collapse;margin-top:10mm;" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <th style="height:8mm;border:solid 1px black;font-size:12pt;width:10mm;">Nº</th>
                        <th style="height:8mm;border:solid 1px black;font-size:12pt;width:35mm;">GRADO</th>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:50mm;">ESPECIALIDAD</th>
                        <th style="height:8mm;border:solid 1px black;font-size:12pt;width:20mm;">SECCION</th>
                        <th style="height:8mm;border:solid 1px black;font-size:12pt;width:30mm;">CODIGO</th>
                        <th style="height:8mm;border:solid 1px black;font-size:12pt;width:70mm;">APELLIDOS</th>
                        <th style="height:8mm;border:solid 1px black;font-size:12pt;width:70mm;">NOMBRES</th>
                        <th style="height:8mm;border:solid 1px black;font-size:12pt;width:10mm;">REC</th>
                    </tr>';
                }
                foreach($estudiantes as $estudiante){
                    $data = reportesData::estudianteJustificadoClase($estudiante[0], 'Injustificada', $inicio, $fin);
                    array_push($studentData, $data);
                }
                function method1($a,$b) 
                {
                  return ($a[7] >= $b[7]) ? -1 : 1;
                }
                usort($studentData, "method1");
                if($nivel == 1){
                    foreach ($studentData as $student) {
                        $c++;
                        $html .= '
                        <tr>
                            <td style="height:8mm;border:solid 1px black;font-size:10pt;text-align:center;"><p>'.$c.'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:11pt;text-align:center;"><p>'.$student[0].'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:11pt;padding-left:2mm;"><p>'.$student[2].'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:11pt;padding-left:2mm;"><p>'.$student[4].'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:11pt;padding-left:2mm;"><p>'.$student[5].'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:11pt;padding-left:2mm;"><p>'.$student[6].'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:11pt;padding-left:2mm;"><p>'.$student[7].'</p></td>
                        </tr>';
                    }
                }else{
                    foreach ($studentData as $student) {
                        $c++;
                        $html .= '
                        <tr>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;text-align:center;"><p>'.$c.'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;text-align:center;"><p>'.$student[0].'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;text-align:center;"><p>'.$student[1].'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;padding-left:2mm;"><p>'.$student[2].'-'.$student[3].'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;padding-left:2mm;"><p>'.$student[4].'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;padding-left:2mm;"><p>'.$student[5].'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;padding-left:2mm;"><p>'.$student[6].'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;padding-left:2mm;"><p>'.$student[7].'</p></td>
                        </tr>';
                    }
                }
                /*$row['estado']*/
                $html .= '
                    </tbody>
                </table>';
            }

        $html .= '</div>
        

        <!--htmlpagefooter name="footer">
            <div align="center" style="padding-bottom:10mm;"><b>{PAGENO}</b></div>
        </htmlpagefooter>
        <sethtmlpagefooter name="footer" value="on" />
    </body>
</html>';

    $mpdf->WriteHTML($html);
    
    $mpdf->Output();
?>