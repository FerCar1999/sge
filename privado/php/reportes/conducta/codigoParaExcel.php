<?php 
    $inicio = date('Y-m-d', strtotime('01-01-2018'));
    $fin = date('Y-m-d', strtotime('31-12-2018'));
    $nivel = 1;
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/reportes/conducta/reporte.php");    
    //NO LO HE PUESTO EN UN MÉTODO SON SOLO LAS LÍNEAS DE CÓDIGO PARA QUE AGARRE LOS DATOS
    //TENES QUE ENVIAR LA FECHA DE INICIO, LA DE FIN, Y EL NIVEL (TERCER CICLO, BACHILLERATO)
    $estudiantes = reportesData::inasistenciasClasesInjustificadas($inicio, $fin, $nivel);
    //ESTE ARRAY DE ABAJO TE VA A DEVOLVER LA INFORMACIÓN YA SOLO PARA ACOMODARLA AL EXCEL
    $studentData = array(); 
    //SI HAY DATOS ENTRA AL IF
    if (count($estudiantes)>0) {
        //AHORA VA A RECORRER LOS ESTUDIANTES
        foreach($estudiantes as $estudiante){
            //LE VOLVES A MANDAR EL INICIO Y EL FIN, NADA MAS ESO LO OTRO LO AGARRA DE LOS DATOS DE ANTES
            //EL PARAMETRO DEL ESTADO 'Injustificado' PUEDE CAMBIAR, DEPENDE DE LO QUE QUERRÁS, SI VER
            //LOS INJUSTIFICADOS O LOS JUSTIFICADOS, SERÍA 'Injustificada' y 'Justificada'
            //EL METODO DE 'estudianteJustificadoClase' ES PARA LAS CLASES SI QUERES SACAR LAS TOTALES
            //OCUPAS el'estudianteJustificado' Y YA.
            $data = reportesData::estudianteJustificadoClase($estudiante[0], 'Injustificada', $inicio, $fin);
            array_push($studentData, $data);
        }
        function method1($a,$b) 
        {
          return ($a[7] >= $b[7]) ? -1 : 1;
        }
        usort($studentData, "method1");
        echo var_dump($studentData);
        foreach($studentData as $datos){
        //    echo $datos[7];
          //  echo "***";
        }
    }
?>