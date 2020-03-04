<?php 
function relog($url){
	// al cargar cuda pagia se verifica que tenga una sesion iniciada, si la tiene verfica el permiso de la pagina que desea ver
	
	//enviar_felicitaciones(); 
	include_once('modulos.php');
	$modulos = get_modules();

	/* si encuentra un sesion iniciada retorna false,
	caso contrario true, si es true redireciona a login*/
	if(isset($_SESSION['id_personal'])){		

		/*codigos de repuesta
		 loginfail = "8900"
		 access_denied = "9097"
		 access_allow = "6756"
		 */

		 $allowed = '9097';

		// verifica la url de la peticion a la view con los modulos del permiso
		 switch ($url) {

		 	case 'inicio':
		 	case 'perfil':		 	
		 	$allowed = "6756";
		 	break;

		 	case 'asistencias':		 	
		 	$allowed = $modulos['Asistencia'] ? '6756' : '9097';
		 	break;	
		 	case 'asignaturas':
		 	$allowed = $modulos['Asignaturas'] ? '6756' : '9097';
		 	break;	
		 	case 'codigos':		
		 	$allowed = $modulos['Códigos'] ? '6756' : '9097';
		 	break;	
		 	case 'codigosexaula':		
		 	$allowed = $modulos['Códigos exaula'] ? '6756' : '9097';
		 	break;	
		 	case 'controldiciplinario':
		 	$allowed = $modulos['Control Disciplinario'] ? '6756' : '9097';
		 	break;
		 	case 'enfermeria':
		 	$allowed = $modulos['Enfermeria'] ? '6756' : '9097';
		 	break;
		 	case 'especialidades':
		 	$allowed = $modulos['Especialidades'] ? '6756' : '9097';
		 	break;
		 	case 'alumnos':
		 	$allowed = $modulos['Estudiantes'] ? '6756' : '9097';
		 	break;
		 	case 'grados':
		 	$allowed = $modulos['Grados'] ? '6756' : '9097';
		 	break;
		 	case 'grupos':
		 	$allowed = $modulos['Grupos'] ? '6756' : '9097';
		 	break;		 	
		 	case 'horarios':
		 	$allowed = $modulos['Horario'] ? '6756' : '9097';
		 	break;
		 	case 'justificaciones':
		 	$allowed = $modulos['Justificaciones'] ? '6756' : '9097';
		 	break;
		 	case 'locales':
		 	$allowed = $modulos['Locales'] ? '6756' : '9097';
		 	break;
		 	case 'matriculados':
		 	$allowed = $modulos['Matriculados'] ? '6756' : '9097';
		 	break;
		 	case 'niveles':
		 	$allowed = $modulos['Niveles'] ? '6756' : '9097';
		 	break;
		 	case 'permisos':
		 	$allowed = $modulos['Permisos'] ? '6756' : '9097';
		 	break;
		 	case 'personal':
		 	$allowed = $modulos['Personal'] ? '6756' : '9097';
		 	break;
		 	case 'suspendidos':
		 	$allowed = $modulos['Suspendidos'] ? '6756' : '9097';
		 	break;		 	
		 	case 'tiempos':
		 	$allowed = $modulos['Tiempos'] ? '6756' : '9097';
		 	break;
		 	case 'asuetos':
		 	$allowed = $modulos['Asuetos'] ? '6756' : '9097';
		 	break;
		 	case 'mensajes':
		 	$allowed = $modulos['Mensajes'] ? '6756' : '9097';
		 	break;
		 	case 'asistente-facebook':
		 	$allowed = $modulos['Bot Facebook'] ? '6756' : '9097';
		 	break;

		 }
		/*// comprueba si las sesiones son iguales
		 $data = Database::getRow("select id_sesion from detalle_sesion where id_persona=?", array($_SESSION['id_usuario']));

		// comprueba si las sesiones no son iguales
		 if($data['id_sesion']!=session_id()){
		 	session_destroy();
		 	$allowed = "8900";			
		 }
		// obtiene el tiempo actual
		 $now = time();

		 if ($now > $_SESSION['expire']) {
			// cierra su sesion 
		 	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
		 	Database::executeRow("update detalle_sesion set estado='off' where id_persona=?", array($_SESSION['id_usuario']));
		 	session_destroy();
		 	$allowed = "8900";            
		 }else {
        	// le sumamos otros 10 minutos
		 	$_SESSION['expire'] = $now + (10 * 60); 
		 }*/
		 return $allowed;
		}
		//en caso de no tener sesion
		else return "8900";	
}
function comprobar_permiso($pagina){
	$response = relog($pagina);
	echo $response;
}
	?>