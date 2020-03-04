<?php 
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	class horariosAlumno
	{
		private static $lunes = array();
		private static $martes = array();
		private static $miercoles = array();
		private static $jueves = array();
		private static $viernes = array();
		private static $formatLunes = array();
		private static $formatMartes = array();
		private static $formatMiercoles = array();
		private static $formatJueves = array();
		private static $formatViernes = array();

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
						CONCAT(g.nombre,' ', s.nombre)) AS encabezadoAcad
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

		function getTiempos(){
			$sql = "SELECT 
						id_tiempo,
						hora_inicial,
						hora_final
					FROM tiempos
					WHERE estado=?
					ORDER BY hora_inicial";
			$params = array('Activo');
            $data = Database::getRows($sql, $params);
            return $data;
		}
			
		function clases($grupo_tecnico = "", $grupo_academico = "", $grado, $seccion)
		{
			$sql = "";
			$params = array();
			if ($grupo_academico === "") {
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
					    AND h.id_grupo_tecnico = ?
					    AND h.id_seccion = ?
					    AND h.id_grado = ?
					ORDER BY t.hora_inicial";
					$params = array($grupo_tecnico, $seccion, $grado);
			}else if ($grupo_tecnico === "") {
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
					    AND h.id_grupo_academico = ?
					    AND h.id_seccion = ?
					    AND h.id_grado = ?
					ORDER BY t.hora_inicial";
					$params = array($grupo_academico, $seccion, $grado);
			}
			$data = Database::getRows($sql, $params);
        	return $data;
		}
		
		function horarioAlumno($grupo_tecnico = "", $grupo_academico = "", $grado, $seccion){
			$clases = self::clases($grupo_tecnico, $grupo_academico, $grado, $seccion);
			foreach ($clases as $clase) {
				if ($clase["dia"] == "Lunes") {
					array_push(self::$lunes, $clase);
				}
				if ($clase["dia"] == "Martes") {
					array_push(self::$martes, $clase);
				}
				if ($clase["dia"] == "Miércoles" || $clase["dia"] == "Miercoles") {
					array_push(self::$miercoles, $clase);
				}
				if ($clase["dia"] == "Jueves") {
					array_push(self::$jueves, $clase);
				}
				if ($clase["dia"] == "Viernes") {
					array_push(self::$viernes, $clase);
				}
			}
		}

		//Dia debe ser un array
		function formatDay($dia){
			$formatDay = array();
			$horas = self::getTiempos();
			$m = 0;
			for ($i = 0; $i < count($horas); $i++) { 
				if ($m < count($dia)) {
					if (!($dia[$m][0] == $horas[$i][1])) {
						//CUANDO TENGA UNA HORA LIBRE Y AUN HAYAN DATOS EN EL ARRAY $dia[]
						array_push($formatDay, array($horas[$i][1],$horas[$i][2],"","",""));
					}else{
						$docente = explode(" ", $dia[$m][3]);
						$nombreD = $docente[0] . " " . $docente[2];
						//CUANDO TENGA UNA CLASE
						array_push($formatDay,array($dia[$m][0],$dia[$m][1],$dia[$m][2],$nombreD,$dia[$m][5]));
						$m++;
					}
				}else{
					//CUANDO TENGA UNA HORA LIBRE Y NO HAYAN DATOS EN EL ARRAY $dia[]
					array_push($formatDay, array($horas[$i][1],$horas[$i][2],"","",""));
				}
			}
			return $formatDay;
		}

		//Dia debe ser un numero
		function getDia($dia){
			if ($dia == 1) {
				return self::formatDay(self::$lunes);
			}
			if ($dia == 2) {
				return self::formatDay(self::$martes);
			}
			if ($dia == 3) {
				return self::formatDay(self::$miercoles);
			}
			if ($dia == 4) {
				return self::formatDay(self::$jueves);
			}
			if ($dia == 5) {
				return self::formatDay(self::$viernes);
			}
		}

		function formatBloque($lunes,$martes,$miercoles,$jueves,$viernes){
			$formatBloques = array();
			$horas = self::getTiempos();
			for ($i = 0; $i < count($horas); $i++) { 
				if ($lunes[$i][0] == $horas[$i][1]) {
					array_push($formatBloques, $lunes[$i]);
				}
				if ($martes[$i][0] == $horas[$i][1]) {
					array_push($formatBloques, $martes[$i]);	
				}
				if ($miercoles[$i][0] == $horas[$i][1]) {
					array_push($formatBloques, $miercoles[$i]);
				}
				if ($jueves[$i][0] == $horas[$i][1]) {
					array_push($formatBloques, $jueves[$i]);
				}
				if ($viernes[$i][0] == $horas[$i][1]) {
					array_push($formatBloques, $viernes[$i]);
				}
			}
			return $formatBloques;
		}
	}

	/*horariosAlumno::horarioAlumno(1,"",8,1);
	$lunes = horariosAlumno::getDia(1);
    $martes = horariosAlumno::getDia(2);
    $miercoles = horariosAlumno::getDia(3);
    $jueves = horariosAlumno::getDia(4);
    $viernes = horariosAlumno::getDia(5);

    $bloques = horariosAlumno::formatBloque($lunes,$martes,$miercoles,$jueves,$viernes);

    for ($i=0; $i < count($bloques); $i++) { 
    	echo "Numero ".$i;
    	echo "****";
		echo $bloques[$i][0];
		echo "****";
		echo $bloques[$i][1];
		echo "****";
		echo $bloques[$i][2];
		echo "****";
		echo $bloques[$i][3];
		echo "****";
		echo $bloques[$i][4];
		echo "****";
	}*/

?>