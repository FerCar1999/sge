<?php 
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : "";

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	$data=Database::getRows("select e.codigo,e.nombres,e.apellidos,g.nombre as grado,es.nombre as espe,i.id_impuntualidad,i.id_horario,i.fecha_hora as fecha from estudiantes e, grados g, especialidades es, impuntualidad i where i.tipo=? and i.id_estudiante= e.id_estudiante and e.id_grado=g.id_grado and e.id_especialidad=es.id_especialidad and i.id_estudiante= (select id_estudiante from estudiantes where codigo=?) and i.estado='Injustificada'",array($tipo,$id));
	$jsondataList = array();
	foreach($data as $row)
	{
		$data_justificar = array();

		if($row['id_horario'] != null){
			$clase = Database::getRow('select  a.nombre from horarios h, asignaturas a where 		h.id_horario =? and  h.id_asignatura=a.id_asignatura',array($row['id_horario']));
			$data_justificar["materia"] = $clase['nombre']; 
		}else $data_justificar["materia"] = "No aplica"; 
		
		
		$data_justificar['id']=$row["id_impuntualidad"];
		$data_justificar["nombre"] = $row["apellidos"].", ".$row['nombres'];
		$data_justificar["codigo"] = $row['codigo']; 
		$data_justificar["grado"] = $row['grado']; 
		$data_justificar["espe"] = $row['espe']; 
		$data_justificar["fecha"] = $row['fecha']; 
		
		$jsondataList[]=$data_justificar;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	

 ?>