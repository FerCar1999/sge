<?php 
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/publico/php/reportes/classHorarioAlumno.php");
    
    //OBTENER DATOS DEL ALUMNO

    function getAlumno($pk){
        $sql = "SELECT
                    e.id_grupo_academico,
                    e.id_grupo_tecnico,
                    g.id_grado,
                    s.id_seccion,
                    IF(ep.nombre != 'Ninguna',
                    CONCAT(g.nombre,' ',ep.nombre,' ', gt.nombre,s.nombre),
                    CONCAT(g.nombre,' ',s.nombre)) AS encabezadoTec,
                    IF(ep.nombre != 'Ninguna',
                    CONCAT(g.nombre,' ', s.nombre, ga.nombre),
                    CONCAT(g.nombre,' ', s.nombre)) AS encabezadoAcad,
                    e.id_especialidad
                FROM estudiantes e, grupos_academicos ga, grupos_tecnicos gt, grados g, secciones s, especialidades ep
                WHERE e.codigo= ?
                    AND e.id_grupo_academico = ga.id_grupo_academico
                    AND e.id_grupo_tecnico = gt.id_grupo_tecnico
                    AND e.id_seccion = s.id_seccion
                    AND g.id_grado = e.id_grado
                    AND ep.id_especialidad = e.id_especialidad";
        $params = array($pk);
        $data = Database::getRows($sql, $params);
        return $data;
    }

    function getDia($grupo_academico, $grupo_tecnico, $especialidad, $seccion, $grado, $dia){
        $sql = "SELECT 
            t.hora_inicial AS hora1,
            t.hora_final AS hora2,
            a.nombre AS asignatura,
            CONCAT(p.nombre, ' ', p.apellido) AS docente,
            h.dia AS dia,
            l.nombre AS local
        FROM horarios h, tiempos t, asignaturas a, locales l, grados g, personal p
        WHERE h.id_tiempo = t.id_tiempo 
            AND h.id_asignatura = a.id_asignatura 
            AND h.id_local = l.id_local 
            AND h.id_grado = g.id_grado
            AND h.id_personal = p.id_personal
            AND (h.id_grupo_academico = ? OR h.id_grupo_tecnico = ? AND h.id_especialidad = ?)
            AND h.id_seccion = ?
            AND h.id_grado = ?
            AND h.dia = ?
        ORDER BY t.hora_inicial";
        $params = array($grupo_academico, $grupo_tecnico, $especialidad, $seccion, $grado, $dia);
        $data = Database::getRows($sql, $params);
        return $data;
    }

    $alumno = getAlumno("20140074");
    //OBTENER EL DÍA QUE SE QUIERE CONSULTAR
    $datetime = DateTime::createFromFormat('d/m/Y', '30/03/2018');
    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
    $nombreDia =  $dias[$datetime->format('w')];

    $claseDia = getDia($alumno[0][0], $alumno[0][1], $alumno[0][6], $alumno[0][3], $alumno[0][2], $nombreDia);
    
    foreach($claseDia as $clase){
        echo $clase[0] . " " . $clase[1] . "\n" . $clase[2] . "\n";
    }

?>