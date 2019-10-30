<?php 
  $accion = isset($_POST["accion"]) && intval($_POST["accion"]) > 0 ? intval($_POST["accion"])  : 3;
  $nivel = isset($_POST["nivel"]) && intval($_POST["nivel"]) > 0 ? intval($_POST["nivel"])  : 2;
  $fecha_inicio = isset($_POST["fecha_inicio"]) ? trim(strip_tags($_POST["fecha_inicio"])): "01-01-2018";
  $fecha_final = isset($_POST["fecha_final"]) ? trim(strip_tags($_POST["fecha_final"])): "31-12-2018";

  require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/reportes/conducta/reporte.php");    
  require_once($_SERVER['DOCUMENT_ROOT']."/libs/PHPExcel.php");

  // Inasistencias clases Injustificadas
  if($accion == 1){
    // obtiene estudiantes
    $fecha_inicio = date('Y-m-d',strtotime($fecha_inicio));
    $fecha_final = date('Y-m-d',strtotime($fecha_final));

    $estudiantes = reportesData::inasistenciasClasesInjustificadas($fecha_inicio, $fecha_final, $nivel);    
    $studentData = array();
    if (count($estudiantes)>0) {
      foreach($estudiantes as $estudiante){          
          $data = reportesData::estudianteJustificadoClase($estudiante[0], 'Injustificada', $fecha_inicio, $fecha_final);
          array_push($studentData, $data);
      }
      function method1($a,$b) 
      {
        return ($a[7] >= $b[7]) ? -1 : 1;
      }
      usort($studentData, "method1");
      
      // exportar excel
      ini_set('display_errors', TRUE);
      ini_set('display_startup_errors', TRUE);
      date_default_timezone_set('UTC');
      $objPHPExcel = new PHPExcel();
      // Set document properties
      $objPHPExcel->getProperties()->setCreator("SGE")
                 ->setTitle("Inasistencias Clases Injustificadas");
          // Add some data
      if($nivel == 1){
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A1', 'Nº')
          ->setCellValue('B1', 'GRADO')
          ->setCellValue('C1', 'SECCION')
          ->setCellValue('D1', 'CODIGO')
          ->setCellValue('E1', 'APELLIDOS')
          ->setCellValue('F1', 'NOMBRES')
          ->setCellValue('G1', 'REC');
      }else 
      {
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A1', 'Nº')
          ->setCellValue('B1', 'GRADO')
          ->setCellValue('C1', 'ESPECIALIDAD')
          ->setCellValue('D1', 'SECCION')
          ->setCellValue('E1', 'CODIGO')
          ->setCellValue('F1', 'APELLIDOS')
          ->setCellValue('G1', 'NOMBRES')
          ->setCellValue('H1', 'REC');
      }
      

      $contador = 2;
      $numero = 1;
      foreach($studentData as $row){
        
        if($nivel == 1){
          // Miscellaneous glyphs, UTF-8
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$contador, $numero)
          ->setCellValue('B'.$contador, $row[0])
          ->setCellValue('C'.$contador, $row[2])
          ->setCellValue('D'.$contador, $row[4])
          ->setCellValue('E'.$contador, $row[5])
          ->setCellValue('F'.$contador, $row[6])
          ->setCellValue('G'.$contador, $row[7]);
        }else {
          // Miscellaneous glyphs, UTF-8
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$contador, $numero)
          ->setCellValue('B'.$contador, $row[0])
          ->setCellValue('C'.$contador, $row[1])
          ->setCellValue('D'.$contador, $row[2].'-'.$row[3])
          ->setCellValue('E'.$contador, $row[4])
          ->setCellValue('F'.$contador, $row[5])
          ->setCellValue('G'.$contador, $row[6])
          ->setCellValue('H'.$contador, $row[7]);
        }
        
        $contador++;
        $numero++;
      }

      // Rename worksheet
      $objPHPExcel->getActiveSheet()->setTitle('Inasis. Clases Injustificadas');
      // Set active sheet index to the first sheet, so Excel opens this as the first sheet
      $objPHPExcel->setActiveSheetIndex(0);
      // Redirect output to a client’s web browser (Excel5)
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="Inasistencias Clases Injustificadas.xls"');
      header('Cache-Control: max-age=0');
      // If you're serving to IE 9, then the following may be needed
      header('Cache-Control: max-age=1');
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      $objWriter->save('php://output');
      exit;
    }
  }
  // Inasistencias clases Justificadas
  if($accion == 2){
    // obtiene estudiantes
    $fecha_inicio = date('Y-m-d',strtotime($fecha_inicio));
    $fecha_final = date('Y-m-d',strtotime($fecha_final));

    $estudiantes = reportesData::inasistenciasClasesInjustificadas($fecha_inicio, $fecha_final, $nivel);    
    $studentData = array();
    if (count($estudiantes)>0) {
      foreach($estudiantes as $estudiante){          
          $data = reportesData::estudianteJustificadoClase($estudiante[0], 'Justificada', $fecha_inicio, $fecha_final);
          array_push($studentData, $data);
      }
      function method1($a,$b) 
      {
        return ($a[7] >= $b[7]) ? -1 : 1;
      }
      usort($studentData, "method1");
      
      // exportar excel
      ini_set('display_errors', TRUE);
      ini_set('display_startup_errors', TRUE);
      date_default_timezone_set('UTC');
      $objPHPExcel = new PHPExcel();
      // Set document properties
      $objPHPExcel->getProperties()->setCreator("SGE")
                 ->setTitle("Inasistencias Justificadas");
          // Add some data
      if($nivel == 1){
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A1', 'Nº')
          ->setCellValue('B1', 'GRADO')
          ->setCellValue('C1', 'SECCION')
          ->setCellValue('D1', 'CODIGO')
          ->setCellValue('E1', 'APELLIDOS')
          ->setCellValue('F1', 'NOMBRES')
          ->setCellValue('G1', 'REC');
      }else 
      {
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A1', 'Nº')
          ->setCellValue('B1', 'GRADO')
          ->setCellValue('C1', 'ESPECIALIDAD')
          ->setCellValue('D1', 'SECCION')
          ->setCellValue('E1', 'CODIGO')
          ->setCellValue('F1', 'APELLIDOS')
          ->setCellValue('G1', 'NOMBRES')
          ->setCellValue('H1', 'REC');
      }
      

      $contador = 2;
      $numero = 1;
      foreach($studentData as $row){
        
        if($nivel == 1){
          // Miscellaneous glyphs, UTF-8
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$contador, $numero)
          ->setCellValue('B'.$contador, $row[0])
          ->setCellValue('C'.$contador, $row[2])
          ->setCellValue('D'.$contador, $row[4])
          ->setCellValue('E'.$contador, $row[5])
          ->setCellValue('F'.$contador, $row[6])
          ->setCellValue('G'.$contador, $row[7]);
        }else {
          // Miscellaneous glyphs, UTF-8
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$contador, $numero)
          ->setCellValue('B'.$contador, $row[0])
          ->setCellValue('C'.$contador, $row[1])
          ->setCellValue('D'.$contador, $row[2].'-'.$row[3])
          ->setCellValue('E'.$contador, $row[4])
          ->setCellValue('F'.$contador, $row[5])
          ->setCellValue('G'.$contador, $row[6])
          ->setCellValue('H'.$contador, $row[7]);
        }
        
        $contador++;
        $numero++;
      }

      // Rename worksheet
      $objPHPExcel->getActiveSheet()->setTitle('Inasis. Clases Justificadas');
      // Set active sheet index to the first sheet, so Excel opens this as the first sheet
      $objPHPExcel->setActiveSheetIndex(0);
      // Redirect output to a client’s web browser (Excel5)
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="Inasistencias Clases Justificadas.xls"');
      header('Cache-Control: max-age=0');
      // If you're serving to IE 9, then the following may be needed
      header('Cache-Control: max-age=1');
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      $objWriter->save('php://output');
      exit;
    }
  }
  // Inasistencias totales Injustificadas
  if($accion == 3){
    // obtiene estudiantes
    $fecha_inicio = date('Y-m-d',strtotime($fecha_inicio));
    $fecha_final = date('Y-m-d',strtotime($fecha_final));

    $estudiantes = reportesData::inasistenciasInjustificadas($fecha_inicio, $fecha_final, $nivel);    
    $studentData = array();
    if (count($estudiantes)>0) {
      foreach($estudiantes as $estudiante){          
          $data = reportesData::estudianteJustificado($estudiante[0], 'Injustificada', $fecha_inicio, $fecha_final);
          array_push($studentData, $data);
      }
      function method1($a,$b) 
      {
        return ($a[7] >= $b[7]) ? -1 : 1;
      }
      usort($studentData, "method1");
      
      // exportar excel
      ini_set('display_errors', TRUE);
      ini_set('display_startup_errors', TRUE);
      date_default_timezone_set('UTC');
      $objPHPExcel = new PHPExcel();
      // Set document properties
      $objPHPExcel->getProperties()->setCreator("SGE")
                 ->setTitle("Inasis. Totales Injustificadas");
          // Add some data
      if($nivel == 1){
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A1', 'Nº')
          ->setCellValue('B1', 'GRADO')
          ->setCellValue('C1', 'SECCION')
          ->setCellValue('D1', 'CODIGO')
          ->setCellValue('E1', 'APELLIDOS')
          ->setCellValue('F1', 'NOMBRES')
          ->setCellValue('G1', 'REC');
      }else 
      {
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A1', 'Nº')
          ->setCellValue('B1', 'GRADO')
          ->setCellValue('C1', 'ESPECIALIDAD')
          ->setCellValue('D1', 'SECCION')
          ->setCellValue('E1', 'CODIGO')
          ->setCellValue('F1', 'APELLIDOS')
          ->setCellValue('G1', 'NOMBRES')
          ->setCellValue('H1', 'REC');
      }
      

      $contador = 2;
      $numero = 1;
      foreach($studentData as $row){
        
        if($nivel == 1){
          // Miscellaneous glyphs, UTF-8
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$contador, $numero)
          ->setCellValue('B'.$contador, $row[0])
          ->setCellValue('C'.$contador, $row[2])
          ->setCellValue('D'.$contador, $row[4])
          ->setCellValue('E'.$contador, $row[5])
          ->setCellValue('F'.$contador, $row[6])
          ->setCellValue('G'.$contador, $row[7]);
        }else {
          // Miscellaneous glyphs, UTF-8
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$contador, $numero)
          ->setCellValue('B'.$contador, $row[0])
          ->setCellValue('C'.$contador, $row[1])
          ->setCellValue('D'.$contador, $row[2].'-'.$row[3])
          ->setCellValue('E'.$contador, $row[4])
          ->setCellValue('F'.$contador, $row[5])
          ->setCellValue('G'.$contador, $row[6])
          ->setCellValue('H'.$contador, $row[7]);
        }
        
        $contador++;
        $numero++;
      }

      // Rename worksheet
      $objPHPExcel->getActiveSheet()->setTitle('Inasis. Totales Injustificadas');
      // Set active sheet index to the first sheet, so Excel opens this as the first sheet
      $objPHPExcel->setActiveSheetIndex(0);
      // Redirect output to a client’s web browser (Excel5)
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="Inasistencias Totales Injustificadas.xls"');
      header('Cache-Control: max-age=0');
      // If you're serving to IE 9, then the following may be needed
      header('Cache-Control: max-age=1');
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      $objWriter->save('php://output');
      exit;
    }
  }
  // Inasistencias totales Justificadas
  if($accion == 4){
    // obtiene estudiantes
    $fecha_inicio = date('Y-m-d',strtotime($fecha_inicio));
    $fecha_final = date('Y-m-d',strtotime($fecha_final));

    $estudiantes = reportesData::inasistenciasJustificadas($fecha_inicio, $fecha_final, $nivel);    
    $studentData = array();
    if (count($estudiantes)>0) {
      foreach($estudiantes as $estudiante){          
          $data = reportesData::estudianteJustificado($estudiante[0], 'Justificada', $fecha_inicio, $fecha_final);
          array_push($studentData, $data);
      }
      function method1($a,$b) 
      {
        return ($a[7] >= $b[7]) ? -1 : 1;
      }
      usort($studentData, "method1");
      
      // exportar excel
      ini_set('display_errors', TRUE);
      ini_set('display_startup_errors', TRUE);
      date_default_timezone_set('UTC');
      $objPHPExcel = new PHPExcel();
      // Set document properties
      $objPHPExcel->getProperties()->setCreator("SGE")
                 ->setTitle("Inasis. Totales Justificadas");
          // Add some data
      if($nivel == 1){
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A1', 'Nº')
          ->setCellValue('B1', 'GRADO')
          ->setCellValue('C1', 'SECCION')
          ->setCellValue('D1', 'CODIGO')
          ->setCellValue('E1', 'APELLIDOS')
          ->setCellValue('F1', 'NOMBRES')
          ->setCellValue('G1', 'REC');
      }else 
      {
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A1', 'Nº')
          ->setCellValue('B1', 'GRADO')
          ->setCellValue('C1', 'ESPECIALIDAD')
          ->setCellValue('D1', 'SECCION')
          ->setCellValue('E1', 'CODIGO')
          ->setCellValue('F1', 'APELLIDOS')
          ->setCellValue('G1', 'NOMBRES')
          ->setCellValue('H1', 'REC');
      }
      

      $contador = 2;
      $numero = 1;
      foreach($studentData as $row){
        
        if($nivel == 1){
          // Miscellaneous glyphs, UTF-8
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$contador, $numero)
          ->setCellValue('B'.$contador, $row[0])
          ->setCellValue('C'.$contador, $row[2])
          ->setCellValue('D'.$contador, $row[4])
          ->setCellValue('E'.$contador, $row[5])
          ->setCellValue('F'.$contador, $row[6])
          ->setCellValue('G'.$contador, $row[7]);
        }else {
          // Miscellaneous glyphs, UTF-8
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$contador, $numero)
          ->setCellValue('B'.$contador, $row[0])
          ->setCellValue('C'.$contador, $row[1])
          ->setCellValue('D'.$contador, $row[2].'-'.$row[3])
          ->setCellValue('E'.$contador, $row[4])
          ->setCellValue('F'.$contador, $row[5])
          ->setCellValue('G'.$contador, $row[6])
          ->setCellValue('H'.$contador, $row[7]);
        }
        
        $contador++;
        $numero++;
      }

      // Rename worksheet
      $objPHPExcel->getActiveSheet()->setTitle('Inasis. Totales Justificadas');
      // Set active sheet index to the first sheet, so Excel opens this as the first sheet
      $objPHPExcel->setActiveSheetIndex(0);
      // Redirect output to a client’s web browser (Excel5)
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="Inasistencias Totales Justificadas.xls"');
      header('Cache-Control: max-age=0');
      // If you're serving to IE 9, then the following may be needed
      header('Cache-Control: max-age=1');
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      $objWriter->save('php://output');
      exit;
    }
  }
?>