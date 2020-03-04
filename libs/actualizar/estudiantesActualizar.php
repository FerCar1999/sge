<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
    $sqlAlumnosT = "SELECT id_estudiante FROM inasistencias_totales GROUP BY id_estudiante";
    $params = array();
    $dataAlumnosT = Database::getRows($sqlAlumnosT, $params);
    $sqlAlumnosC = "SELECT id_estudiante FROM inasistencias_clases GROUP BY id_estudiante";
    $params = array();
    $dataAlumnosC = Database::getRows($sqlAlumnosC, $params);
    $array1 = array();
    $array2 = array();
    $entrada = array();
    $resultado = array();
    $alumnosAjustar = array();
    foreach($dataAlumnosT as $id){
        array_push($array1, $id[0]);
    }
    foreach($dataAlumnosC as $id){
        array_push($array2, $id[0]);
    }
    $intersecc = array_intersect($array1, $array2);
    $diff1 = array_diff($array1, $array2);
    $diff2 = array_diff($array2, $array1);
    for ($i=0; $i < count($intersecc); $i++) { 
        array_push($resultado, $intersecc[$i]);
    }
    for ($i=0; $i < count($diff1); $i++) { 
        if($diff1[$i] != null){
            array_push($resultado, $diff1[$i]);
            $real = $real + 1;
        }
    }
    for ($i=0; $i < count($diff2); $i++) { 
        if($diff2[$i] != null){
            array_push($resultado, $diff2[$i]);
        }
    }
    //print_r($resultado);
    for ($i=0; $i < count($resultado); $i++) { 
        $sqlDataAlumnoT = "SELECT * FROM inasistencias_totales WHERE id_estudiante = ?";  
        $paramsT = array($resultado[$i]);
        $dataT = Database::getRows($sqlDataAlumnoT, $paramsT); 
        $sqlDataAlumnoC = "SELECT * FROM inasistencias_clases WHERE id_estudiante = ?";
        $paramsC = array($resultado[$i]);
        $dataC = Database::getRows($sqlDataAlumnoC, $paramsC);
        if(count($dataC) > 0){
            foreach ($dataT as $keyT) {
                foreach ($dataC as $keyC) {
                    if(date('Y-m-d', strtotime($keyC[2])) == date('Y-m-d', strtotime($keyT[1]))){
                        if($keyC[4] != $keyT[3]){
                            array_push($alumnosAjustar, $resultado[$i]);
                        }
                    }
                }
            }
        }
    }
    $final = array_unique($alumnosAjustar);
    for ($i=0; $i < count($final); $i++) { 
        $sqlDataAlumnoT = "SELECT * FROM inasistencias_totales WHERE id_estudiante = ?";  
        $paramsT1 = array($final[$i]);
        $dataT1 = Database::getRows($sqlDataAlumnoT, $paramsT1); 
        $sqlDataAlumnoC = "SELECT * FROM inasistencias_clases WHERE id_estudiante = ?";
        $paramsC1 = array($final[$i]);
        $dataC1 = Database::getRows($sqlDataAlumnoC, $paramsC1);
        if(count($dataC1) > 0){
            foreach ($dataT1 as $keyT) {
                foreach ($dataC1 as $keyC) {
                    if(date('Y-m-d', strtotime($keyC[2])) == date('Y-m-d', strtotime($keyT[1]))){
                        if($keyC[4] != $keyT[3]){
                            $sqlIT ="UPDATE inasistencias_totales set estado = ? WHERE fecha = ? AND id_estudiante = ?";
                            $paramsAT = array('Justificada',$keyT[1],$final[$i]);
                            Database::executeRow($sqlIT, $paramsAT);
                            $sqlIC ="UPDATE inasistencias_clases set estado = ? WHERE fecha_hora = ? AND id_estudiante = ?";
                            $paramsAC = array('Justificada',$keyC[2],$final[$i]);
                            if(Database::executeRow($sqlIC, $paramsAC)){
                                echo("exito*---*");
                            }
                            //echo($final[$i]."***");
                        }
                    }
                }
            }
        }
    }
    //print_r($final);
?>