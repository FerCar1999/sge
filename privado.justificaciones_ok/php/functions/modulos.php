<?php 
// obtiene los modulos con acceso del usuario
function get_modules(){
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	// consulta obtener los modulos con acceso
	$sql="select m.nombre from modulos m,permiso_modulo mp where mp.id_modulo=m.id_modulo and mp.id_permiso=(select id_permiso from personal where id_personal=?)";

	// toma la pk del usuario y obtiene los datos
	$params = array($_SESSION["id_personal"]);
	$data = Database::getRows($sql, $params);
	// inicia todos los modulos en false
	$_modules = array();
	// recorre los modulos a los que tiene acceso
	foreach($data as $row)
	{
		// cambia a true el elemento de array que le corresponde al modulo
		$_modules[$row["nombre"]] = true;	
	}
	return $_modules;
}

?>
