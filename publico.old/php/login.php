<?php 
$user = isset($_POST["user"]) ? trim($_POST["user"]) : ("");
$pass = isset($_POST["pass"]) ? trim($_POST["pass"]) : ("");

if ($user === "" || $pass === "") {
	echo 'Ingrese usuario y contraseña'; 
}

require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
session_start();


$data = Database::getRow("SELECT id_estudiante,nombres,apellidos,e.foto AS foto,e.clave AS clave,e.codigo AS codigo,g.nombre AS grado,CONCAT(p.nombre, ' ', p.apellido) AS docente,p.correo AS correo,IF(ep.nombre != 'Ninguna',CONCAT(ep.nombre,' ', gt.nombre),'Tercer ciclo') AS especialidad,IF(ep.nombre !='Ninguna',CONCAT(s.nombre,ga.nombre),s.nombre) AS seccion FROM estudiantes e, grados g, especialidades ep, secciones s, grupos_academicos ga, grupos_tecnicos gt, personal p WHERE e.codigo = ? AND g.id_grado = e.id_grado AND e.id_especialidad = ep.id_especialidad AND s.id_seccion = e.id_seccion AND e.id_grupo_academico = ga.id_grupo_academico AND gt.id_grupo_tecnico = e.id_grupo_tecnico AND e.id_personal = p.id_personal", array($user));

if(isset($data['id_estudiante'])){
	
	if (password_verify($pass, $data['clave'])) {
		
		Database::executeRow('INSERT INTO ingreso_estudiante VALUES(?,(select now()))',array($data['id_estudiante']));

		$_SESSION["id_estudiante"] = $data['id_estudiante'];
		$_SESSION["nombres"] = $data['nombres'];
		$_SESSION["apellidos"] = $data['apellidos'];
		$_SESSION["codigo"] = $data['codigo'];
		$_SESSION["grado"] = $data['grado'];
		$_SESSION["especialidad"] = $data['especialidad'];
		$_SESSION["seccion"] = $data['seccion'];
		$_SESSION["docente"] = $data['docente'];
		$_SESSION["correo"] = $data['correo'];
		/*if (file_exists('http://sge.ricaldone.edu.sv:8080/media/img/alumnos/'.$data['codigo'].'.JPG')) {
            $_SESSION["foto"] = 'http://sge.ricaldone.edu.sv:8080/media/img/alumnos/'.$data['codigo'].'.JPG';
        }else{
            $_SESSION["foto"] = 'http://sge.ricaldone.edu.sv:8080/media/img/user_default.jpg';
        }*/
		Token::generate();
		echo 'true';
		
	}else {
		echo 'Clave incorrecta.';
	}
}else echo 'El usuario no existe.';
?>
