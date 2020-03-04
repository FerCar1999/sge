<?php 
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	$data=Database::getRows("select e.codigo,e.nombres,e.apellidos,g.nombre as grado,es.nombre as espe,i.id_inasistencia,g.id_nivel as nivel,s.nombre as seccion,ga.nombre as grupo_academico,gt.nombre as grupo_tecnico,i.fecha from grupos_academicos ga,grupos_tecnicos gt,secciones s,estudiantes e, grados g, especialidades es, inasistencias_totales i where  i.id_estudiante= e.id_estudiante and i.id_estudiante= (select id_estudiante from estudiantes where codigo=?)  and e.id_seccion=s.id_seccion and e.id_grupo_academico = ga.id_grupo_academico and e.id_grupo_tecnico = gt.id_grupo_tecnico and e.id_grado=g.id_grado and e.id_especialidad=es.id_especialidad ",array($id));
	$jsondataList = array();
	foreach($data as $row)
	{
		$data_justificar = array();
		
		$especialidad = $row['espe'];
		$grupoa = $row['grupo_academico'];
		$grupot = $row['grupo_tecnico'];
		$seccion = $row['seccion'];
		$nivel = $row['nivel']; 
		$grado = $row['grado'];
		$fecha = $row['fecha'];		
		//bachillerato
		if(intval($nivel) == 2){
			$grado.= " ".$seccion.$grupoa;
			$especialidad.= " Grupo ".$grupot;
		}
		// tercer ciclo
		else{
			$grado.=" ".$seccion;
		}
		
		$data_justificar['id']=$row["id_inasistencia"];
		$data_justificar["nombre"] = $row["apellidos"].", ".$row['nombres'];
		$data_justificar["codigo"] = $row['codigo']; 
		$data_justificar["grado"] = $grado;
		$data_justificar["espe"] = $especialidad;
		$data_justificar["fecha"] = $fecha; 				
		$jsondataList[]=$data_justificar;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	

 ?>