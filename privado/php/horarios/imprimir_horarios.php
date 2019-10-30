<?php 

function cargar_dia($dia){
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
//session_start();

	$sql='select lo.nombre as lugar,id_grupo_academico,id_grupo_tecnico,sec.nombre as seccion,gr.nombre as grado,asig.nombre as materia,TIME_FORMAT(ts.hora_inicial, "%H:%i") as hinicio,TIME_FORMAT(ts.hora_final, "%H:%i") as final, esp.nombre as especialidad, h.id_horario as id,h.inicio,h.tipo
	from horarios h,locales lo,secciones sec,grados gr,asignaturas asig, tiempos ts, especialidades esp 
	where h.id_personal='.$_SESSION["id_personal"].' and dia="'.$dia.'" and h.id_local=lo.id_local and h.id_seccion= sec.id_seccion and 
	h.id_grado = gr.id_grado and h.id_asignatura=asig.id_asignatura and h.id_tiempo=ts.id_tiempo and h.id_especialidad=esp.id_especialidad and DAYOFYEAR(h.inicio) < 2 order by ts.hora_inicial asc';

	$data = Database::getRows($sql, array());
	$table="";
	foreach($data as $row)
	{
		$materia=$row['materia'];
		$grupo ="";
		$id=$row['id']; 

		if($row['tipo']=="Grupo") $seccion = "Grupo Completo";
		else $seccion =$row['seccion'];

		$lugar =$row['lugar'];
		$grado= $row['grado'];
		$tiempo = $row['hinicio'].' - '.$row['final'];

	//if($row['inicio']!="0000-00-00") $materia="Modulo";
		if($row['id_grupo_tecnico'] == null){
			if($row['id_grupo_academico'] == null){
				$grupo = "No aplica";
			}else{
				$grupodata = Database::getRow('select nombre from grupos_academicos  where id_grupo_academico=?', array($row['id_grupo_academico']));
				$grupo= $grupodata['nombre'];
			}
		}else
		{ 
			$grupodata = Database::getRow('select nombre from grupos_tecnicos  where id_grupo_tecnico=?', array($row['id_grupo_tecnico']));
			$grupo= $grupodata['nombre'];
		}

		echo "<tr><td>$materia</td><td>$tiempo</td><td>$grado</td><td>$seccion</td><td>$grupo</td><td>$lugar</td><td><a onclick='remove_clase(\"$id\",$(this));'' class='btn-floating green'><i class='material-icons'>clear</i></a></td>";
	}

	$sql_modulos ='select lo.nombre as lugar,id_grupo_academico,id_grupo_tecnico,sec.nombre as seccion,gr.nombre as grado,asig.nombre as materia,TIME_FORMAT(ts.hora_inicial, "%H:%i") as hinicio,TIME_FORMAT(ts.hora_final, "%H:%i") as final, esp.nombre as especialidad, h.id_horario as id,h.inicio, ts.id_tiempo,h.dia,h.tipo
	from horarios h,locales lo,secciones sec,grados gr,asignaturas asig, tiempos ts, especialidades esp 
	where h.id_personal='.$_SESSION["id_personal"].' and dia="'.$dia.'" and h.id_local=lo.id_local and h.id_seccion= sec.id_seccion and 
	h.id_grado = gr.id_grado and h.id_asignatura=asig.id_asignatura and h.id_tiempo=ts.id_tiempo and h.id_especialidad=esp.id_especialidad and DAYOFYEAR(h.inicio) > 2  group by h.id_tiempo,h.dia  order by ts.hora_inicial asc';

	$modulos = Database::getRows($sql_modulos, array());
	foreach($modulos as $row)
	{
		$materia="Materia Modular";
		$grupo ="";
		$id=$row['id']; 
		if($row['tipo']=="Grupo") $seccion = "Grupo Completo";
		else $seccion =$row['seccion'];
		$id_tiempo =$row['id_tiempo'];
		$day =$row['dia'];
		$lugar =$row['lugar'];
		$grado= $row['grado'];
		$tiempo = $row['hinicio'].' - '.$row['final'];

	//if($row['inicio']!="0000-00-00") $materia="Modulo";
		if($row['id_grupo_tecnico'] == null){
			if($row['id_grupo_academico'] == null){
				$grupo = "No aplica";
			}else{
				$grupodata = Database::getRow('select nombre from grupos_academicos  where id_grupo_academico=?', array($row['id_grupo_academico']));
				$grupo= $grupodata['nombre'];
			}
		}else
		{ 
			$grupodata = Database::getRow('select nombre from grupos_tecnicos  where id_grupo_tecnico=?', array($row['id_grupo_tecnico']));
			$grupo= $grupodata['nombre'];
		}

		echo "<tr><td>$materia</td><td>$tiempo</td><td>$grado</td><td>$seccion</td><td>$grupo</td><td>$lugar</td><td><a onclick='detalles(\"$id\",\"$id_tiempo\",\"$day\",$(this));'' class='btn-floating green'><i class='material-icons'>search</i></a></td>";
	}
}
?>
