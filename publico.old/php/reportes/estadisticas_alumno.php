<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/reportes/conducta/reporte.php");
    class Estadistica
    {

        public static function getNivel($pk_alumno){
            $sql = "SELECT g.id_nivel FROM estudiantes e, grados g WHERE e.id_grado = g.id_grado AND e.codigo = ?";
            $params = array($pk_alumno);
            $data = Database::getRow($sql, $params);
            return $data[0];
        }

        public static function getPeriodo($nivel){
            $sql = "SELECT fecha_inicial AS inicio, fecha_final AS fin FROM etapas WHERE NOW() BETWEEN fecha_inicial AND fecha_final AND id_nivel = ?";
            $params = array($nivel);
            $data = Database::getRow($sql, $params);
            return $data;
        }

        public static function getCodigosNegativos($pk_alumno, $inicio, $fin){
            $numeroCN = 0;
            $sql = "SELECT COUNT(d.id_disciplina) FROM disciplina d, estudiantes e, codigos c, tipos_codigos t WHERE d.id_estudiante = e.id_estudiante AND d.id_codigo = c.id_codigo AND c.id_tipo_codigo = t.id_tipo_codigo AND e.codigo = ? AND t.nombre != 'Positivo' AND d.fecha_hora BETWEEN ? AND ?";
            $params = array($pk_alumno, $inicio, $fin);
            $data = Database::getRow($sql, $params);
            $numero = $data[0];
            echo $numero;
        }
        public static function getCodigosPositivos($pk_alumno, $inicio, $fin){
            $sql = "SELECT 
                        COUNT(d.id_disciplina) 
                    FROM disciplina d, estudiantes e, codigos c, tipos_codigos t
                    WHERE d.id_estudiante = e.id_estudiante 
                    AND d.id_codigo = c.id_codigo
                    AND c.id_tipo_codigo = t.id_tipo_codigo
                    AND e.codigo = ?
                    AND t.nombre = 'Positivo'
                    AND d.fecha_hora BETWEEN ? AND ?";
            $params = array($pk_alumno, $inicio, $fin);
            $data = Database::getRow($sql, $params);
            $numeroCP = $data[0];
            echo $numeroCP;
        }
        public static function getInasistenciaClase($pk_alumno, $inicio, $fin){
            $numeroIC = 0;
            $sqlBloque = "SELECT COUNT(id_bloque) FROM bloques_justificados b, estudiantes e WHERE b.id_estudiante = e.id_estudiante AND e.codigo = ? AND b.fecha BETWEEN ? AND ?";
            $params = array($pk_alumno, $inicio, $fin);
            $data = Database::getRow($sqlBloque, $params);
            $numeroIC += $data[0];
            $sqlClases = "SELECT COUNT(i.id_inasistencia) FROM inasistencias_clases i, estudiantes e WHERE i.id_estudiante = e.id_estudiante AND e.codigo = ? AND i.fecha_hora BETWEEN ? AND ?";
            $params = array($pk_alumno, $inicio, $fin);
            $data = Database::getRow($sqlClases, $params);
            $numeroIC += $data[0];
            echo $numeroIC;
        }
        public static function getInasistenciaTotal($pk_alumno, $inicio, $fin){
            $numeroIT = 0;
            $sqlAusencias = "SELECT a.inicio AS inicio, a.fin AS fin FROM ausencias_justificadas a, estudiantes e WHERE a.id_estudiante = e.id_estudiante AND e.codigo = ? AND inicio BETWEEN ? AND ?";
            $params = array($pk_alumno, $inicio, $fin);
            $data = Database::getRows($sqlAusencias, $params);
            foreach ($data as $key) {
                $date1 = strtotime($key['inicio']);
                $date2 = strtotime($key['fin']);
                $segundos = $date2 - $date1;
                $days = $segundos / 86400;
                $numeroIT += $days + 1;
            }
            $sqlTotal = "SELECT COUNT(i.fecha) FROM inasistencias_totales i, estudiantes e WHERE e.codigo = ? AND i.fecha BETWEEN ? AND ?";
            $params = array($pk_alumno, $inicio, $fin);
            $data = Database::getRow($sqlTotal, $params);
            $numeroIT += $data[0];
            echo $numeroIT;
        }
    }
?>