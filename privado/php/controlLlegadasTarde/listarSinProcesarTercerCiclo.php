<?php 
$id_nivel = isset($_POST["id_nivel"]) ? $_POST["id_nivel"] : "";
$id_etapa = isset($_POST["id_etapa"]) ? $_POST["id_etapa"] : "";

require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

$periodos = Database::getRows('select id_etapa,fecha_inicial,fecha_final,nombre from etapas where id_nivel = ? and id_etapa = ?  and estado ="Activo"',array($id_nivel,$id_etapa));

foreach ($periodos as $periodo) {
	$tabla2 .= "<tr><td class='td_center' COLSPAN=8>".$periodo["nombre"]."</td></tr>";
	$estudiantes = Database::getRows('select count(*) as cantidad,e.codigo, e.apellidos,e.nombres,g.nombre as grado,es.nombre as especialidad, gt.nombre as gt,ga.nombre as ga,sec.nombre as seccion from impuntualidad i,estudiantes e,grupos_academicos ga,grupos_tecnicos gt,secciones s, grados g, especialidades es , secciones sec where i.id_estudiante = e.id_estudiante   and date(i.fecha_hora) between ? and ? and e.id_seccion=s.id_seccion and e.id_grupo_academico = ga.id_grupo_academico and e.id_grupo_tecnico = gt.id_grupo_tecnico and e.id_grado=g.id_grado and e.id_seccion = sec.id_seccion and e.id_especialidad=es.id_especialidad and i.estado="Injustificada" group by e.codigo  order by grado,cantidad desc ',array($periodo["fecha_inicial"],$periodo["fecha_final"]));

	$contador = 0;	
	foreach ($estudiantes as $key) {
		

		$estudiante = Database::getRow('select id_procesado from impuntualidad_procesados where id_estudiante=(select id_estudiante from estudiantes where codigo =?)',array($key['codigo']));

		$nivel = Database::getRow("select g.id_nivel from estudiantes e,grados g where e.id_grado=g.id_grado and id_estudiante=(select id_estudiante from estudiantes where codigo =?)",array($key['codigo']));
		//obtiene fecha procesado 
		$procesado = Database::getRow("select fecha_procesado from impuntualidad_procesados where id_estudiante = (select id_estudiante from estudiantes where codigo =?)",array($key['codigo']));
		$cantidadProcesado = Database::getRow('select count(*) as cantidad,e.codigo, e.apellidos,e.nombres,g.nombre as grado,es.nombre as especialidad, gt.nombre as gt,ga.nombre as ga,sec.nombre as seccion from impuntualidad i,estudiantes e,grupos_academicos ga,grupos_tecnicos gt,secciones s, grados g, especialidades es , secciones sec where i.id_estudiante = e.id_estudiante  and i.tipo="Institución" and date(i.fecha_hora) between DATE(DATE_ADD(?, INTERVAL 1 DAY)) and ? and e.id_seccion=s.id_seccion and e.id_grupo_academico = ga.id_grupo_academico and e.id_grupo_tecnico = gt.id_grupo_tecnico and e.id_grado=g.id_grado and e.id_seccion = sec.id_seccion and e.id_especialidad=es.id_especialidad and i.estado="Injustificada" and e.codigo = ? group by e.codigo  order by especialidad desc ',array($procesado["fecha_procesado"],$periodo["fecha_final"],$key["codigo"]));	
		if(intval($nivel["id_nivel"]) == 1){
			if($estudiante["id_procesado"]== null || intval($cantidadProcesado["cantidad"]) > 0 ){	

				$salon = Database::getRow('select count(*) as cantidad from impuntualidad  where   tipo= "Salón" and date(fecha_hora) between ? and ? and  id_estudiante=(select id_estudiante from estudiantes where codigo =?) and estado="Injustificada"',array($periodo["fecha_inicial"],$periodo["fecha_final"],$key['codigo']));		
	
				$cantidad = (intval($salon["cantidad"])+intval($key['cantidad']));
				if($cantidad >=5){
					$contador++;
					$tabla2.= "<tr><td>".$contador."</td><td><a onclick=\"cargarEtapas('".$key['codigo']."');\" href='#'>".$key['codigo']."</a></td><td>".$key['apellidos'].", ".$key['nombres']."</td><td>".$key['grado']." ".$key['seccion']."</td><td>".$key['cantidad']."</td><td>".$salon['cantidad']."</td><td>".$cantidad."</td><td><a class='btn-floating green tooltipped' data-position='bottom' data-delay='50' data-tooltip='Procesar' onclick='procesar_alumno(\"".$key['codigo']."\",\"procesar\");'><i class='material-icons'>check</i></a></td></tr>";				
				}
	
			}
		}
	}	
}
echo $tabla2;
?>