<?php 
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	class listas
	{
		//LISTA ACADEMICA DE BACHILLERATO
		function listaAcademica($id_grado, $id_seccion, $id_grupo_academico)
		{
			$sql = "
				SELECT 
					e.codigo AS carnet,
					CONCAT(e.apellidos, ', ', e.nombres) AS alumno,
					ep.nombre AS especialidad,
					s.nombre AS seccion,
					ga.nombre AS grupoAcademico,
					gt.nombre AS grupoTecnico,
					g.nombre AS grado
				FROM estudiantes e, secciones s, grupos_academicos ga, grupos_tecnicos gt, especialidades ep, grados g
				WHERE e.id_grupo_academico = ?
					AND e.id_grado = ?
					AND e.id_seccion = ?
					AND e.id_seccion = s.id_seccion
					AND e.id_grado = g.id_grado
					AND e.id_especialidad = ep.id_especialidad
					AND e.id_grupo_academico = ga.id_grupo_academico
					AND e.id_grupo_tecnico = gt.id_grupo_tecnico
				ORDER BY ep.nombre ASC,
					gt.nombre ASC,
					ga.nombre ASC,
					e.apellidos ASC";
			$params = array($id_grupo_academico, $id_grado,$id_seccion);
            $data = Database::getRows($sql, $params);
            return $data;
		}
		//LISTA DE ESPECIALIDAD DE BACHILLERATO
		function listaEspecialidadBC($id_grado, $id_grupo_tecnico, $id_especialidad)
		{
			$sql = "
				SELECT 
					e.codigo AS carnet,
					CONCAT(e.apellidos, ', ', e.nombres) AS alumno,
					ep.nombre AS especialidad,
					s.nombre AS seccion,
					ga.nombre AS grupoAcademico,
					gt.nombre AS grupoTecnico,
					g.nombre AS grado
				FROM estudiantes e, secciones s, grupos_academicos ga, grupos_tecnicos gt, especialidades ep, grados g
				WHERE e.id_grupo_tecnico = ?
					AND e.id_grado = ?
					AND e.id_especialidad = ?
					AND e.id_seccion = s.id_seccion
					AND e.id_grado = g.id_grado
					AND e.id_especialidad = ep.id_especialidad
					AND e.id_grupo_academico = ga.id_grupo_academico
					AND e.id_grupo_tecnico = gt.id_grupo_tecnico
				ORDER BY s.nombre ASC,
					ga.nombre ASC,
					e.apellidos ASC";
			$params = array($id_grupo_tecnico,$id_grado,$id_especialidad);
            $data = Database::getRows($sql, $params);
            return $data;
		}
		//LISTA DE ALUMNOS DEL ITR
		function ListaGeneral()
		{
			$sql = "
				SELECT
				e.nombres as nombres,
				e.apellidos as apellidos,
				e.codigo as codigo,
				g.nombre as grado,
				ep.nombre as especialidad,
				IF(ep.nombre != 'Ninguna',
				   CONCAT('Grupo: ', gt.nombre),
				   CONCAT('Grupo: 1')) AS grupo,
				IF(ep.nombre != 'Ninguna',
				   CONCAT(s.nombre,'-' ,ga.nombre),
				   (s.nombre)) AS seccionAcad,
				IF(ep.nombre != 'Ninguna',
				   CONCAT(gt.nombre,s.nombre),
				   ('No asignado')) AS seccionTec
				FROM estudiantes e, grupos_academicos ga, grupos_tecnicos gt, grados g, secciones s, especialidades ep
				WHERE e.id_grupo_academico = ga.id_grupo_academico
				AND e.id_grupo_tecnico = gt.id_grupo_tecnico
				AND e.id_seccion = s.id_seccion
				AND g.id_grado = e.id_grado
				AND ep.id_especialidad = e.id_especialidad
				AND e.estado='Activo'
				ORDER BY g.id_grado ASC,
				ep.nombre ASC,
				gt.nombre ASC,
				s.nombre ASC,
				ga.nombre ASC,
				e.apellidos ASC";
			$params = array($id_grupo_tecnico,$id_grado,$id_especialidad);
            $data = Database::getRows($sql, $params);
            return $data;
		}
	}
?>