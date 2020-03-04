<?php 
    date_default_timezone_set('America/El_Salvador');
    include($_SERVER['DOCUMENT_ROOT']."/utils/mpdf/mpdf.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/reportes/listas/classListas.php");
    
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

    session_start();

    $id_grado = isset($_POST["id_gradoAc"]) ? trim($_POST["id_gradoAc"]): null;
    $id_seccion = isset($_POST["id_seccionAc"]) ? trim($_POST["id_seccionAc"]): null;
    $id_grupo_academico = isset($_POST["id_grupo_academicoAc"]) ? trim($_POST["id_grupo_academicoAc"]): null;

    //SE REQUIERE LA LISTA TENICA (BACHILLERATO)
    $lista = listas::listaAcademica($id_grado,$id_seccion,$id_grupo_academico);

    $mpdf = new mPDF('c','LETTER','', '' , 10 , 0 , 9 , 10 , 0 , 0);
 
    $mpdf->debug = true;

    $mpdf->SetDisplayMode('fullpage');
 
    $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list

    $mpdf->setFooter('{PAGENO}');

    $nombreGrado = "";
    $nombreGrupo = "";
    $nombreSeccion = "";
    $titulo = "";

    foreach ($lista as $datos) {
        $nombreSeccion = $datos["seccion"];
        $nombreGrado = $datos["grado"];
        if ($datos["grupoTecnico"] != "Indefinido"){
            $nombreGrupo = $datos["grupoAcademico"];
            $titulo = "BACHILLERATO TÉNICO VOCACIONAL Y GENERAL";
        }
        else{
            $nombreGrupo = "1";
            $titulo = "TERCER CICLO";
        }
        break;
    }

    if (count($lista) == 0) {
        $nombreGrado = "Ninguno";
        $nombreGrupo = "Ninguno";
        $nombreSeccion = "Ninguno";
        $titulo = "SIN DATOS QUE MOSTRAR";
    }

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
                #tablaLista{
                    border-collapse: collapse;
                    margin-top:-29mm;
                    width:178mm;
                    font-size:8pt;
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
                    '.$titulo.'
                </p>
                <p style="margin-left:43mm;margin-top:2mm;"><b>AÑO '.date('Y').'</b></p>
                <p style="margin-left:22mm;margin-top:4mm;"><b>'.mb_strtoupper($nombreGrado,"utf-8").', '.mb_strtoupper($nombreSeccion,"utf-8").', '.$nombreGrupo.'</b></p>
                <table id="tablaLista" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="noBorderCell"></td>
                        <td class="noBorderCell"></td>
                        <td class="noBorderCell"></td>
                        <td style="width:7mm;max-width:7mm;height:32mm;border-bottom:0px!important;"></td>
                        <td class="tdAlto" style="width:7mm;max-width:7mm;height:32mm;"></td>
                        <td class="tdAlto" style="width:7mm;max-width:7mm;height:32mm;"></td>
                        <td class="tdAlto" style="width:7mm;max-width:7mm;height:32mm;"></td>
                        <td class="tdAlto" style="width:7mm;max-width:7mm;height:32mm;"></td>
                        <td class="tdAlto" style="width:7mm;max-width:7mm;height:32mm;"></td>
                        <td class="tdAlto" style="width:7mm;max-width:7mm;height:32mm;"></td>
                        <td class="tdAlto" style="width:7mm;max-width:7mm;height:32mm;"></td>
                        <td class="tdAlto" style="width:7mm;max-width:7mm;height:32mm;"></td>
                    </tr>
                    <tr id="headers">
                        <td class="tdEncabezado" style="width:7mm;max-width:7mm;">No.</td>
                        <td class="tdEncabezado" style="width:15mm;max-width:15mm;border-bottom:1px solid black;"> Codigo</td>
                        <td class="tdEncabezado" style="text-align:center;width:97mm;max-width:97mm;border-bottom:1px solid black;">Nombre</td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-top:0px;"></td>
                    </tr>';
            //CORRELATIVO
            $n = 0;
            //NOMBRE VARIABLE DE LA ESPECIALIDAD
            $ep = "";
            $gt = "";
            foreach ($lista as $row) {
                $n++;
                if ($ep === "" && $row["especialidad"] != "Ninguna" && $gt === "") {
                $html.='
                    <tr>
                        <td style="background-color:#eeeeee" colspan=12>'.$row["especialidad"].', Grupo '.$row["grupoTecnico"].', '. $row["seccion"] .', '.$row["grupoAcademico"].'</td>
                    </tr>';
                    $ep = $row["especialidad"];
                    $gt = $row["grupoTecnico"];
                }
                if ($row["especialidad"] != $ep 
                    && $row["especialidad"] != "Ninguna"
                    || ($gt != $row["grupoTecnico"]) && $gt != "") {
                $html.='
                    <tr>
                        <td style="background-color:#eeeeee" colspan=12>'.$row["especialidad"].', Grupo '.$row["grupoTecnico"].', '. $row["seccion"] .', '.$row["grupoAcademico"].'</td>
                    </tr>';
                $ep = $row["especialidad"];
                $gt = $row["grupoTecnico"];
                }
                $html.='
                    <tr>
                        <td style="border-right:0px;">'.$n.'</td>
                        <td style="border-right:0px;border-top:0px;">'.$row["carnet"].'</td>
                        <td style="border-right:0px;border-top:0px;">'.$row["alumno"].'</td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-right:0px;border-top:0px;"></td>
                        <td style="width:7mm;max-width:7mm;border-top:0px;"></td>
                    </tr>';
            }
                $html.='
                </table>
                <div style="width:90mm;float:left;">
                    <p style="font-size:7pt; float:left;">Reporte Generado por: '.mb_strtoupper($_SESSION["apellido"],"utf-8").', '.mb_strtoupper($_SESSION["nombre"],"utf-8").'</p>
                </div>
                <div style="width:58mm;float:left;">
                    <p style="font-size:7pt; float:left;">Fecha: '.date('d-m-Y').'</p>
                </div>
                <div style="float:left;">
                    <p style="font-size:7pt; float:left;">Número de alumnos: '.$n.'</p>
                </div>
            </div>
        </body>
    </html>';

    $mpdf->WriteHTML($html);
    
    $mpdf->Output();
?>