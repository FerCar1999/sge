<?php 
	session_start();
	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/notificaciones/notificaciones.php");
	

	function GetAdmins(){
        $sql = "SELECT 
                    id_personal
                FROM personal
                WHERE id_permiso = ?";
        $params = array(1);
        $data = Database::getRows($sql, $params);
        return $data;
    }

    $admin = GetAdmins();

    foreach ($admin as $id) {
    	notificaciones::insertNotificacion($id[0], "SIN REGISTRO DE ASISTENCIA", "Saludos, recientemente se detectó que un docente no registró asistencia a los alumnos.");
    }
	
?>