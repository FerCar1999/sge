<?php
	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	/*session_start();
	$pk_id = $_SESSION["id_personal"];*/

	class horariosDocente
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
		private static $rowspan = array();

		function encabezado($docente)
        {
            $sql = "SELECT 
                        CONCAT(nombre, ' ',apellido) AS nombre
                    FROM personal
                    WHERE id_personal = ?";
            $params = array($docente);
            $data = Database::getRow($sql, $params);
            return $data;
        }

		function getTiempos(){
			/*
			SELECT 
						id_tiempo,
						hora_inicial,
						hora_final
					FROM tiempos
					WHERE estado='Activo'
					AND
					(TIMEDIFF(hora_final,hora_inicial) = '00:45:00'
					OR
					TIMEDIFF(hora_final,hora_inicial) = '00:40:00')
					ORDER BY hora_inicial
			*/
			$sql = "SELECT 
						id_tiempo,
						hora_inicial,
						hora_final
					FROM tiempos
					WHERE estado=?
					AND
					(TIMEDIFF(hora_final,hora_inicial) = '00:45:00'
					OR
					TIMEDIFF(hora_final,hora_inicial) = '00:40:00')
					ORDER BY hora_inicial";
			$params = array('Activo');
            $data = Database::getRows($sql, $params);
            return $data;
		}
			
		function clases($pk)
		{
			/*
			CONSULTA SI SE QUIERE MOSTRAR UNICAMENTE LA CLASE DEL MODULO QUE SE VA A IMPARTIR
			SELECT 
				t.hora_inicial AS hora1,
				t.hora_final AS hora2,
				IF(h.inicio = CONCAT(YEAR(CURDATE()),'-01-01'),a.nombre,a.nombre) AS asignatura, 
				CONCAT(g.nombre, ' <b>', s.nombre, '</b>') AS grado, 
				h.dia AS dia,
				l.nombre AS local
			FROM horarios h, tiempos t, asignaturas a, locales l, grados g, secciones s
			WHERE h.id_tiempo = t.id_tiempo 
				AND h.id_asignatura = a.id_asignatura 
				AND h.id_local = l.id_local 
				AND h.id_grado = g.id_grado 
				AND h.id_seccion = s.id_seccion
				AND h.id_personal = ?
				AND CURDATE() BETWEEN h.inicio AND h.fin
			GROUP BY asignatura, hora1, hora2, dia
			ORDER BY t.hora_inicial*/
			/*$sql = "SELECT 
						t.hora_inicial AS hora1,
						t.hora_final AS hora2,
						IF(h.inicio = CONCAT(YEAR(CURDATE()),'-01-01'),a.nombre,'Materia modular') AS asignatura, 
						CONCAT(g.nombre, ' <b>', s.nombre, '</b>') AS grado, 
						h.dia AS dia,
						l.nombre AS local
					FROM horarios h, tiempos t, asignaturas a, locales l, grados g, secciones s
					WHERE h.id_tiempo = t.id_tiempo 
						AND h.id_asignatura = a.id_asignatura 
						AND h.id_local = l.id_local 
						AND h.id_grado = g.id_grado 
                        AND h.id_seccion = s.id_seccion
						AND h.id_personal = ?
					GROUP BY asignatura, hora1, hora2, dia
					ORDER BY t.hora_inicial";*/
			$sql = "SELECT 
						t.hora_inicial AS hora1,
						t.hora_final AS hora2,
						IF(h.inicio = CONCAT(YEAR(CURDATE()),'-01-01'),a.nombre,'Materia modular') AS asignatura, 
						IF(h.id_grupo_academico IS NOT NULL,
						   	IF(h.id_grupo_academico = 4,
						   		CONCAT(g.nombre, ' <b>', s.nombre, '</b>'), 
						   		CONCAT(g.nombre, ' <b>', s.nombre,'-',h.id_grupo_academico,'</b>')),
					   		CONCAT(g.nombre, ' <b>', h.id_grupo_tecnico ,s.nombre,'</b>')) AS grado,
						h.dia AS dia,
						l.nombre AS local
						FROM horarios h, tiempos t, asignaturas a, locales l, grados g, secciones s
						WHERE h.id_tiempo = t.id_tiempo 
						AND h.id_asignatura = a.id_asignatura 
						AND h.id_local = l.id_local 
						AND h.id_grado = g.id_grado 
						AND h.id_seccion = s.id_seccion
						AND h.id_personal = ?
						GROUP BY asignatura, hora1, hora2, dia
						ORDER BY t.hora_inicial";
			$params = array($pk);
			$data = Database::getRows($sql, $params);
        	return $data;
		}
		
		function horarioDocente($pk){
			$clases = self::clases($pk);
			foreach ($clases as $clase) {
				if ($clase["dia"] == "Lunes") {
					array_push(self::$lunes, $clase);
				}
				if ($clase["dia"] == "Martes") {
					array_push(self::$martes, $clase);
				}
				if ($clase["dia"] == "Miercoles" || $clase["dia"] == "Miércoles") {
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
						//CUANDO TENGA UNA CLASE
						array_push($formatDay,array($dia[$m][0],$dia[$m][1],$dia[$m][2],$dia[$m][3],$dia[$m][5]));
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

	
	/*horariosDocente::horarioDocente(425);
	$lunes = horariosDocente::getDia(1);
    $martes = horariosDocente::getDia(2);
    $miercoles = horariosDocente::getDia(3);
    $jueves = horariosDocente::getDia(4);
    $viernes = horariosDocente::getDia(5);

	echo "//////////////////";

    $bloques = horariosDocente::formatBloque($lunes,$martes,$miercoles,$jueves,$viernes);

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
	}

	/*for ($i=0; $i < count($lunes); $i++) { 
		echo $lunes[$i][0];
		echo "****";
		echo $lunes[$i][1];
		echo "****";
		echo $lunes[$i][2];
		echo "****";
		echo $lunes[$i][3];
		echo "****";
	}*/
	//echo horariosDocente::getTiempos()[0][1];

	/*horariosDocente::horarioDocente($pk_id);
	$dia = horariosDocente::getDia(1);
	//horariosDocente::formatRows($dia);
	//echo horariosDocente::formatDay()[0][2];
	//$diaF = horariosDocente::formatDay($dia);

	$docente = horariosDocente::encabezado($pk_id);

	/*for ($i=0; $i < count($dia); $i++) { 
		echo $dia[$i][0];
		echo "****";
		echo $dia[$i][1];
		echo "****";
		echo $dia[$i][2];
		echo "****";
		echo $dia[$i][3];
		echo "****";
		echo $dia[$i][4];
		echo "****";
	}*/

	//echo count($dia);
	//echo $dia[1][4];
	

	// Ejemplo 1
	/*$pizza  = "porción1 porción2 porción3 porción4 porción5 porción6";
	$porciones = explode(" ", $pizza);
	echo $porciones[0]; // porción1*/
?>