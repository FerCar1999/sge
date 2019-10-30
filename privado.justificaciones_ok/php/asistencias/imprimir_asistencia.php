<?php 
// importa la db
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
date_default_timezone_set('UTC');
// variables para los titulos
$tabla = "";$titulo ="Materia: ";$inicio ="";$fin ="";$contenido="";$observacion="";$numero_lista=0;
$dia = array('Lunes','Martes','Miercoles','Jueves','Viernes');
$numero_dia = date('N');
// consulta SQL
$sql='select lo.nombre as lugar,id_grupo_academico,id_grupo_tecnico,sec.nombre as seccion,gr.nombre as grado,asig.nombre as materia,TIME_FORMAT(ts.hora_inicial, "%H:%i") as hinicio,TIME_FORMAT(ts.hora_final, "%H:%i") as final, esp.nombre as especialidad, h.id_horario as id,h.inicio,sec.id_seccion,gr.id_grado,esp.id_especialidad, asig.id_asignatura,h.tipo
from horarios h,locales lo,secciones sec,grados gr,asignaturas asig, tiempos ts, especialidades esp 
where h.id_personal='.$_SESSION["id_personal"].' and dia="'.$dia[$numero_dia-1].'" and h.id_local=lo.id_local and h.id_seccion= sec.id_seccion and 
h.id_grado = gr.id_grado and h.id_asignatura=asig.id_asignatura and h.id_tiempo=ts.id_tiempo and h.id_especialidad=esp.id_especialidad and CURDATE() between h.inicio and h.fin and date(now()) between h.inicio and h.fin ';

