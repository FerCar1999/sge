<?php 
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	class notificaciones
	{	

		function insertNotificacion($id_personal,$titulo,$descripcion){
		$sql ="INSERT INTO notificaciones(id_personal,titulo,descripcion,fecha_hora) VALUES(?,?,?,NOW())";
 			$params = array(strip_tags($id_personal),strip_tags($titulo),strip_tags($descripcion));
			Database::executeRow($sql, $params);
		}
		/*function procesados($id_guia)
		{
			$sql = "SELECT CONCAT(nombres,' ',apellidos) AS nombre FROM estudiantes WHERE procesado=? AND id_personal=? ORDER BY apellidos";
			$params = array(1,$id_guia);
			$data = Database::getRows($sql, $params);
			return $data;
		}*/

		function getNotificaciones($id_guia){
			$sql = "SELECT * FROM notificaciones WHERE estado=? AND id_personal=? ORDER BY fecha_hora";
			$params = array('Activo',$id_guia);
			$data = Database::getRows($sql, $params);
			return $data;
		}

		function getNotificacionesLeidas($id_guia){
			$sql = "SELECT * FROM notificaciones WHERE estado=? AND id_personal=? ORDER BY fecha_hora";
			$params = array('Inactivo',$id_guia);
			$data = Database::getRows($sql, $params);
			return $data;
		}

		function countNotificaciones($id_guia){
			$sql = "SELECT COUNT(id_notificacion) FROM notificaciones WHERE estado=? AND id_personal=?";
			$params = array('Activo',$id_guia);
			$data = Database::getRow($sql, $params);
			return $data;
		}

		function countNotificacionesLeidas($id_guia){
			$sql = "SELECT COUNT(id_notificacion) FROM notificaciones WHERE estado=? AND id_personal=?";
			$params = array('Inactivo',$id_guia);
			$data = Database::getRow($sql, $params);
			return $data;
		}

		function notificacionVista($id_notificacion){
			$resp = false;
			$sql ="UPDATE notificaciones set estado=? where id_notificacion=?";
 			$params = array('Inactivo',strip_tags($id_notificacion));
 
			if(Database::executeRow($sql, $params)){
		    	$resp = 'success';    
			}
			return $resp;
		}

	}
?>