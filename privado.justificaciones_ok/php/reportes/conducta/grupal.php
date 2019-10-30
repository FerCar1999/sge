<?php 
	
    include($_SERVER['DOCUMENT_ROOT']."/utils/mpdf/mpdf.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/reportes/conducta/reporte.php");

    session_start();
    $pk_id = $_SESSION["id_personal"];

    $alumnos = reportesData::alumnosGuia($pk_id);

    $mpdf = new mPDF('c','LETTER','','' , 0 , 0 , 10 , 10 , 0 , 5);
 
    $mpdf->debug = true;

    $mpdf->SetDisplayMode('fullpage');

    $mpdf->setFooter('{PAGENO}');
 
    $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list

    $k = 0;

    foreach ($alumnos as $alumno){
    	$sintardeclase = true;
	    $sintardeinstitucion = true;
	    $sinenfermeria = true;
	    $sinobservaciones = true;
	    $sincodigosn = true;
	    $sinpositivos = true;
        $sininasistenciat = true;
    	$html = 
		'<html>
        	<head>
            <title>Instituto Técnico Ricaldone | Reporte de conducta </title>
            
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

                <p style="text-align:center;font-weight:bold;padding-top:20mm;padding-left:10mm;font-size:13pt;">INSTITUTO TÉCNICO RICALDONE</p>
                <img src="'.$_SERVER['DOCUMENT_ROOT'].'/media/img/logo.png" style="width:23mm;padding-left:10mm;padding-top:5mm;">';


                $encabezado = reportesData::encabezado($alumno[0]);
                $etapas = reportesData::getEtapas($encabezado['nivel']);

                $html .= '<p style="padding-left:10mm;padding-top:10mm;font-size:11pt;"><b>Alumno: </b>'.$encabezado['nombre'].'</p>
                <p style="padding-left:10mm;padding-top:2mm;font-size:11pt;"><b>Código: </b>'.$encabezado['codigo'].'</p>
                <p style="padding-left:10mm;padding-top:2mm;font-size:11pt;"><b>Curso: </b>'.$encabezado['especialidad'].'</p>';
                
                if (file_exists($_SERVER['DOCUMENT_ROOT'].'/media/img/alumnos/'.$alumno[0].'.JPG')) {
                    $html .= '
                    <img src="'.$_SERVER['DOCUMENT_ROOT'].'/media/img/alumnos/'.$alumno[0].'.JPG" style="width:100%;height:auto;padding-left:155mm;padding-top:-15mm;position:relative">';
                }else{
                    $html .= '
                    <img src="'.$_SERVER['DOCUMENT_ROOT'].'/media/img/user_default.jpg" style="width:100%;height:auto;padding-left:155mm;padding-top:-15mm;position:relative">';
                }
                

            $i = 0;
            $p = 0;
            $c = 0;
            ///LEGADAS TARDE A LA INSTITUCION
            foreach($etapas as $row){
                $llegadasTardeInstitucion = reportesData::llegadasTardeInstitucion($alumno[0],$row["inicio"],$row["fin"]);
                if (count($llegadasTardeInstitucion)>0) {
                    $c = 0;
                    $sintardeinstitucion = false;
                    if($i == 0 || $p == 0){
                    $html .= 
                    '
                    <h3 style="text-align:center;padding-top:5mm;padding-bottom:5mm;">Llegadas tarde a la insitución</h3>
                    <table style="width:190mm;padding-left:10mm;padding-top:10mm;border-collapse: collapse;" cellspacing="0" cellpadding="0">
                    <tbody>';
                    $p++;
                    }
                    $html .= '
                    <tr>
                        <th style="height:8mm;border:solid 1px black;" colspan=3>'.$row["nombre"]." ".$row["inicio"]." - ".$row["fin"].'</th>
                    </tr>
                    <tr>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:7mm;">Nº</th>
                        <th style="height:8mm;border:solid 1px black;width:38mm;">Fecha y hora</th>
                        <th style="height:8mm;border:solid 1px black;width:117mm;">Tipo</th>
                        <!--th style="height:8mm;border:solid 1px black;width:35mm;">Estado</th-->
                    </tr>';
                    foreach ($llegadasTardeInstitucion as $row) {
                        $c++;
                        $html .= '
                        <tr>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;text-align:center;"><p>'.
                            $c.'</p></td>
                            <td style="height:8mm;padding-left:2mm;border:solid 1px black;"><p>'.$row['fecha'].'</p></td>
                            <td style="height:8mm;text-align:center;border:solid 1px black;"><p>Llegada tarde a la institución</p></td>
                            <!--td style="height:8mm;text-align:center;border:solid 1px black;"><p>'.$row['estado'].'</p></td-->
                        </tr>';
                    }
                }
                if ((count($etapas)-1) == $i) {
                    $html .= '</tbody>
                </table>';
                }
                $i++;
            }
            if ($sintardeinstitucion) {
                $html .= '<div align="center" style="padding-top:10mm;"><h2 style="text-align:center;">SIN LLEGADAS TARDE A LA INSTITUCIÓN</h2></div>';
            }

            $i = 0;
            $p = 0;
            $c = 0;
            ///LEGADAS TARDE A CLASE
            foreach($etapas as $row){
                $llegadasTardeClase = reportesData::llegadasTardeClase($alumno[0],$row["inicio"],$row["fin"]);
                if (count($llegadasTardeClase)>0) {
                    $sintardeclase = false;
                    if ($i == 0 || $p == 0) {
                    $html .= 
                    '<h3 style="text-align:center;padding-top:7mm;padding-bottom:5mm;">Llegadas tarde a clase</h3>
                    <table style="width:190mm;padding-left:10mm;padding-top:10mm;border-collapse: collapse;" cellspacing="0" cellpadding="0">
                    <tbody>';   
                    $p++;
                    }
                    $html .= '
                    <tr>
                        <th style="height:8mm;border:solid 1px black;" colspan=4>'.$row["nombre"]." ".$row["inicio"]." - ".$row["fin"].'</th>
                    </tr>
                    <tr>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:7mm;">Nº</th>
                        <th style="height:8mm;border:solid 1px black;width:25mm;border:solid 1px black;">Fecha</th>
                        <th style="height:8mm;border:solid 1px black;width:117mm;border:solid 1px black;">Tipo</th>
                        <!--th style="height:8mm;border:solid 1px black;width:35mm;border:solid 1px black;">Estado</th-->
                    </tr>';
                    foreach ($llegadasTardeClase as $row) {
                        $c++;
                        $html .= '
                        <tr>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;text-align:center;"><p>'.
                            $c.'</p></td>
                            <td style="height:8mm;text-align:center;border:solid 1px black;"><p>'.date("d-m-Y",strtotime($row['fecha'])).'</p></td>
                            <td style="height:8mm;text-align:center;border:solid 1px black;"><p>Llegada tarde a clase: <b>'.$row['asignatura'].'</b></p></td>
                            <!--td style="height:8mm;text-align:center;border:solid 1px black;"><p>'.$row['estado'].'</p></td-->
                        </tr>';
                    }
                }
                if ((count($etapas)-1) == $i) {
                    $html .= '</tbody>
                </table>';
                }
                $i++;
            }
            if ($sintardeclase) {
            $html .= '<div align="center"><h2 style="text-align:center;">SIN LLEGADAS TARDE A CLASE</h2></div>';
            }

            $i = 0;
            $p = 0;
            $c = 0;
            //CODIGOS POSITIVOS
            foreach($etapas as $row){
                $codigosPositivos = reportesData::codigosPositivos($alumno[0],$row["inicio"],$row["fin"]);
                if (count($codigosPositivos)>0) {
                    $sinpositivos = false;
                    if($i == 0 || $p == 0){                    
                    $html .= '
                    <h3 style="text-align:center;padding-top:7mm;padding-bottom:5mm;">Códigos positivos</h3>
                    <table style="width:190mm;padding-left:10mm;padding-top:10mm;border-collapse: collapse;" cellspacing="0" cellpadding="0">
                    <tbody>';
                    $p++;
                    }
                    $html .= '
                    <tr>
                        <th style="height:8mm;border:solid 1px black;" colspan=5>'.$row["nombre"]." ".$row["inicio"]." - ".$row["fin"].'</th>
                    </tr>
                    <tr>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:7mm;">Nº</th>
                        <th style="height:8mm;border:solid 1px black;width:25mm;font-size:12pt;">Fecha</th>
                        <th style="height:8mm;border:solid 1px black;width:110mm;font-size:12pt;">Código</th>
                        <th style="height:8mm;border:solid 1px black;width:80mm;font-size:12pt;">Docente</th>
                        <th style="height:8mm;border:solid 1px black;width:25mm;font-size:12pt;">Tipo</th>
                    </tr>';
                    foreach ($codigosPositivos as $row) {
                        $c++;
                        $html .= '
                        <tr>
                        <td style="height:8mm;border:solid 1px black;font-size:9pt;text-align:center;"><p>'.
                            $c.'</p></td>
                        <td style="height:8mm;padding-left:2mm;padding-top:3mm;border:solid 1px black;"><p style="font-size:10pt!important">'.date("d-m-Y",strtotime($row['fecha'])).'</p></td>
                        <td style="height:8mm;text-align:left;padding-top:3mm;padding-left:3mm;border:solid 1px black;"><p style="font-size:11pt!important">'.$row['codigo'].'</p></td>
                        <td style="height:8mm;text-align:left;padding-top:3mm;padding-left:3mm;border:solid 1px black;"><p style="font-size:11pt!important">'.$row['docente'].'</p></td>
                        <td style="height:8mm;text-align:center;padding-top:3mm;border:solid 1px black;"><p style="font-size:11pt!important">'.$row['horario'].'</p></td>
                    </tr>';
                    }
                }
                if ((count($etapas)-1) == $i) {
                    $html .= '</tbody>
                </table>';
                }
                $i++;
            }
            if ($sinpositivos) {
                $html .= '<div align="center"><h2 style="text-align:center;">SIN CÓDIGOS POSITIVOS</h2></div>';
            }
            $i = 0;
            $p = 0;
            $c = 0;
            //CODIGOS NEGATIVOS
            foreach($etapas as $row){
                $codigosNegativos = reportesData::codigosNegativos($alumno[0],$row["inicio"],$row["fin"]);
                if (count($codigosNegativos)>0) {
                    $sincodigosn = false;
                    if($i == 0 || $p == 0){
                    $html .= '
                    <h3 style="text-align:center;padding-top:7mm;padding-bottom:5mm;">Códigos a mejorar</h3>
                    <table style="width:190mm;padding-left:10mm;padding-top:10mm;border-collapse: collapse;" cellspacing="0" cellpadding="0">
                        <tbody>';
                        $p++;
                    }
                    $html .= '
                        <tr>
                            <th style="height:8mm;border:solid 1px black;" colspan=6>'.$row["nombre"]." ".$row["inicio"]." - ".$row["fin"].'</th>
                        </tr>
                        <tr>
                            <th style="height:8mm;border:solid 1px black;font-size:11pt;width:7mm;">Nº</th>
                            <th style="height:8mm;border:solid 1px black;width:25mm;font-size:12pt;">Fecha</th>
                            <th style="height:8mm;border:solid 1px black;width:110mm;font-size:12pt;">Código</th>
                            <th style="height:8mm;border:solid 1px black;width:70mm;font-size:12pt;">Docente</th>
                            <th style="height:8mm;border:solid 1px black;width:25mm;font-size:12pt;">Tipo</th>
                            <th style="height:8mm;border:solid 1px black;width:25mm;font-size:12pt;">Nivel</th>
                        </tr>';
                    foreach ($codigosNegativos as $row) {
                        $c++;
                        $html .= '
                    <tr>
                        <td style="height:8mm;border:solid 1px black;font-size:9pt;text-align:center;"><p>'.
                            $c.'</p></td>
                        <td style="border:solid 1px black;height:8mm;padding-left:2mm;padding-top:3mm;"><p style="font-size:10pt!important">'.date("d-m-Y",strtotime($row['fecha'])).'</p></td>
                        <td style="border:solid 1px black;height:8mm;text-align:left;padding-top:3mm;padding-left:3mm;"><p style="font-size:11pt!important">'.$row['codigo'].'</p></td>
                        <td style="border:solid 1px black;height:8mm;text-align:left;padding-top:3mm;padding-left:3mm;"><p style="font-size:11pt!important">'.$row['docente'].'</p></td>
                        <td style="border:solid 1px black;height:8mm;text-align:center;padding-top:3mm;"><p style="font-size:11pt!important">'.$row['horario'].'</p></td>
                        <td style="border:solid 1px black;height:8mm;text-align:center;padding-top:3mm;"><p style="font-size:11pt!important">'.$row['tipo'].'</p></td>
                    </tr>';
                    }
                }
                if ((count($etapas)-1) == $i) {
                    $html .= '</tbody>
                    </table>';
                }
                $i++;
            }
            if ($sincodigosn) {
                $html .= '<div align="center"><h2 style="text-align:center;">SIN CÓDIGOS A MEJORAR</h2></div>';
            } 
            $i = 0;
            $p = 0;
            $c = 0;
            foreach($etapas as $row){
                //OBSERVACIONES
                $observaciones = reportesData::observaciones($alumno[0],$row["inicio"],$row["fin"]);
                if (count($observaciones)>0) {
                    $sinobservaciones = false;
                    if($i == 0 || $p == 0){
                    $html .= '
                    <h3 style="text-align:center;padding-top:7mm;padding-bottom:5mm;">Observaciones</h3>
                    <table style="width:190mm;padding-left:10mm;padding-top:10mm;border-collapse: collapse;" cellspacing="0" cellpadding="0">
                    <tbody>';   
                    $p++;
                    }
                    $html .= '
                    <tr>
                        <th style="height:8mm;border:solid 1px black;" colspan=4>'.$row["nombre"]." ".$row["inicio"]." - ".$row["fin"].'</th>
                    </tr>
                    <tr>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:7mm;">Nº</th>
                        <th style="height:8mm;border:solid 1px black;width:25mm;">Fecha</th>
                        <th style="height:8mm;border:solid 1px black;width:110mm;">Observación</th>
                        <th style="height:8mm;border:solid 1px black;width:55mm;">Docente</th>
                    </tr>';
                    foreach ($observaciones as $row) {
                        $c++;
                        $html .= '
                    <tr>
                        <td style="height:8mm;border:solid 1px black;font-size:9pt;text-align:center;"><p>'.
                            $c.'</p></td>
                        <td style="border:solid 1px black;height:8mm;padding-left:2mm;padding-top:3mm;"><p>'.date("d-m-Y",strtotime($row['fecha'])).'</p></td>
                        <td style="border:solid 1px black;height:8mm;text-align:justify;padding-top:3mm;padding-left:3mm;padding-right:3mm;"><p>'.$row['observacion'].'</p></td>
                        <td style="border:solid 1px black;height:8mm;text-align:center;padding-top:3mm;padding-left:3mm;"><p style="font-size:10pt!important;">'.$row['docente'].'</p></td>
                    </tr>';
                    }
                }
                if ((count($etapas)-1) == $i) {
                    $html .= '</tbody>
                </table>';
                }
                $i++;
            }
            if ($sinobservaciones) {
                $html .= '<div align="center"><h2 style="text-align:center;">SIN OBSERVACIONES</h2></div>';
            }
            $i = 0;
            $p = 0;
            $c = 0;
            foreach($etapas as $row){
                //VISITAS A ENFERMERIA
                $enfermeria = reportesData::enfermeria($alumno[0],$row["inicio"],$row["fin"]);
                if (count($enfermeria)>0) {
                    $sinenfermeria = false;
                    if($i == 0 || $p == 0){
                        $html .= '
                        <h3 style="text-align:center;padding-top:7mm;padding-bottom:5mm;">Visitas a enfermería</h3>
                        <table style="width:190mm;padding-left:10mm;padding-top:10mm;border-collapse: collapse;" cellspacing="0" cellpadding="0">
                        <tbody>';
                        $p++;
                    }
                    $html .= '
                    <tr>
                        <th style="height:8mm;border:solid 1px black;" colspan=3>'.$row["nombre"]." ".$row["inicio"]." - ".$row["fin"].'</th>
                    </tr>
                    <tr>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:7mm;">Nº</th>
                        <th style="height:8mm;border:solid 1px black;width:25mm;">Fecha</th>
                        <th style="height:8mm;border:solid 1px black;width:110mm;">Observación</th>
                        
                    </tr>';
                    
                    foreach ($enfermeria as $row) {
                        $c++;
                        $html.= '
                    <tr>
                        <td style="height:8mm;border:solid 1px black;font-size:9pt;text-align:center;"><p>'.
                            $c.'</p></td>
                        <td style="border:solid 1px black;height:8mm;padding-left:2mm;padding-top:3mm;"><p>'.date("d-m-Y",strtotime($row['fecha'])).'</p></td>
                        <td style="border:solid 1px black;height:8mm;text-align:justify;padding-top:3mm;padding-left:3mm;padding-right:3mm;"><p>'.$row['observacion'].'</p></td>
                    </tr>';
                    }
                }
                if ((count($etapas)-1) == $i) {
                    $html .= '</tbody>
                </table>';
                }
                $i++;
            }
            if ($sinenfermeria) {
                $html .= '<div align="center"><h2 style="text-align:center;">SIN VISITAS A ENFERMERIA</h2></div>';
            }
            //INASISTENCIAS A LA INSTITUCIÓN
            $i = 0;
            $p = 0;
            $c = 0;
            foreach($etapas as $row){
                $td_asist = '';
                /*$inasistenciasinst = reportesData::inasistenciasAlumnoInst($codigo,$row["inicio"],$row["fin"]);
                foreach ($inasistenciasinst as $asist) {
                    //if (verificarInasistenciaTotal($codigo,$asist['fecha'])) {
                        $sininasistenciat = false;
                        $c++;
                        $td_asist .= '
                        <tr>
                            <td style="height:8mm;padding-left:2mm;border:solid 1px black;"><p>'.$c.'</p></td>
                            <td style="height:8mm;padding-left:2mm;border:solid 1px black;"><p>'.$asist['fecha'].'</p></td>
                            <td style="height:8mm;text-align:center;border:solid 1px black;"><p>Inasistencia total a la institución</p></td>
                            <td style="height:8mm;text-align:center;border:solid 1px black;"><p>'.$asist['estado'].'</p></td>
                        </tr>';
                    //}
                }*/
                $inasistenciasClase = reportesData::inasistenciasAlumnoClase($codigo,$row["inicio"],$row["fin"]);
                foreach ($inasistenciasClase as $asistCl) {
                    $sininasistenciat = false;
                    $c++;
                    $td_asist .= '
                    <tr>
                        <td style="height:8mm;padding-left:2mm;border:solid 1px black;"><p>'.$c.'</p></td>
                        <td style="height:8mm;padding-left:2mm;border:solid 1px black;"><p>'.date('m-d-Y',strtotime($asistCl['fecha'])).'</p></td>
                        <td style="height:8mm;text-align:center;border:solid 1px black;"><p>Inasistencia a clase - <b>'.$asistCl['asignatura'].'</b></p></td>
                        <td style="height:8mm;text-align:center;border:solid 1px black;"><p>'.$asistCl['estado'].'</p></td>
                    </tr>';
                }
                if ($td_asist != '') {
                    if($i == 0 || $p == 0){
                        $html .= 
                        '
                        <h3 style="text-align:center;padding-top:5mm;padding-bottom:5mm;">INASISTENCIAS A CLASE O INSTITUCIÓN</h3>
                        <table style="width:190mm;padding-left:10mm;padding-top:10mm;border-collapse: collapse;" cellspacing="0" cellpadding="0">
                        <tbody>';
                        $p++;
                    }
                    $html .= '
                    <tr>
                        <th style="height:8mm;border:solid 1px black;" colspan=4>'.$row["nombre"]." ".$row["inicio"]." - ".$row["fin"].'</th>
                    </tr>
                    <tr>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:7mm;">Nº</th>
                        <th style="height:8mm;border:solid 1px black;width:38mm;">Fecha</th>
                        <th style="height:8mm;border:solid 1px black;width:117mm;">Tipo</th>
                        <th style="height:8mm;border:solid 1px black;width:35mm;">Estado</th>
                    </tr>';
                    $html .= $td_asist;
                }
                if ((count($etapas)-1) == $i) {
                    $html .= '</tbody>
                    </table>';
                }
                $i++;
            }
            if ($sininasistenciat) {
                $html .= '<div align="center"><h2 style="text-align:center;">SIN INASISTENCIAS</h2></div>';
            }

            $html .= '</div>
            

            <!--htmlpagefooter name="footer">
                <div align="center" style="padding-bottom:10mm;"><b>{PAGENO}</b></div>
            </htmlpagefooter>
            <sethtmlpagefooter name="footer" value="on" /-->
        	</body>
		</html>';
    	$mpdf->WriteHTML($html);
        $k++;
    	if($k != (count($alumnos))){
    		$mpdf->AddPage();
    	}
    }
    $mpdf->Output();
?>