<?php
session_start();
if(isset($_SESSION['id_personal'])){
require_once ('../../libs/PHPExcel.php');
require_once('../App/Models/estudiantes.class.php');
class ExcelClass{
    public function create_excel(){
        try{
            $data = null;
             $estudiantes = new estudiantes;                                
                if(isset($_POST['reporte'])){
                        if($estudiantes->setAnio($_POST['Anio'])){
                            if($estudiantes->setInicio($_POST['Fechainicio'])){
                                if($estudiantes->setFin($_POST['Fechafin'])){
                                  
                        if(isset($_POST['Seccion'])){
                            $estudiantes->SetSeccion($_POST['Seccion']);
                            if($_POST['Anio'] >=4){
                                if(isset($_POST['Grupoacademico'])){
                                    if($estudiantes->setGrupoacademico($_POST['Grupoacademico'])){
                                        $data = $estudiantes->getExcelSeccion();
                                      }
                                }                             
                            }else{
                                if($estudiantes->setEspecialidad(11)){
                                    if($estudiantes->setGrupotecnico(4)){
                                        if($estudiantes->setGrupoacademico(1)){
                                         $data = $estudiantes->getExcelTercerCiclo();
                                        }
                                    }
                                }                             
                            }
                        }
                        if(isset($_POST['Especialidad'])){
                            $estudiantes->setEspecialidad($_POST['Especialidad']);
                            if(isset($_POST['Grupotecnico'])){
                                if($estudiantes->setGrupotecnico($_POST['Grupotecnico'])){
                                    $data = $estudiantes->getExcelEspecialidad();
                                } 
                            }else{
                                header('Location: reportes.php');
                            }                                                     
                        }

                        }else{
                            header('Location: reportes.php');
                        }
                    }else{
                        header('Location: reportes.php');
                    }
                }else{
                    header('Location: reportes.php');
                }
        }         
            if($data){
                if($_POST['Anio'] < 4){
                    $especialidad = 11;
                    $grupoAcademico =1;
                    $anio =$_POST['Anio'];
                    $seccion = $_POST['Seccion'];
                    $grupoTecnico =4;
                }else{
                    if(isset($_POST['Especialidad']) && isset($_POST['Anio']) && isset($_POST['Grupotecnico'])){
                        $especialidad = $_POST['Especialidad'];
                        $anio =$_POST['Anio'];
                        $grupoTecnico =$_POST['Grupotecnico'];
                    }
                    else{
                        if(isset($_POST['Seccion'])){
                            if($estudiantes->getConsultarEsp()){
                                $anio = $_POST['Anio'];
                                $especialidad = $estudiantes->getEspecialidad();                 
                                $grupoTecnico = $estudiantes->getGrupoTecnico();
                            }                       
                        }
                       
                    }
                }
               if(isset($_POST['reporte'])){
                   if($_POST['Anio'] < 4){
                    $data2 = $estudiantes->getJustificacionesTercerCiclo();
                   }else{
                    $data2 = $estudiantes->getJustificaciones();
                   }
               }
         
                $idSeccion = "Indefinido";
                if($anio == 1 && $seccion == 1 && $especialidad == 11 && $grupoAcademico == 1 && $grupoTecnico == 4)$idSeccion=772250;
                if($anio == 1 && $seccion == 2 && $especialidad == 11 && $grupoAcademico == 1 && $grupoTecnico == 4)$idSeccion=772256;
                if($anio == 1 && $seccion == 3 && $especialidad == 11 && $grupoAcademico == 1 && $grupoTecnico == 4)$idSeccion=773743;
                if($anio == 1 && $seccion == 4 && $especialidad == 11 && $grupoAcademico == 1 && $grupoTecnico == 4)$idSeccion=773749;

                if($anio == 2 && $seccion == 1 && $especialidad == 11 && $grupoAcademico == 1 && $grupoTecnico == 4)$idSeccion=773754;
                if($anio == 2 && $seccion == 2 && $especialidad == 11 && $grupoAcademico == 1 && $grupoTecnico == 4)$idSeccion=773757;
                if($anio == 2 && $seccion == 3 && $especialidad == 11 && $grupoAcademico == 1 && $grupoTecnico == 4)$idSeccion=773759;
                if($anio == 2 && $seccion == 4 && $especialidad == 11 && $grupoAcademico == 1 && $grupoTecnico == 4)$idSeccion=773762;

                if($anio == 3 && $seccion == 1 && $especialidad == 11 && $grupoAcademico == 1 && $grupoTecnico == 4)$idSeccion=773767;
                if($anio == 3 && $seccion == 2 && $especialidad == 11 && $grupoAcademico == 1 && $grupoTecnico == 4)$idSeccion=773769;
                if($anio == 3 && $seccion == 3 && $especialidad == 11 && $grupoAcademico == 1 && $grupoTecnico == 4)$idSeccion=773772;
                if($anio == 3 && $seccion == 4 && $especialidad == 11 && $grupoAcademico == 1 && $grupoTecnico == 4)$idSeccion=773774;
                //PRIMERO
                if($anio == 4 && $especialidad == 12 && $grupoTecnico == 4)$idSeccion = 847250;

                if($anio == 4 && $especialidad == 2 && $grupoTecnico == 1)$idSeccion = 841574;
                if($anio == 4 && $especialidad == 9 && $grupoTecnico == 1)$idSeccion = 841575;
                if($anio == 4 && $especialidad == 9 && $grupoTecnico == 2)$idSeccion = 841577;
                if($anio == 4 && $especialidad == 5 && $grupoTecnico == 1)$idSeccion = 775056;
                if($anio == 4 && $especialidad == 4 && $grupoTecnico == 1)$idSeccion = 841578;
                if($anio == 4 && $especialidad == 4 && $grupoTecnico == 2)$idSeccion = 841580;
                if($anio == 4 && $especialidad == 7 && $grupoTecnico == 1)$idSeccion = 774680;
                if($anio == 4 && $especialidad == 8 && $grupoTecnico == 1)$idSeccion = 774718;
                if($anio == 4 && $especialidad == 8 && $grupoTecnico == 2)$idSeccion = 774724;
                if($anio == 4 && $especialidad == 3 && $grupoTecnico == 1)$idSeccion = 798222;
                //SEGUNDO
                if($anio == 5 && $especialidad == 12 && $grupoTecnico == 4)$idSeccion = 786523;

                if($anio == 5 && $especialidad == 2 && $grupoTecnico == 1)$idSeccion = 774656;
                if($anio == 5 && $especialidad == 9 && $grupoTecnico == 1)$idSeccion = 798226;
                if($anio == 5 && $especialidad == 9 && $grupoTecnico == 2)$idSeccion = 798227;
                if($anio == 5 && $especialidad == 5 && $grupoTecnico == 1)$idSeccion = 775081;
                if($anio == 5 && $especialidad == 4 && $grupoTecnico == 1)$idSeccion = 798228;
                if($anio == 5 && $especialidad == 4 && $grupoTecnico == 2)$idSeccion = 798230;
                if($anio == 5 && $especialidad == 7 && $grupoTecnico == 1)$idSeccion = 774699;
                if($anio == 5 && $especialidad == 8 && $grupoTecnico == 1)$idSeccion = 774737;
                if($anio == 5 && $especialidad == 8 && $grupoTecnico == 2)$idSeccion = 774743;
                if($anio == 5 && $especialidad == 3 && $grupoTecnico == 1)$idSeccion = 774785;

                if($anio == 5 && $especialidad == 10 && $grupoTecnico == 1)$idSeccion = 775171;
                //TERCERO
                if($anio == 6 && $especialidad == 2 && $grupoTecnico == 1)$idSeccion = 774662;
                if($anio == 6 && $especialidad == 9 && $grupoTecnico == 1)$idSeccion = 774607;
                if($anio == 6 && $especialidad == 9 && $grupoTecnico == 2)$idSeccion = 774633;
                if($anio == 6 && $especialidad == 5 && $grupoTecnico == 1)$idSeccion = 775125;
                if($anio == 6 && $especialidad == 4 && $grupoTecnico == 1)$idSeccion = 798229;
                if($anio == 6 && $especialidad == 4 && $grupoTecnico == 2)$idSeccion = 810505;
                if($anio == 6 && $especialidad == 7 && $grupoTecnico == 1)$idSeccion = 774707;
                if($anio == 6 && $especialidad == 8 && $grupoTecnico == 1)$idSeccion = 774750;
                if($anio == 6 && $especialidad == 8 && $grupoTecnico == 2)$idSeccion = 774757;
                if($anio == 6 && $especialidad == 3 && $grupoTecnico == 1)$idSeccion = 774787;
 
                if($anio == 6 && $especialidad == 10 && $grupoTecnico == 1)$idSeccion = 775177;

                //Instanciamos PHPExcel
                $objExcel = new PHPExcel();   
                //Modificamos propiedades
                $objExcel->getProperties()
                ->setCreator('Usuario')
                ->setTitle('Reporte inasistencias')
                ->setDescription('Reporte asistencias e inasistencias del alumnado')
                ->setKeywords('excel phpexcel php')
                ->setCategory('Reportes');

               /* $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
                $objDrawing->setName('HOLA');
                $objDrawing->setDescription('HELLO');
                $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
                $objDrawing->setHeight(100);
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWorksheet($objExcel->getActiveSheet());*/
               
                //Se activa lo hoja a ocupar y se le asigna un titulo
                $objExcel->setActiveSheetIndex(0);
                $objExcel->getActiveSheet()->setTitle('Reporte estudiantes');
        
                    //Creamos un estilo para los indices 
                    $estiloTituloColumnas = array(
                    'font' => array(
                    'name'  => 'Arial',
                    'bold'  => true,
                    'size' =>11,
                    'color' => array(
                    'rgb' => 'FFFFFF'
                    )
                    ),
                    'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '538DD5')
                    ),
                    'borders' => array(
                    'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                    ),
                    'alignment' =>  array(
                    'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    )
                    );
                    
                    $estiloInformacion = new PHPExcel_Style();
                    $estiloInformacion->applyFromArray( array(
                    'font' => array(
                    'name'  => 'Arial',
                    'color' => array(
                    'rgb' => '000000'
                    )
                    ),
                    'fill' => array(
                    'type'  => PHPExcel_Style_Fill::FILL_SOLID
                    ),
                    'borders' => array(
                    'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                    ),
                    'alignment' =>  array(
                    'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    )
                    ));
                    //Estilo de titulo principal
                    $estiloTitulo = array(
                        'font' => array(
                        'name'  => 'Arial',
                        'bold'  => true,
                        'size' =>15,
                        'color' => array(
                        'rgb' => '000000'
                        )
                        ),
                        'alignment' =>  array(
                        'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    ));

                //Colocar estilos al encabezado   
                $objExcel->getActiveSheet()->getStyle('B2:G2')->applyFromArray($estiloTituloColumnas);
                $objExcel->getActiveSheet()->setCellValue("B1","REPORTE RICALDONE");
                $objExcel->getActiveSheet()->mergeCells('B1:G1');
                $objExcel->getActiveSheet()->getStyle('B1')->applyFromArray($estiloTitulo);
                //anchura de la columna autoajustable
                 $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                 $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                 $objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                 $objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                 $objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                 $objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                    
                //Agregamos el formato del documento para las celdas con encabezado
                $objExcel->getActiveSheet()->setCellValue('B2', "Codigo");
                $objExcel->getActiveSheet()->setCellValue('C2', "Id Seccion");
                $objExcel->getActiveSheet()->setCellValue('D2', "Codigo estudiante");
                $objExcel->getActiveSheet()->setCellValue('E2', "Fecha");
                $objExcel->getActiveSheet()->setCellValue('F2', "Falto");
                $objExcel->getActiveSheet()->setCellValue('G2', "Observaciones");
        
                $j = 3;
                foreach($data as $alumno){
                    if($_POST['Anio'] < 4){
                    $objExcel->getActiveSheet()->setCellValue('B'.($j), $alumno['seccion']);
                    }else{
                    $objExcel->getActiveSheet()->setCellValue('B'.($j), $alumno['gtecnico']);
                    }
                    $objExcel->getActiveSheet()->setCellValue('C'.($j), $idSeccion);
                    $objExcel->getActiveSheet()->setCellValue('D'.($j), $alumno['codigo']);
                    $objExcel->getActiveSheet()->setCellValue('E'.($j), $alumno['fecha']);
                    $objExcel->getActiveSheet()->setCellValue('F'.($j), $alumno['falto']);
                    $objExcel->getActiveSheet()->setCellValue('G'.($j),"Sin observaciones");

                    $j++;
                }
                $objExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "B3:G".$j);

                $objExcel->createSheet();
                //ACTIVANDO SEGUNDA PAGINA EXCEL
                $objExcel->setActiveSheetIndex(1);
                $objExcel->getActiveSheet()->setTitle('Reporte Justificaciones');

                 //Colocar estilos al encabezado   
                $objExcel->getActiveSheet()->getStyle('B2:F2')->applyFromArray($estiloTituloColumnas);
                $objExcel->getActiveSheet()->setCellValue("B1","JUSTIFICACIONES RICALDONE");
                $objExcel->getActiveSheet()->mergeCells('B1:F1');
                $objExcel->getActiveSheet()->getStyle('B1')->applyFromArray($estiloTitulo);
                //anchura de la columna autoajustable
                 $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                 $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                 $objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                 $objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                 $objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                    
                //Agregamos el formato del documento para las celdas con encabezado
                $objExcel->getActiveSheet()->setCellValue('B2', "Codigo estudiante");
                $objExcel->getActiveSheet()->setCellValue('C2', "Fecha inicio");
                $objExcel->getActiveSheet()->setCellValue('D2', "Fecha fin");
                $objExcel->getActiveSheet()->setCellValue('E2', "Motivo");
                $objExcel->getActiveSheet()->setCellValue('F2', "Permiso");
        
                $j = 3;
                foreach($data2 as $justificacion){
                    $objExcel->getActiveSheet()->setCellValue('B'.($j), $justificacion['codigo']);
                    $objExcel->getActiveSheet()->setCellValue('C'.($j), $justificacion['inicio']);
                    $objExcel->getActiveSheet()->setCellValue('D'.($j), $justificacion['fin']);
                    $objExcel->getActiveSheet()->setCellValue('E'.($j), $justificacion['motivo']);
                    $objExcel->getActiveSheet()->setCellValue('F'.($j), $justificacion['permiso']);
                    $j++;
                }
                $objExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "B3:F".$j);
                $objExcel->setActiveSheetIndex(0);
                //Excel aplication
                header('Content-Type: application/vnd.ms-excel');
                //En esta seccion tendremos que poner un boton file
                header('Content-Disposition: attachment;filename="Reporteinasistencias'.date("y/m/d").'.xlsx"');
                header('Cache-Control: max-age=0');    
                //Creamos un excel con la version 5
                $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
                $objWriter->save('php://output');

            }else{
               echo('<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <style>
            @import url("https://fonts.googleapis.com/css?family=Montserrat:400,600,700");
            @import url("https://fonts.googleapis.com/css?family=Catamaran:400,800");
            .error-container {
            text-align: center;
            font-size: 180px;
            font-family: "Catamaran", sans-serif;
            font-weight: 800;
            margin: 20px 15px;
            }
            * {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            body {
            background-color: #416475;
            margin-bottom: 50px;
            }
            html, button, input, select, textarea {
                font-family: "Montserrat", Helvetica, sans-serif;
                color: #92a4ad;
            }
            h1 {
            text-align: center;
            margin: 30px 15px;
            }
            .zoom-area { 
            max-width: 490px;
            margin: 30px auto 30px;
            font-size: 19px;
            text-align: center;
            }
        </style>
        <title>Document</title>
    </head>
    <body>
        <h1>Falta de datos</h1>
        <p class="zoom-area"> Carge nuevamente el excel desde el sistema informatico </p>
        <section class="error-container">
        <span>No hay datos</span>
        </section>
    </body>
    </html>
    ');        
            }
        } catch(Exception $error){
                Component::showMessage(2, $error->getMessage());
            }
    } 
}
}else{
header("location: /login");
}
    $obj = new  ExcelClass();
    $obj->create_excel();
?>
