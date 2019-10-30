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
        <title>Instituto Técnico Ricaldone | Reporte inasistencias justificadas </title>
        
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

            <p style="font-weight:bold;font-size:12pt;padding-top:15mm">REPORTE DE INASISTENCIAS JUSTIFICADAS</p>
            <img src="'.$_SERVER['DOCUMENT_ROOT'].'/media/img/logo.png" style="width:20mm;">
            <p style="font-weight:bold;font-size:12pt;margin-top:-5mm;">'.strtoupper($nombreNivel[0]).'</p>
            <p style="font-weight:bold;font-size:12pt;margin-top:-5mm;">DEL '.date('d/m/Y',strtotime($inicio)).' AL '.date('d/m/Y',strtotime($fin)).'</p>
            <p style="text-align:center;font-weight:bold;font-size:12pt;margin-top:10mm">REPORTE ESTADÍSTICO DE '.strtoupper($nombreNivel[0]).' INASISTENCIAS JUSTIFICADAS</p>
            <p style="text-align:center;font-weight:bold;font-size:12pt;">DEL '.date('d/m/Y',strtotime($inicio)).' AL '.date('d/m/Y',strtotime($fin)).'</p>';
            $estudiantes1 = reportesData::inasistenciasT($inicio, $fin, $nivel, 'Justificada');
            $estudiantes2 = reportesData::inasistenciasClasesT($inicio, $fin, $nivel, 'Justificada');

            $studentData = array();
            if (count($estudiantes1) > 0 || count($estudiantes2) > 0) {
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
                $array1 = array();
                $array2 = array();
                $entrada = array();
                foreach($estudiantes1 as $id){
                    array_push($array1, $id[0]);
                    array_push($entrada, $id[0]);
                }
                foreach($estudiantes2 as $id){
                    array_push($array2, $id[0]);
                    array_push($entrada, $id[0]);
                }
                $ccc = 0;
                $resultado = array();
                $intersecc = array_intersect($array1, $array2);
                for ($i=0; $i < count($array1); $i++) { 
                    if((in_array($array1[$i], $array2))){
                        array_push($resultado, $array1[$i]);
                    }
                }
                for ($m=0; $m < count($entrada); $m++) { 
                    if(!(in_array($entrada[$m], $intersecc))){
                        array_push($resultado, $entrada[$m]);
                    }
                }
                for ($i=0; $i < count($resultado); $i++) { 
                    $fechaRepetida = 0;
                    $cantidadTotal = 0;
                    $arrayMismoDia = array();
                    $arrayMismoDiaB = array();
                    $data = reportesData::estudianteJustificado($resultado[$i], $inicio, $fin);
                    $cantC = reportesData::countJustificadoClase($resultado[$i], $inicio, $fin, 'Justificada');
                    $cantT = reportesData::countJustificado($resultado[$i], $inicio, $fin, 'Justificada');
                    foreach($cantC as $keyC1){
                        array_push($arrayMismoDia, date('Y-m-d',strtotime($keyC1[0])));
                    }
                    $arrayMismoDiaB = array_unique($arrayMismoDia);
                    $cantidadTotal = count($arrayMismoDiaB) + count($cantT);
                    foreach ($cantC as $keyC) {
                        foreach ($cantT as $keyT) {
                            if(date('Y-m-d', strtotime($keyC[0])) == date('Y-m-d', strtotime($keyT[0]))){
                                $fechaRepetida++;
                            }
                        }
                    }
                    $suma = $cantidadTotal - $fechaRepetida;
                    array_push($data, $suma);
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