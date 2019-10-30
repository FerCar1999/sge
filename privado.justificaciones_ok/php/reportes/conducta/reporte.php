<?php 
    
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

    class reportesData
    {

        function encabezado($pk_alumno)
        {
            $sql = "SELECT 
                        CONCAT(e.nombres, ' ', e.apellidos) AS nombre,
                        e.codigo AS codigo,
                        IF(n.nombre = 'Bachillerato',
                            CONCAT(g.nombre,' de ',ep.nombre, '; <b>Grupo: </b> ',gt.nombre, '; <b>Sección: </b>' ,s.nombre,'-' ,ga.nombre),
                            CONCAT(g.nombre,'; <b>Sección: </b>',s.nombre,'; ',n.nombre)) AS especialidad,
                        e.foto AS foto,
                        n.id_nivel AS nivel
                    FROM niveles n, grados g, estudiantes e, secciones s, grupos_academicos ga, especialidades ep, grupos_tecnicos gt
                    WHERE n.id_nivel = g.id_nivel 
                    AND e.id_grado = g.id_grado
                    AND s.id_seccion = e.id_seccion
                    AND e.id_grupo_academico = ga.id_grupo_academico
					AND e.id_grupo_tecnico = gt.id_grupo_tecnico
                    AND e.id_especialidad = ep.id_especialidad
                    AND e.codigo = ?";
            $params = array($pk_alumno);
            $data = Database::getRow($sql, $params);
            return $data;
        }

        function alumnosGuia($docente){
            $sql = "SELECT
                        e.codigo
                    FROM estudiantes e
                    WHERE e.id_personal = ?";
            $params = array($docente);
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function bloquesJustificados($fecha_inicio, $fecha_fin, $pk_alumno){
            $sql = "SELECT 
                        a.nombre AS asignatura, 
                        b.fecha AS fecha
                FROM bloques_justificados b, estudiantes e, horarios h, asignaturas a 
                WHERE b.id_estudiante = e.id_estudiante
                AND b.id_horario = h.id_horario
                AND h.id_asignatura = a.id_asignatura
                AND b.fecha BETWEEN ? AND ?
                AND e.codigo = ?
                ORDER BY b.fecha";
                $params = array($fecha_inicio, $fecha_fin, $pk_alumno);
                $data = Database::getRows($sql, $params);
                return $data;
        }

        function ausenciasJustificadas($fecha_inicio, $fecha_fin, $pk_alumno){
            $sql = "SELECT CONCAT('Insistencia total - ',a.motivo) AS motivo, a.inicio AS fecha, a.fin, CONCAT('Justificada') as estado, CONCAT('normal') AS tipo
            FROM ausencias_justificadas a, estudiantes e 
            WHERE a.id_estudiante = e.id_estudiante 
            AND a.inicio BETWEEN ? AND ?
            AND a.fin BETWEEN ? AND ?
            AND e.codigo = ?
            ORDER BY a.inicio";
            $params = array($fecha_inicio, $fecha_fin, $fecha_inicio, $fecha_fin, $pk_alumno);
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function inasistencias($fecha_inicio = "", $fecha_fin = ""){
            if ($fecha_inicio === "") {
                $sql = "SELECT
                        CONCAT(e.nombres, ' ', e.apellidos) AS nombre,
                        e.codigo AS codigo,
                        i.fecha AS fecha,
                        i.estado AS estado,
                        IF(n.nombre = 'Bachillerato',
                            CONCAT(g.nombre,' - ', s.nombre, ga.nombre,' | ',ep.nombre, ' ', gt.nombre),
                            CONCAT(g.nombre, ' ', s.nombre)) AS grado
                    FROM estudiantes e, inasistencias_totales i, niveles n, grados g, especialidades ep, grupos_tecnicos gt, secciones s, grupos_academicos ga
                    WHERE e.id_estudiante = i.id_estudiante
                    AND n.id_nivel = g.id_nivel 
                    AND e.id_grado = g.id_grado
                    AND e.id_grupo_tecnico = gt.id_grupo_tecnico
                    AND e.id_especialidad = ep.id_especialidad
                    AND e.id_seccion = s.id_seccion
                    AND ga.id_grupo_academico = e.id_grupo_academico
                    AND i.fecha = CURRENT_DATE()
                    ORDER BY i.fecha";
            }else{
                $sql = "SELECT
                        CONCAT(e.nombres, ' ', e.apellidos) AS nombre,
                        e.codigo AS codigo,
                        i.fecha AS fecha,
                        i.estado AS estado,
                        IF(n.nombre = 'Bachillerato',
                            CONCAT(g.nombre,' - ', s.nombre, ga.nombre,' | ',ep.nombre, ' ', gt.nombre),
                            CONCAT(g.nombre, ' ', s.nombre)) AS grado
                    FROM estudiantes e, inasistencias_totales i, niveles n, grados g, especialidades ep, grupos_tecnicos gt, secciones s, grupos_academicos ga
                    WHERE e.id_estudiante = i.id_estudiante
                    AND n.id_nivel = g.id_nivel 
                    AND e.id_grado = g.id_grado
                    AND e.id_grupo_tecnico = gt.id_grupo_tecnico
                    AND e.id_especialidad = ep.id_especialidad
                    AND e.id_seccion = s.id_seccion
                    AND ga.id_grupo_academico = e.id_grupo_academico
                    AND i.fecha BETWEEN '" . $fecha_inicio . "' AND '". $fecha_fin . "'
                    ORDER BY i.fecha";
            }
            $params = array();
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function inasistenciasJustificadas($inicio, $fin, $nivel){
            $sql = "SELECT 
                        i.id_estudiante 
                    FROM inasistencias_totales i, grados g, estudiantes e 
                    WHERE g.id_grado = e.id_grado 
                    AND e.id_estudiante = i.id_estudiante 
                    AND i.fecha BETWEEN ? AND ?
                    AND g.id_nivel = ?
                    AND i.estado = ?
                    AND e.estado = ?
                    GROUP BY i.id_estudiante";
            $params = array($inicio, $fin, $nivel, 'Justificada', 'Activo');
            $data = Database::getRows($sql, $params);
            return $data;
        }
        
        function inasistenciasT($inicio, $fin, $nivel, $estado){
            $sql = "SELECT 
                        i.id_estudiante 
                    FROM inasistencias_totales i, grados g, estudiantes e 
                    WHERE g.id_grado = e.id_grado 
                    AND e.id_estudiante = i.id_estudiante 
                    AND i.fecha BETWEEN ? AND ?
                    AND g.id_nivel = ?
                    AND e.estado = ?
                    AND i.estado = ?
                    GROUP BY i.id_estudiante";
            $params = array($inicio, $fin, $nivel, 'Activo', $estado);
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function inasistenciasInjustificadas($inicio, $fin, $nivel){
            $sql = "SELECT 
                        i.id_estudiante 
                    FROM inasistencias_totales i, grados g, estudiantes e 
                    WHERE g.id_grado = e.id_grado 
                    AND e.id_estudiante = i.id_estudiante 
                    AND i.fecha BETWEEN ? AND ?
                    AND g.id_nivel = ?
                    AND i.estado = ?
                    AND e.estado = ?
                    GROUP BY i.id_estudiante";
            $params = array($inicio, $fin, $nivel, 'Injustificada', 'Activo');
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function inasistenciasClasesInjustificadas($inicio, $fin, $nivel){
            $sql = "SELECT 
                        i.id_estudiante 
                    FROM inasistencias_clases i, grados g, estudiantes e 
                    WHERE g.id_grado = e.id_grado 
                    AND e.id_estudiante = i.id_estudiante 
                    AND i.fecha_hora BETWEEN ? AND ?
                    AND g.id_nivel = ?
                    AND i.estado = ?
                    AND e.estado = ?
                    GROUP BY i.id_estudiante";
            /*echo $sql;
            echo "***";
            echo $inicio . "**" . $fin;*/
            $params = array($inicio, $fin, $nivel, 'Injustificada', 'Activo');
            $data = Database::getRows($sql, $params);
            return $data;
        }
        
        function inasistenciasClasesT($inicio, $fin, $nivel, $estado){
            $sql = "SELECT 
                        i.id_estudiante 
                    FROM inasistencias_clases i, grados g, estudiantes e 
                    WHERE g.id_grado = e.id_grado 
                    AND e.id_estudiante = i.id_estudiante 
                    AND i.fecha_hora BETWEEN ? AND ?
                    AND g.id_nivel = ?
                    AND e.estado = ?
                    AND i.estado = ?
                    GROUP BY i.id_estudiante";
            $params = array($inicio, $fin, $nivel, 'Activo', $estado);
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function inasistenciasClasesJustificadas($inicio, $fin, $nivel){
            $sql = "SELECT 
                        i.id_estudiante 
                    FROM inasistencias_clases i, grados g, estudiantes e 
                    WHERE g.id_grado = e.id_grado 
                    AND e.id_estudiante = i.id_estudiante 
                    AND i.fecha_hora BETWEEN ? AND ?
                    AND g.id_nivel = ?
                    AND i.estado = ?
                    AND e.estado = ?
                    GROUP BY i.id_estudiante";
            $params = array($inicio, $fin, $nivel, 'Justificada', 'Activo');
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function estudianteJustificado($id){
            $sql = "SELECT 
                        g.nombre AS grado, 
                        s.nombre AS especialidad, 
                        n.nombre AS seccion, 
                        a.nombre AS grupoAcad, 
                        e.codigo AS codigo, 
                        e.apellidos AS apellidos, 
                        e.nombres AS nombres
                        FROM estudiantes e, grados g, secciones n, grupos_academicos a, especialidades s
                        WHERE e.id_grado = g.id_grado 
                        AND e.id_seccion = n.id_seccion 
                        AND e.id_grupo_academico = a.id_grupo_academico 
                        AND e.id_especialidad = s.id_especialidad 
                        AND e.id_estudiante = ?";
            $params = array($id);
            $data = Database::getRow($sql, $params);
            return $data;
        }

        function estudianteJustificado2($id, $inicio, $fin){
            $sql = "SELECT 
                        g.nombre AS grado, 
                        s.nombre AS especialidad, 
                        n.nombre AS seccion, 
                        a.nombre AS grupoAcad, 
                        e.codigo AS codigo, 
                        e.apellidos AS apellidos, 
                        e.nombres AS nombres
                        #COUNT(i.id_inasistencia) 
                        FROM estudiantes e, grados g, secciones n, grupos_academicos a, especialidades s, inasistencias_totales i 
                        WHERE e.id_grado = g.id_grado 
                        AND e.id_seccion = n.id_seccion 
                        AND e.id_grupo_academico = a.id_grupo_academico 
                        AND e.id_especialidad = s.id_especialidad 
                        AND e.id_estudiante = i.id_estudiante 
                        AND e.id_estudiante = ?
                        AND i.fecha BETWEEN ? AND ?";
            $params = array($id, $inicio, $fin);
            $data = Database::getRow($sql, $params);
            return $data;
        }

        function estudianteJustificadoClase($id, $inicio, $fin){
            $sql = "SELECT 
                        g.nombre AS grado, 
                        s.nombre AS especialidad, 
                        n.nombre AS seccion, 
                        a.nombre AS grupoAcad, 
                        e.codigo AS codigo, 
                        e.apellidos AS apellidos, 
                        e.nombres AS nombres
                        #COUNT(i.id_inasistencia) 
                        FROM estudiantes e, grados g, secciones n, grupos_academicos a, especialidades s, inasistencias_clases i 
                        WHERE e.id_grado = g.id_grado 
                        AND e.id_seccion = n.id_seccion 
                        AND e.id_grupo_academico = a.id_grupo_academico 
                        AND e.id_especialidad = s.id_especialidad 
                        AND e.id_estudiante = i.id_estudiante 
                        AND e.id_estudiante = ?
                        AND i.fecha_hora BETWEEN ? AND ?";
            $params = array($id, $inicio, $fin);
            $data = Database::getRow($sql, $params);
            return $data;
        }

        function countJustificado($id, $inicio, $fin, $estado){
            $sql = "SELECT 
                        (i.fecha) 
                        FROM estudiantes e, grados g, secciones n, grupos_academicos a, especialidades s, inasistencias_totales i 
                        WHERE e.id_grado = g.id_grado 
                        AND e.id_seccion = n.id_seccion 
                        AND e.id_grupo_academico = a.id_grupo_academico 
                        AND e.id_especialidad = s.id_especialidad 
                        AND e.id_estudiante = i.id_estudiante 
                        AND e.id_estudiante = ?
                        AND i.fecha BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY)
                        AND i.estado = ?";
            $params = array($id, $inicio, $fin, $estado);
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function countJustificadoClase($id, $inicio, $fin, $estado){
            $sql = "SELECT 
                        (i.fecha_hora) 
                        FROM estudiantes e, grados g, secciones n, grupos_academicos a, especialidades s, inasistencias_clases i 
                        WHERE e.id_grado = g.id_grado 
                        AND e.id_seccion = n.id_seccion 
                        AND e.id_grupo_academico = a.id_grupo_academico 
                        AND e.id_especialidad = s.id_especialidad 
                        AND e.id_estudiante = i.id_estudiante 
                        AND e.id_estudiante = ?
                        AND i.fecha_hora BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY)
                        AND i.estado = ?";
            $params = array($id, $inicio, $fin, $estado);
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function estudianteInjustificado($id, $estado){
            $sql = "SELECT 
                        g.nombre AS grado, 
                        s.nombre AS especialidad, 
                        n.nombre AS seccion, 
                        a.nombre AS grupoAcad, 
                        e.codigo AS codigo, 
                        e.apellidos AS apellidos, 
                        e.nombres AS nombres, 
                        COUNT(i.id_inasistencia) 
                        FROM estudiantes e, grados g, secciones n, grupos_academicos a, especialidades s, inasistencias_totales i 
                        WHERE e.id_grado = g.id_grado 
                        AND e.id_seccion = n.id_seccion 
                        AND e.id_grupo_academico = a.id_grupo_academico 
                        AND e.id_especialidad = s.id_especialidad 
                        AND e.id_estudiante = i.id_estudiante 
                        AND e.id_estudiante = ?
                        AND i.estado = ?";
            $params = array($id, $estado);
            $data = Database::getRow($sql, $params);
            return $data;
        }

        function llegadasTardeInstitucionTotal($fecha_inicio = "", $fecha_fin = ""){
            if ($fecha_inicio != "") {
                $sql = "
                SELECT
                    CONCAT(e.nombres, ' ', e.apellidos) AS nombre,
                    e.codigo AS codigo,
                    i.fecha_hora AS fecha,
                    i.estado AS estado,
                    IF(n.nombre = 'Bachillerato',
                        CONCAT(g.nombre,' - ', s.nombre, ga.nombre,' | ',ep.nombre, ' ', gt.nombre),
                        CONCAT(g.nombre, ' ', s.nombre)) AS grado
                    FROM estudiantes e, impuntualidad i, niveles n, grados g, especialidades ep, grupos_tecnicos gt, secciones s, grupos_academicos ga
                    WHERE e.id_estudiante = i.id_estudiante
                    AND e.id_grado = g.id_grado
                    AND g.id_nivel = n.id_nivel
                    AND e.id_especialidad = ep.id_especialidad
                    AND e.id_grupo_tecnico = gt.id_grupo_tecnico
                    AND e.id_seccion = s.id_seccion
                    AND ga.id_grupo_academico = e.id_grupo_academico
                    AND i.id_horario IS NULL
                    AND i.fecha_hora BETWEEN '" . $fecha_inicio . "' AND DATE_ADD('". $fecha_fin . "',INTERVAL 1 DAY)
                    ORDER BY i.fecha_hora";
            }else{
                $sql = "SELECT
                        CONCAT(e.nombres, ' ', e.apellidos) AS nombre,
                        e.codigo AS codigo,
                        i.fecha_hora AS fecha,
                        i.estado AS estado,
                        IF(n.nombre = 'Bachillerato',
                            CONCAT(g.nombre,' - ', s.nombre, ga.nombre,' | ',ep.nombre, ' ', gt.nombre),
                            CONCAT(g.nombre, ' ', s.nombre)) AS grado
                    FROM estudiantes e, impuntualidad i, niveles n, grados g, especialidades ep, grupos_tecnicos gt, secciones s, grupos_academicos ga
                    WHERE e.id_estudiante = i.id_estudiante
                    AND e.id_grado = g.id_grado
                    AND g.id_nivel = n.id_nivel
                    AND e.id_especialidad = ep.id_especialidad
                    AND e.id_grupo_tecnico = gt.id_grupo_tecnico
                    AND e.id_seccion = s.id_seccion
                    AND ga.id_grupo_academico = e.id_grupo_academico
                    AND i.id_horario IS NULL
                    AND i.fecha_hora = CURRENT_DATE()
                    ORDER BY i.fecha_hora";
            }
            $params = array();
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function llegadasTardeInstitucion($pk_alumno,$inicio = "", $fin = ""){
            $sql = "SELECT
                        i.fecha_hora AS fecha,
                        i.estado AS estado
                    FROM estudiantes e, impuntualidad i
                    WHERE e.id_estudiante = i.id_estudiante
                    AND i.id_horario IS NULL
                    AND e.codigo = ?
                    AND i.fecha_hora BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY)";
            $params = array($pk_alumno,$inicio,$fin);
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function llegadasTardeClaseTotal($fecha_inicio = "", $fecha_fin = ""){
            if ($fecha_inicio != "") {
                $sql = "
                    SELECT 
                        CONCAT(e.nombres, ' ', e.apellidos) AS nombre,
                        e.codigo AS codigo,
                        i.fecha_hora AS fecha, 
                        i.estado AS estado,
                        a.nombre AS asignatura,
                        IF(n.nombre = 'Bachillerato',
                            CONCAT(g.nombre,' - ', s.nombre, ga.nombre,' | ',ep.nombre, ' ', gt.nombre),
                            CONCAT(g.nombre, ' ', s.nombre)) AS grado
                    FROM estudiantes e, impuntualidad i, horarios h, asignaturas a, niveles n, grados g, especialidades ep, grupos_tecnicos gt, secciones s, grupos_academicos ga
                    WHERE e.id_estudiante = i.id_estudiante 
                    AND i.id_horario IS NOT NULL 
                    AND h.id_horario = i.id_horario
                    AND h.id_asignatura = a.id_asignatura
                    AND e.id_grado = g.id_grado
                    AND g.id_nivel = n.id_nivel
                    AND e.id_especialidad = ep.id_especialidad
                    AND e.id_grupo_tecnico = gt.id_grupo_tecnico
                    AND e.id_seccion = s.id_seccion
                    AND ga.id_grupo_academico = e.id_grupo_academico
                    AND i.fecha_hora BETWEEN '" . $fecha_inicio . "' AND DATE_ADD('". $fecha_fin . "',INTERVAL 1 DAY)
                    ORDER BY i.fecha_hora";
            }else{
                $sql = "SELECT 
                        CONCAT(e.nombres, ' ', e.apellidos) AS nombre,
                        e.codigo AS codigo,
                        i.fecha_hora AS fecha, 
                        i.estado AS estado,
                        a.nombre AS asignatura,
                        IF(n.nombre = 'Bachillerato',
                            CONCAT(g.nombre,' - ', s.nombre, ga.nombre,' | ',ep.nombre, ' ', gt.nombre),
                            CONCAT(g.nombre, ' ', s.nombre)) AS grado
                    FROM estudiantes e, impuntualidad i, horarios h, asignaturas a, niveles n, grados g, especialidades ep, grupos_tecnicos gt, secciones s, grupos_academicos ga
                    WHERE e.id_estudiante = i.id_estudiante 
                    AND i.id_horario IS NOT NULL 
                    AND h.id_horario = i.id_horario
                    AND h.id_asignatura = a.id_asignatura
                    AND e.id_grado = g.id_grado
                    AND g.id_nivel = n.id_nivel
                    AND e.id_especialidad = ep.id_especialidad
                    AND e.id_grupo_tecnico = gt.id_grupo_tecnico
                    AND e.id_seccion = s.id_seccion
                    AND ga.id_grupo_academico = e.id_grupo_academico
                    AND i.fecha_hora = CURRENT_DATE()
                    ORDER BY i.fecha_hora";
            }
            $params = array();
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function llegadasTardeClase($pk_alumno, $inicio = "", $fin = ""){
            $sql = "SELECT 
                        i.fecha_hora AS fecha, 
                        i.estado AS estado,
                        a.nombre AS asignatura
                    FROM estudiantes e, impuntualidad i, horarios h, asignaturas a
                    WHERE e.id_estudiante = i.id_estudiante 
                    AND i.id_horario IS NOT NULL 
                    AND h.id_horario = i.id_horario
                    AND h.id_asignatura = a.id_asignatura
                    AND e.codigo = ?
                    AND i.fecha_hora BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY)
                    ORDER BY i.fecha_hora";
            $params = array($pk_alumno,$inicio,$fin);
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function inasistenciasAlumnoInst($pk_alumno,$fecha_inicio, $fecha_fin)
        {
            $sql = "SELECT 
                        it.id_inasistencia, 
                        it.fecha as fecha, 
                        it.estado as estado,
                        CONCAT('Inasistencia total') AS motivo,
                        CONCAT('normal') AS tipo
                    FROM
                        inasistencias_totales it, estudiantes e
                    WHERE e.codigo = ?
                    AND it.id_estudiante = e.id_estudiante
                    AND fecha BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY)
                    ORDER BY fecha";
            $params = array($pk_alumno, $fecha_inicio, $fecha_fin);
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function inasistenciasAlumnoClase($pk_alumno,$fecha_inicio, $fecha_fin)
        {  
            $sql = "SELECT 
                ic.id_inasistencia, 
                ic.fecha_hora as fecha, 
                ic.estado as estado,
                a.nombre as asignatura, 
                ic.tipo as tipo,
                CONCAT('Inasistencia a clase - ', a.nombre) as motivo
            FROM 
                inasistencias_clases ic, estudiantes e, asignaturas a, horarios h
            WHERE e.codigo = ?
            AND h.id_asignatura = a.id_asignatura
            AND ic.id_horario = h.id_horario
            AND ic.id_estudiante = e.id_estudiante
            AND fecha_hora BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY)
            ORDER BY fecha_hora";
            $params = array($pk_alumno, $fecha_inicio, $fecha_fin);
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function getEtapas($nivel){
            $sql = "SELECT 
                        nombre AS nombre,
                        fecha_inicial AS inicio, 
                        fecha_final AS fin
                    FROM etapas
                    WHERE id_nivel =?
                    AND estado = 'Activo'
                    ORDER BY fecha_inicial ASC";
            $params = array($nivel);
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function getNivel($id_nivel){
            $sql = "SELECT 
                        nombre
                    FROM niveles
                    WHERE id_nivel = ?";
            $params = array($id_nivel);
            $data = Database::getRow($sql, $params);
            return $data;
        }

        function GetEtapa($id_etapa){
            $sql = "SELECT 
                        nombre AS nombre,
                        fecha_inicial AS inicio, 
                        fecha_final AS fin
                    FROM etapas
                    WHERE id_etapa = ?";
            $params = array($id_etapa);
            $data = Database::getRow($sql, $params);
            return $data;
        }

        function codigosPositivos($pk_alumno,$inicio = "", $fin = ""){
            $sql = "SELECT 
                        dp.fecha_hora AS fecha,
                        c.nombre AS codigo,
                        CONCAT(p.nombre, ' ', p.apellido) AS docente,
                        IF(dp.id_horario IS NULL,'ExAula','Aula') AS horario
                    FROM codigos c, tipos_codigos tc, estudiantes e, disciplina dp, personal p
                    WHERE c.id_tipo_codigo = tc.id_tipo_codigo 
                    AND tc.escala = 0 
                    AND c.id_codigo = dp.id_codigo
                    AND e.id_estudiante = dp.id_estudiante
                    AND p.id_personal = dp.id_personal
                    AND c.estado=?
                    AND e.codigo = ?
                    AND dp.fecha_hora BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY)";
            $params = array('Activo',$pk_alumno,$inicio,$fin);
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function codigosNegativos($pk_alumno,$inicio = "", $fin = ""){
            $sql = "SELECT 
                        dp.fecha_hora AS fecha,
                        c.nombre AS codigo,
                        CONCAT(p.nombre, ' ', p.apellido) AS docente,
                        tc.nombre AS tipo,
                        IF(dp.id_horario IS NULL,'ExAula','Aula') AS horario
                    FROM codigos c, tipos_codigos tc, estudiantes e, disciplina dp, personal p
                    WHERE c.id_tipo_codigo = tc.id_tipo_codigo
                    AND tc.escala != 0
                    AND c.id_codigo = dp.id_codigo
                    AND e.id_estudiante = dp.id_estudiante
                    AND p.id_personal = dp.id_personal
                    AND c.estado=?
                    AND e.codigo=?
                    AND dp.fecha_hora BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY)
                    ORDER BY dp.fecha_hora";
            $params = array('Activo',$pk_alumno,$inicio,$fin);
            $data = Database::getRows($sql, $params);
            return $data;
        }
        

        function enfermeria($pk_alumno,$inicio = "", $fin = ""){
            $sql = "SELECT 
                    ef.fecha_hora AS fecha,
                    ef.observacion AS observacion
                FROM enfermeria ef, estudiantes e
                WHERE ef.id_estudiante = e.id_estudiante
                AND e.codigo = ?
                AND ef.fecha_hora BETWEEN ? AND ?
                ORDER BY ef.fecha_hora";
            $params = array($pk_alumno,$inicio,$fin);
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function observaciones($pk_alumno,$inicio = "", $fin = ""){
            $sql = "SELECT 
                        ob.fecha AS fecha,
                        ob.observacion AS observacion,
                        CONCAT(p.nombre, ' ', p.apellido) AS docente
                    FROM observaciones ob, estudiantes e, personal p
                    WHERE ob.id_estudiante = e.id_estudiante
                    AND ob.id_personal = p.id_personal
                    AND e.codigo = ?
                    AND ob.fecha BETWEEN ? AND ?
                    ORDER BY ob.fecha";
            $params = array($pk_alumno,$inicio,$fin);
            $data = Database::getRows($sql, $params);
            return $data;
        }

        function DiasSuspendidos($pk_alumno, $inicio, $fin){
            $sql = "SELECT 
                        s.inicio AS inicio, 
                        s.fin AS fin, 
                        s.observacion AS observacion
                        FROM suspendidos s, estudiantes e 
                        WHERE e.codigo = ? 
                        AND e.id_estudiante = s.id_estudiante
                        AND s.inicio BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY)";
            $params = array($pk_alumno,$inicio,$fin);
            $data = Database::getRows($sql, $params);
            return $data;
        }
    }


    function verificarInasistenciaTotal($fecha,$id_estudiante){
    date_default_timezone_set('UTC');
    $unixTimestamp = strtotime($fecha);
    $dayOfWeek = date("l", $unixTimestamp);
    $dia = "Lunes";

    switch ($dayOfWeek) {
        case 'Monday':
            $dia = "Lunes";
        break;
        case 'Tuesday':
            $dia = "Martes";
        break;
        case 'Wednesday':
            $dia = "Miercoles";
        break;
        case 'Thursday':
            $dia = "Jueves";
        break;
        case 'Friday':
            $dia = "Viernes";
        break;          
    }
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

    $alumno = Database::getRow('select * from estudiantes where id_estudiante =(select id_estudiante from estudiantes where codigo = ?)',array($id_estudiante));


    // obtiene materias academicas
    $materiasAcademicas = Database::getRows("SELECT 
                        t.hora_inicial AS hora1,
                        t.hora_final AS hora2,
                        a.nombre AS asignatura,
                        CONCAT(p.nombre, ' ', p.apellido) AS docente,
                        h.dia AS dia,
                        l.nombre AS local,
                        h.id_horario 
                    FROM horarios h, tiempos t, asignaturas a, locales l, grados g, personal p
                    WHERE h.id_tiempo = t.id_tiempo 
                        AND h.id_asignatura = a.id_asignatura 
                        AND h.id_local = l.id_local 
                        AND h.id_grado = g.id_grado
                        AND h.id_personal = p.id_personal
                        AND h.id_grupo_academico = ?
                        AND h.id_seccion = ?
                        AND h.id_grado = ?
                        AND h.id_especialidad = ?
                        AND h.dia = ?
                    ORDER BY t.hora_inicial",array($alumno['id_grupo_academico'],$alumno['id_seccion'],$alumno['id_grado'],$alumno['id_especialidad'],$dia));

    // obtiene materias tecnicas
    $materiasTecnicas = Database::getRows("SELECT 
                        t.hora_inicial AS hora1,
                        t.hora_final AS hora2,
                        a.nombre AS asignatura,
                        CONCAT(p.nombre, ' ', p.apellido) AS docente,
                        h.dia AS dia,
                        l.nombre AS local,
                        h.id_horario 
                    FROM horarios h, tiempos t, asignaturas a, locales l, grados g, personal p
                    WHERE h.id_tiempo = t.id_tiempo 
                        AND h.id_asignatura = a.id_asignatura 
                        AND h.id_local = l.id_local 
                        AND h.id_grado = g.id_grado
                        AND h.id_personal = p.id_personal
                        AND h.id_grupo_tecnico = ?
                        AND h.id_seccion = ?
                        AND h.id_grado = ?
                        AND h.id_especialidad = ?
                        AND h.dia = ?
                    ORDER BY t.hora_inicial
        ",array($alumno['id_grupo_tecnico'],$alumno['id_seccion'],$alumno['id_grado'],$alumno['id_especialidad'],$dia));


    $inasistencia_total = false;
    // materias tecnicas
    foreach ($materiasTecnicas as $key) {

            $asistencia = Database::getRow('select count(*) as cantidad from asistencias where date(fecha_hora) = ? and id_horario = ?',array($fecha,$key["id_horario"]));
            // si hay registros es porque si se ha pasado lista
            if(intval($asistencia['cantidad']) > 0) $inasistencia_total  = true;
    }
    // materias academicas      
        foreach ($materiasAcademicas as $key) {     

            $asistencia = Database::getRow('select count(*) as cantidad from asistencias where date(fecha_hora) = ? and id_horario = ?',array($fecha,$key["id_horario"]));

            // si hay registros es porque si se ha pasado lista
            if(intval($asistencia['cantidad']) > 0) $inasistencia_total  = true;

        }
//  echo "Fecha ".$fecha." dia de la semana ".$dia;
//  var_dump($inasistencia_total);
    return $inasistencia_total;
}
    //reportesData::llegadasTardeInstitucionTotal();
?>