if(isset($_SESSION["pk_horario"])){
	$sql.= 'and h.id_horario = '.$_SESSION["pk_horario"].' order by ts.hora_inicial asc limit 1';
}else {
	$sql.= 'and TIME_TO_SEC((select now())) between TIME_TO_SEC(ts.hora_inicial) and TIME_TO_SEC(ts.hora_final) order by ts.hora_inicial asc limit 1';
}
// obtiene la informacion de la clase
$data = Database::getRows($sql, array());
$especialidad_tabla = "";
foreach ($data as $row) {	

	// variables para imprimir la tabla
	$grupo = "";$tipo="";$id = "";$inicio= $row['hinicio'];$fin = $row['final'];

	// verifica que tipo de grupo es  
	if($row['id_grupo_tecnico'] == null){
		if($row['id_grupo_academico'] == null){
			$grupo = "No aplica";
			// asigna el titulo
			$titulo.=$row['materia'].', '.$row['grado'].' '.$row['seccion']; 
		}else{
			$grupodata = Database::getRow('select nombre from grupos_academicos  where id_grupo_academico=?', array($row['id_grupo_academico']));
			$grupo= $grupodata['nombre'];
			$tipo="academico";
			// asigna el titulo
			$titulo.=$row['materia'].', '.$row['grado'].' '.$row['seccion'].$grupo; 
		}
	}else
	{ 
		$grupodata = Database::getRow('select nombre from grupos_tecnicos  where id_grupo_tecnico=?', array($row['id_grupo_tecnico']));
		$grupo= $grupodata['nombre'];
		$tipo="tecnico";
		// asigna el titulo
		$titulo.=$row['materia'].', '.$row['grado'].' '.$row['especialidad'].' '.$row['seccion'].' '.$grupo; 
	}
	// en caso que sea tercer ciclo
	if($grupo ==="No aplica"){	
		// obtiene los alumnos del grado y seccion
		$alumnos = Database::getRows('select est.codigo as codigo, est.nombres as nombres, est.apellidos as apellidos,est.id_estudiante as id,foto id from estudiantes est where  est.id_seccion = ? and est.id_grado = ?  and est.estado="Activo" and est.id_especialidad=?  order by est.apellidos desc',array($row['id_seccion'],$row['id_grado'],$row['id_especialidad']));
		foreach ($alumnos as $estudiante) {
			// va obtener si se ha pasado lista anteriormente
			$nombre =$estudiante['apellidos'].', '. $estudiante['nombres']; $codigo=$estudiante['codigo']; $especialidad = $row['especialidad'];$id=$estudiante['id'];$id_horario = $row['id'];$foto = $estudiante['foto'];
			$guardados = Database::getRow(' select id_estudiante,contenido,observacion from asistencias where id_horario = ? and TIME_TO_SEC((fecha_hora)) between TIME_TO_SEC(?) and TIME_TO_SEC(?) and id_estudiante =? and DATE(fecha_hora) = CURDATE()',array($id_horario,$row['hinicio'],$row['final'],$id));
			// obtiene si el alumno tiene lleda tarde
			$llegada_tarde = Database::getRow('select id_estudiante from impuntualidad where id_horario = ? and TIME_TO_SEC((fecha_hora)) between TIME_TO_SEC(?) and TIME_TO_SEC(?) and id_estudiante =? and DATE(fecha_hora) = CURDATE()',array($id_horario,$row['hinicio'],$row['final'],$id));

			// atributos de los checkbox
			$atributo ="no";$checked="";$suspendido=""; 	
			$atributo_tarde ="no";$checked_tarde="";
			// si se ha pasado lista
			if($guardados['id_estudiante']!=null)
			{
				$atributo = "si";	
				$checked = "checked";
				$contenido = $guardados['contenido'];
				$observacion = $guardados['observacion'];
			} 
			if($llegada_tarde['id_estudiante']!=null){
				$atributo_tarde = "si";	
				$checked_tarde = "checked";
			}

			$justificado_bloque=Database::getRow("select count(*) as cantidad from bloques_justificados where fecha = CURDATE() and id_estudiante = ? and id_horario = ?",array($id,$id_horario));
			$ausencia_previa=Database::getRow("select count(*) as cantidad from ausencias_justificadas where id_estudiante = ? and CURDATE() between inicio and fin",array($id));
			$suspendidoData=Database::getRow("select s.id_suspendido from suspendidos s where s.id_estudiante=(select id_estudiante from estudiantes where codigo =?) and CURDATE() between s.inicio and s.fin",array($codigo));

			if($suspendidoData['id_suspendido']!=null) $suspendido= "disabled";
			if(intval($justificado_bloque['cantidad'])>0) $suspendido= "disabled";
			if(intval($ausencia_previa['cantidad'])>0) $suspendido= "disabled";

			if($suspendido==="disabled"){
				$tabla.= "<tr><td>$codigo</td><td>$nombre</td><td><input  id='$id' id_horario='$id_horario' guardado='$atributo' class='access_check' type='checkbox' $checked $suspendido>> <label id='$id' for='$id'></td><td><input pk='$id'  id='$codigo' id_horario='$id_horario'  guardado='$atributo_tarde' class='access_check_tarde' type='checkbox' $checked_tarde $suspendido> <label id='$id' for='$codigo'></td><td><a class='btn-floating green' onclick='mostrarModalAula(\"$codigo\",\"$nombre\",\"$foto\",\"$id_horario\");' ><i class='material-icons'>gavel</i></a>   </td>";
			} else {
				// imprime la tabla
				$tabla.= "<tr><td>$codigo</td><td>$nombre</td><td><input  id='$id' id_horario='$id_horario' guardado='$atributo' class='access_check' type='checkbox' $checked> <label id='$id' for='$id'></td><td><input pk='$id'  id='$codigo' id_horario='$id_horario'  guardado='$atributo_tarde' class='access_check_tarde' type='checkbox' $checked_tarde $suspendido> <label id='$id' for='$codigo'></td><td><a class='btn-floating green' onclick='mostrarModalAula(\"$codigo\",\"$nombre\",\"$foto\",\"$id_horario\");' ><i class='material-icons'>gavel</i></a>   </td>";
			}
			
		}

	}
	if($tipo==="academico"){
		$alumnos = Database::getRows('select est.codigo as codigo, est.nombres as nombres, est.apellidos as apellidos,est.id_estudiante as id,foto,espe.nombre as espe from estudiantes est, especialidades espe  where  est.id_seccion = ? and est.id_grado = ? and est.estado="Activo"  and est.id_grupo_academico=?  and est.id_especialidad = espe.id_especialidad order by  espe.nombre asc , est.apellidos asc',array($row['id_seccion'],$row['id_grado'],$row['id_grupo_academico']));$id_horario = $row['id'];
		foreach ($alumnos as $estudiante) {


			$numero_lista++;	
			$nombre =$estudiante['apellidos'].', '. $estudiante['nombres']; $codigo=$estudiante['codigo']; $especialidad = $estudiante['espe'];$id=$estudiante['id'];$id_horario = $row['id'];$foto = $estudiante['foto'];

			$guardados = Database::getRow(' select id_estudiante,contenido,observacion from asistencias where id_horario = ? and TIME_TO_SEC((fecha_hora)) between TIME_TO_SEC(?) and TIME_TO_SEC(?) and id_estudiante =? and DATE(fecha_hora) = CURDATE() group by id_estudiante',array($id_horario,$row['hinicio'],$row['final'],$id));
			
			$llegada_tarde = Database::getRow('select id_estudiante from impuntualidad where id_horario = ? and TIME_TO_SEC((fecha_hora)) between TIME_TO_SEC(?) and TIME_TO_SEC(?) and id_estudiante =? and DATE(fecha_hora) = CURDATE()',array($id_horario,$row['hinicio'],$row['final'],$id));

			$atributo ="no";$checked="";$suspendido=""; 
			$atributo_tarde ="no";$checked_tarde="";
			if($guardados['id_estudiante']!=null)
			{
				$atributo = "si";	
				$checked = "checked";
				$contenido = $guardados['contenido'];
				$observacion = $guardados['observacion'];
			} 
			if($llegada_tarde['id_estudiante']!=null){
				$atributo_tarde = "si";	
				$checked_tarde = "checked";
			}
			if($especialidad_tabla !=$especialidad){
				$tabla .= "<tr><td class='td_center' COLSPAN=6>".$especialidad."</td></tr>";
				$especialidad_tabla = $especialidad;
			}
			$justificado_bloque=Database::getRow("select count(*) as cantidad from bloques_justificados where fecha = CURDATE() and id_estudiante = ? and id_horario = ?",array($id,$id_horario));
			$ausencia_previa=Database::getRow("select count(*) as cantidad from ausencias_justificadas where id_estudiante = ? and CURDATE() between inicio and fin",array($id));
			$suspendidoData=Database::getRow("select s.id_suspendido from suspendidos s where s.id_estudiante=(select id_estudiante from estudiantes where codigo =?) and CURDATE() between s.inicio and s.fin",array($codigo));
			$estado_ausencia = "(Suspensión)";

			if($suspendidoData['id_suspendido']!=null) $suspendido= "disabled";
			if(intval($justificado_bloque['cantidad'])>0){
				$suspendido= "disabled";
				$estado_ausencia = "(Justificación Previa)";
			} 
			if(intval($ausencia_previa['cantidad'])>0) {
				$suspendido= "disabled";
				$estado_ausencia = "(Justificación Previa)";
			}

			if($suspendido==="disabled"){
				$tabla.= "<tr ><td>$numero_lista</td><td>$codigo</td><td>$nombre $estado_ausencia</td><td><input  type='checkbox' $checked $suspendido> <label ></td><td><input pk='$id'  id='$codigo' id_horario='$id_horario'  guardado='$atributo_tarde' type='checkbox' $checked_tarde $suspendido> <label id='$id' for='$codigo'></td><td><a class='btn-floating green' onclick='mostrarModalAula(\"$codigo\",\"$nombre\",\"$foto\",\"$id_horario\");'><i class='material-icons' >gavel</i></a>    </td>";
			}
			else{
				$tabla.= "<tr><td>$numero_lista</td><td>$codigo</td><td>$nombre</td><td><input  id='$id' id_horario='$id_horario' guardado='$atributo' class='access_check' type='checkbox' $checked> <label id='$id' for='$id'></td><td><input pk='$id'  id='$codigo' id_horario='$id_horario'  guardado='$atributo_tarde' class='access_check_tarde' type='checkbox' $checked_tarde $suspendido> <label id='$id' for='$codigo'></td><td><a class='btn-floating green' onclick='mostrarModalAula(\"$codigo\",\"$nombre\",\"$foto\",\"$id_horario\");'><i class='material-icons' >gavel</i></a>   </td>";
			}
		}
	}
	else if($tipo==="tecnico"){

		$sql_estudiantes = 'select est.codigo as codigo, est.nombres as nombres, est.apellidos as apellidos,est.id_estudiante as id,foto from estudiantes est where ';

			$params =  array();
			// verificar si desea todo el grupo
			if($row['tipo']==="Seccion"){
				$sql_estudiantes.='est.id_seccion = ? and est.estado="Activo" and est.id_grado = ?  and est.id_especialidad=? and est.id_grupo_tecnico=?  order by est.apellidos asc';
				 $params = array($row['id_seccion'],$row['id_grado'],$row['id_especialidad'],$row['id_grupo_tecnico']);	
			} 
			else{
				$sql_estudiantes.='est.id_grado = ? and est.estado="Activo"  and est.id_especialidad=? and est.id_grupo_tecnico=? order by est.apellidos asc';	
				
				$params = array($row['id_grado'],$row['id_especialidad'],$row['id_grupo_tecnico']);	
				
				$titulo="Materia:".' '.$row['materia'].', '.$row['grado'].' '.$row['especialidad'].' '.$grupo.'; Grupo Completo'; 
			} 


		
		$alumnos = Database::getRows($sql_estudiantes,$params);
		foreach ($alumnos as $estudiante) {
			$numero_lista++;	
			$nombre =$estudiante['apellidos'].', '. $estudiante['nombres']; $codigo=$estudiante['codigo']; $especialidad = $row['especialidad'];$id=$estudiante['id'];$id_horario = $row['id'];$foto = $estudiante['foto'];
			$guardados = Database::getRow(' select id_estudiante,contenido,observacion from asistencias where id_horario = ? and TIME_TO_SEC((fecha_hora)) between TIME_TO_SEC(?) and TIME_TO_SEC(?) and id_estudiante =? and DATE(fecha_hora) = CURDATE() group by id_estudiante',array($id_horario,$row['hinicio'],$row['final'],$id));

			$llegada_tarde = Database::getRow('select id_estudiante from impuntualidad where id_horario = ? and TIME_TO_SEC((fecha_hora)) between TIME_TO_SEC(?) and TIME_TO_SEC(?) and id_estudiante =? and DATE(fecha_hora) = CURDATE()',array($id_horario,$row['hinicio'],$row['final'],$id));


			$atributo ="no";$checked="";$suspendido=""; 
			$atributo_tarde ="no";$checked_tarde="";


			if($guardados['id_estudiante']!=null)
			{
				$atributo = "si";	
				$checked = "checked";
				$contenido = $guardados['contenido'];
				$observacion = $guardados['observacion'];
			} 

			if($llegada_tarde['id_estudiante']!=null){
				$atributo_tarde = "si";	
				$checked_tarde = "checked";
			}
			/*if($especialidad_tabla !=$row['especialidad']){
				$tabla .= "<tr><td class='td_center' COLSPAN=6>".$row['especialidad']."</td></tr>";
				$especialidad_tabla = $row['especialidad'];
			}*/
			$justificado_bloque=Database::getRow("select count(*) as cantidad from bloques_justificados where fecha = CURDATE() and id_estudiante = ? and id_horario = ?",array($id,$id_horario));
			$ausencia_previa=Database::getRow("select count(*) as cantidad from ausencias_justificadas where id_estudiante = ? and CURDATE() between inicio and fin",array($id));
			$suspendidoData=Database::getRow("select s.id_suspendido from suspendidos s where s.id_estudiante=(select id_estudiante from estudiantes where codigo =?) and CURDATE() between s.inicio and s.fin",array($codigo));
			$estado_ausencia = "(Suspensión)";

			if($suspendidoData['id_suspendido']!=null) $suspendido= "disabled";
			if(intval($justificado_bloque['cantidad'])>0){
				$suspendido= "disabled";
				$estado_ausencia = "(Justificación Previa)";
			} 
			if(intval($ausencia_previa['cantidad'])>0) {
				$suspendido= "disabled";
				$estado_ausencia = "(Justificación Previa)";
			}
			
			if($suspendido==="disabled"){
				$tabla.= "<tr><td>$numero_lista</td><td>$codigo</td><td>$nombre $estado_ausencia</td><td><input    guardado='$atributo' type='checkbox' $checked $suspendido> <label id='$id' for='$id'></td><td><input  guardado='$atributo_tarde'  type='checkbox' $checked_tarde $suspendido> <label id='$id' for='$codigo'></td><td><a class='btn-floating green' onclick='mostrarModalAula(\"$codigo\",\"$nombre\",\"$foto\",\"$id_horario\");'><i class='material-icons'>gavel</i></a>   </td>";
			}
			else{
				$tabla.= "<tr><td>$numero_lista</td><td>$codigo</td><td>$nombre</td><td><input  id='$id' id_horario='$id_horario'  guardado='$atributo' class='access_check' type='checkbox' $checked $suspendido> <label id='$id' for='$id'></td><td><input pk='$id'  id='$codigo' id_horario='$id_horario'  guardado='$atributo_tarde' class='access_check_tarde' type='checkbox' $checked_tarde $suspendido> <label id='$id' for='$codigo'></td><td><a class='btn-floating green' onclick='mostrarModalAula(\"$codigo\",\"$nombre\",\"$foto\",\"$id_horario\");'><i class='material-icons'>gavel</i></a>   </td>";
			}
		}
		
	}

}

echo '<input type="hidden" id="inicio" value="'.$inicio.'">';
echo '<input type="hidden" id="fin" value="'.$fin.'">';
?>