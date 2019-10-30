<?php 
	date_default_timezone_set('America/El_Salvador');        
	session_start();    
?>
<html>
	<?php require_once 'include/header.php' ?>
	<body>
		<?php require_once 'include/menu.php' ?>
		<div class="content">
			<div class="row">
				<div class="col s12">
					<div class="card">
						<div class="card-content">
							<br>
							<form id="formAlumnos" enctype="multipart/form-data" method="post">
								<div class="agregar_alumno">
									<span class="card-title">Estudiante</span>
									<div class="row">
										<div class="col s6 offset-s3 m4 offset-m4 offset-m3 l3 offset-l4" id="div-img-personal">
											<img class="circle" src="/media/img/user_default.jpg" id="img_alumno" alt="Imagen">
									    	<div class="file-field input-field input-photo col l6 m6 s12 tooltipped" data-position="left" data-delay="50" data-tooltip="Agregar foto">
      											<div class="btn amber darken-4">
      												<i class="material-icons">photo</i>
        											<input type="file" id="fotoAlumno" name="fotoAlumno">
      											</div>
    										</div>
										</div>
									</div>
									<div class="row">
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">account_circle</i>
		          							<input id="nombre" type="text" class="validate" autocomplete="off">
		          							<label for="nombre">Nombres</label>
										</div>
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">account_circle</i>
		          							<input id="apellido" type="text" class="validate" autocomplete="off">
		          							<label for="apellido">Apellidos</label>
										</div>
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">assignment_ind</i>
		          							<input id="codigo" type="text" class="validate" autocomplete="off">
		          							<label for="codigo">Código</label>
										</div>
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">email</i>
		          							<input id="correo" type="email" class="validate" autocomplete="off">
		          							<label for="correo">Correo responsable</label>
										</div>
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">vpn_key</i>
		          							<input id="clave" type="password" class="validate" autocomplete="off">
		          							<label for="clave">Clave</label>
										</div>

										<div class="input-field col l6 m6 s12" id="divSelect">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="selectGrado">
												<option value="0" disabled selected>Seleccione un grado</option>
												<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "select id_grado,nombre from grados where estado='Activo'";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_grado"].'">'.$row["nombre"].'</option>';
													}
												?>
											</select>
											<label>Grado</label>
										</div>

										<div class="input-field col l6 m6 s12" id="divSelectEsp">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="selectEspecialiad">
												<option value="0" disabled selected>Seleccione una especialidad</option>
												<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "select id_especialidad,nombre from especialidades where estado='Activo'";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_especialidad"].'">'.$row["nombre"].'</option>';
													}
												?>
											</select>
											<label>Especialidad</label>
										</div>

										<div class="input-field col l6 m6 s12" id="divSelect">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="selectSeccion">
												<option value="0" disabled selected>Seleccione una sección</option>
												<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "select id_seccion,nombre from secciones where estado='Activo'";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_seccion"].'">'.$row["nombre"].'</option>';
													}
												?>
											</select>
											<label>Sección</label>
										</div>

										<div class="input-field col l6 m6 s12" id="divSelectAcad">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="selectGrupoAcad">
												<option value="0" disabled selected>Seleccione un grupo académico</option>
												<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "select id_grupo_academico,nombre from grupos_academicos where estado='Activo'";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_grupo_academico"].'">'.$row["nombre"].'</option>';
													}
												?>
											</select>
											<label>Grupo académico</label>
										</div>

										<div class="input-field col l6 m6 s12" id="divSelectTec">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="selectGrupoTec">
												<option value="0" disabled selected>Seleccione un grupo técnico</option>
												<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "select id_grupo_tecnico,nombre from grupos_tecnicos where estado='Activo'";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_grupo_tecnico"].'">'.$row["nombre"].'</option>';
													}
												?>
											</select>
											<label>Grupo técnico</label>
										</div>
										
										<div class="input-field col l6 m6 s12" id="divSelectGuia">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="selectGuia">
												<option value="0" disabled selected>Seleccione un docente guía</option>
												<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "SELECT id_personal, CONCAT(apellido, ', ', nombre) AS nombre FROM `personal` WHERE id_permiso = 4 AND estado='Activo' ORDER BY nombre ASC";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_personal"].'">'.$row["nombre"].'</option>';
													}
												?>
											</select>
											<label>Docente guía</label>
										</div>

										<div class="col s12 right-align">
											<br>
	  										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Agregar" id="agregar_alumno">
												<i class="material-icons">add</i>
											</a>
										</div>
									</div>
								</div>
							</form>
							<form id="formAlumnoMod" enctype="multipart/form-data" method="post">
								<div class="modificar_alumno">
									<span class="card-title">Modificar estudiante</span>
									<div class="row">
										<div class="col s6 offset-s3 m4 offset-m4 offset-m3 l3 offset-l4" id="div-img-personal">
											<img class="circle" src="/media/img/user_default.jpg" id="img_alumno_mod" alt="Imagen">
											<div class="file-field input-field input-photo col l6 m6 s12 tooltipped" data-position="left" data-delay="50" data-tooltip="Agregar foto">
      											<div class="btn amber darken-4">
      												<i class="material-icons">photo</i>
        											<input type="file" id="fotoAlumnoMod" name="fotoAlumnoMod">
      											</div>
    										</div>
										</div>
									</div>
									<div class="row">
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">account_circle</i>
		          							<input id="mod_nombre" type="text" class="validate">
		          							<label for="mod_nombre">Nombres</label>
										</div>
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">account_circle</i>
		          							<input id="mod_apellido" type="text" class="validate">
		          							<label for="mod_apellido">Apellidos</label>
										</div>
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">assignment_ind</i>
		          							<input id="mod_codigo" type="text" class="validate">
		          							<label for="mod_codigo">Código</label>
										</div>
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">email</i>
		          							<input id="mod_correo" type="email" class="validate">
		          							<label for="mod_correo">Correo responsable</label>
										</div>
										<div class="input-field col l6 m6 s12" id="divSelect">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="selectGradoMod">
												<option value="0" disabled selected>Seleccione un grado</option>
												<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "select id_grado,nombre from grados where estado='Activo'";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_grado"].'">'.$row["nombre"].'</option>';
													}
												?>
											</select>
											<label for="select">Grado</label>
										</div>
										<div class="input-field col l6 m6 s12" id="divModSelectEsp">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="selectEspecialiadMod">
												<option value="0" disabled selected>Seleccione una especialidad</option>
												<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "select id_especialidad,nombre from especialidades where estado='Activo'";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_especialidad"].'">'.$row["nombre"].'</option>';
													}
												?>
											</select>
											<label for="select">Especialidad</label>
										</div>
										<div class="input-field col l6 m6 s12" id="divSelect">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="selectSeccionMod">
												<option value="0" disabled selected>Seleccione una sección</option>
												<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "select id_seccion,nombre from secciones where estado='Activo'";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_seccion"].'">'.$row["nombre"].'</option>';
													}
												?>
											</select>
											<label for="select">Sección</label>
										</div>
										
										<div class="input-field col l6 m6 s12" id="divModSelectAcad">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="selectGrupoAcadMod">
												<option value="0" disabled selected>Seleccione un grupo académico</option>
												<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "select id_grupo_academico,nombre from grupos_academicos where estado='Activo'";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_grupo_academico"].'">'.$row["nombre"].'</option>';
													}
												?>
											</select>
											<label for="select">Grupo académico</label>
										</div>

										<div class="input-field col l6 m6 s12" id="divModSelectTec">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="selectGrupoTecMod">
												<option value="0" disabled selected>Seleccione un grupo técnico</option>
												<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "select id_grupo_tecnico,nombre from grupos_tecnicos where estado='Activo'";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_grupo_tecnico"].'">'.$row["nombre"].'</option>';
													}
												?>
											</select>
											<label for="select">Grupo técnico</label>
										</div>

										<div class="input-field col l6 m6 s12" id="divModSelectGuia">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="selectGuiaMod">
												<option value="0" disabled selected>Seleccione un docente guía</option>
												<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "SELECT id_personal, CONCAT(apellido, ', ', nombre) AS nombre FROM `personal` WHERE id_permiso = 4 AND estado='Activo' ORDER BY nombre ASC";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_personal"].'">'.$row["nombre"].'</option>';
													}
												?>
											</select>
											<label>Docente guía</label>
										</div>

										<div class="col s12 right-align">
											<br>
											<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Modificar clave" href="#modalClave" id="modificar_clave">
												<i class="material-icons">vpn_key</i>
											</a>
	  										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Modificar" id="modificar_alumno">
												<i class="material-icons">edit</i>
											</a>
											<a  id="cancelar_mod" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
												<i class="material-icons">cancel</i>
											</a>
										</div>
									</div>
								</div>
							</form>
							</form>
								<div class="eliminar_alumno">
									<span class="card-title">Desactivar estudiante</span>
									<br>
									<div class="row">
										<h5 id="confirmacion"></h5>
										<div class="col s12 right-align">
											<a  id="eliminar_alumno" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Desactivar">
												<i class="material-icons">delete</i>
											</a>
											<a  id="cancelar_eliminar" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
												<i class="material-icons">cancel</i>
											</a>
										</div>
									</div>
								</div>
							</form>
							</form>
								<div class="activar_alumno">
									<span class="card-title">Activar estudiante</span>
									<br>
									<div class="row">
										<h5 id="confirmacion_activar"></h5>
										<div class="col s12 right-align">
											<a  id="activar_alumno" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Activar">
												<i class="material-icons">account_circle</i>
											</a>
											<a  id="cancelar_activar" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
												<i class="material-icons">cancel</i>
											</a>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<div class="card">
						<div class="card-content">
							<div class="input-field col  s12">									
								<input id="buscar_alumno" type="text" class="validate">
								<label for="icon_prefix">Buscar</label>
							</div>
							<table class="striped">
								<thead>
									<tr>
										<th>Código</th>
										<th>Nombres</th>
										<th>Apellidos</th>
										<th>Correo</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody class="table">        
								</tbody>
							</table>

							<!-- Paginacion de datos-->            
							<ul class="pagination"></ul>
							<div class="footer">
								<div class="stats">
									<a id="ver_alumnos_activos" href="#!">Activos</a> |
									<a id="ver_alumnos_inactivos" href="#!">Inactivos</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="fixed-action-btn" style="margin-right:65px;margin-bottom:20px;">
			<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped right" data-position="left" data-delay="50" data-tooltip="Imprimir nómina de alumnos" href="lista_general" target="_blank">
				<i class="material-icons">print</i>
			</a>
		</div>
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("alumnos"); ?>">
		<div id="modalClave" class="modal">
			<div class="modal-content">
				<h4 class="text-center">CAMBIAR CLAVE DEL ALUMNO</h4>
				<br>
				<br>
				<div class="input-field col s12">
					<i class="material-icons prefix">vpn_key</i>
					<input id="mod_clave" type="password" class="validate" autocomplete="off">
					<label for="mod_clave">Nueva clave</label>
				</div>
			</div>
			<div class="modal-footer">
				<a class="modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
				<a class="modal-action waves-effect waves-green btn-flat" id="modificarClave">Guardar</a>
			</div>
		</div>
		<?php require_once 'include/scripts.php' ?>
		<script type="text/javascript" src="/privado/js/alumnos.js"></script>
	</body>
</html>