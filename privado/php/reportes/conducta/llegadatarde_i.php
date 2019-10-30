<?php 
    
    include($_SERVER['DOCUMENT_ROOT']."/utils/mpdf/mpdf.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/reportes/conducta/reporte.php");

    /*$inicio = isset($_POST['inicio']) ? intval($_POST['inicio']) : "";
    $fin = isset($_POST['fin']) ? intval($_POST['fin']) : "";*/
    date_default_timezone_set('America/El_Salvador');

    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];
    
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");
        
    if (!validarFecha(date("Y-m-d",strtotime($inicio))) && $inicio != "") {
        echo "Fecha de inicio inválida";
        exit();
    }
    if (!validarFecha(date("Y-m-d",strtotime($fin))) && $fin != "") {
        echo "Fecha de finalización inválida";
        exit();
    }

    $mpdf = new mPDF('c','LETTER','','' , 0 , 0 , 10 , 10 , 0 , 5); 
 
    $mpdf->debug = true;

    $mpdf->SetDisplayMode('fullpage');
 
    $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list

    $mpdf->setFooter('{PAGENO} / {nb}');

    $html = '<html>
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
                    margin:-10mm;
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
                    padding-top:-10mm;
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
                <p style="text-align:center;font-weight:bold;padding-top:1mm;padding-left:10mm;font-size:13pt;">LLEGADAS TARDE A LA INSTITUCIÓN</p>
                <img src="'.$_SERVER['DOCUMENT_ROOT'].'/media/img/logo.png" style="width:23mm;padding-left:10mm;padding-top:1mm;">';

                if ($inicio != $fin) {
                    $html .=  '
                <p style="text-align:center;font-weight:bold;padding-top:1mm;padding-left:10mm;font-size:13pt;">DESDE '.$inicio.' HASTA '.$fin.'</p>';
                }else{
                    $html .=  '
                <p style="text-align:center;font-weight:bold;padding-top:1mm;padding-left:10mm;font-size:13pt;">FECHA '.date("d-m-Y",strtotime($inicio)).'</p>';
                }
                ///LEGADAS TARDE A LA INSTITUCION
            
                if ($inicio === "" && $fin === "") {
                    $llegadasTardeInstitucion = reportesData::llegadasTardeInstitucionTotal();
                }else{
                    $llegadasTardeInstitucion = reportesData::llegadasTardeInstitucionTotal($inicio,$fin);
                }
                if (count($llegadasTardeInstitucion)>0) {
                    $c = 0;
                    $html .= 
                    '<table style="width:190mm;padding-left:10mm;padding-top:10mm;border-collapse:collapse;margin-top:10mm;" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:7mm;">Nº</th>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:30mm;">Fecha</th>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:30mm;">Carnet</th>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:75mm;">Estudiante</th>
                        <th style="height:8mm;border:solid 1px black;font-size:11pt;width:70mm;">Curso</th>
                        <!--th style="height:8mm;border:solid 1px black;font-size:11pt;width:35mm;">Estado</th-->
                    </tr>';
                    foreach ($llegadasTardeInstitucion as $row) {
                        $c++;
                        $html .= '
                        <tr>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;text-align:center;"><p>'.
                            $c.'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;text-align:center;"><p>'.date("d-m-Y h:i",strtotime($row['fecha'])).'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;text-align:center;"><p>'.$row['codigo'].'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;padding-left:2mm;"><p>'.$row['nombre'].'</p></td>
                            <td style="height:8mm;border:solid 1px black;font-size:9pt;padding-left:2mm;"><p>'.$row['grado'].'</p></td>
                            <!--td style="height:8mm;border:solid 1px black;font-size:9pt;text-align:center;"><p></p></td-->
                        </tr>';
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

    //$mpdf->WriteHTML(file_get_contents($_SERVER['DOCUMENT_ROOT']."/privado/php/reportes/conducta.php"));
    $mpdf->WriteHTML($html);
    
    $mpdf->Output();
?>