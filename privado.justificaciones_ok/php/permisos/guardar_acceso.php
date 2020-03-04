<?php 
	try{
	// evalua los valores del POST
	$id_permiso = isset($_POST['id_permiso']) ? intval($_POST['id_permiso']) : 0;
	$lista_modulos = isset($_POST['permisos']) ? json_decode($_POST['permisos']) : null;
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($id_permiso)) {
		echo "Registro inválido.";
		exit();
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	if($id_permiso>0){
		$sql_limpiar = "delete from permiso_modulo where id_permiso =?";
		$params_limpiar = array($id_permiso);
		Database::executeRow($sql_limpiar, $params_limpiar);

		$sql = "insert into permiso_modulo (id_modulo,id_permiso) values (?,?)";
		// parametros
		foreach($lista_modulos as $id_modulo)
	    {
	    	if($id_modulo!=null){
	    	$params = array($id_modulo, $id_permiso);
			// ejecuta la consulta
			Database::executeRow($sql, $params);
			}
	    }
	}
   }catch (Exception $error){
   		echo $error;
   } 
 ?>