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
							<div class="formAsignatura">
									<span class="card-title">Asignatura</span>
									<div class="row">
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">book</i>
		          							<input id="nombre" type="text" class="validate" autocomplete="off">
		          							<label for="nombre">Nombre</label>
										</div>
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">lock</i>
		          							<input id="codigoAsignatura" type="text" class="validate" autocomplete="off">
		          							<label for="codigoAsignatura">Código</label>
										</div>
										<div class="input-field col l6 m6 s12" id="divSelect">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="selectTipoAsignatura">
												<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "select id_tipo_asignatura,nombre from tipos_asignaturas where estado='Activo'";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_tipo_asignatura"].'">'.$row["nombre"].'</option>';
													}
												?>
											</select>
											<label>Tipo de asignatura</label>
										</div>
										<div class="input-field col l6 m6 s12" id="divSelect">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="selectGrado">
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
										<div class="col s12 right-align">
											<br>
	  										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Agregar" id="agregar_asignatura">
												<i class="material-icons">add</i>
											</a>
										</div>
									</div>
								</div>
							<div class="formAsignaturaMod">
									<span class="card-title">Modificar asignatura</span>
									<div class="row">
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">book</i>
		          							<input id="mod_nombre" type="text" class="validate" autocomplete="off">
		          							<label for="mod_nombre">Nombre</label>
										</div>
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">lock</i>
		          							<input id="mod_codigoAsignatura" type="text" class="validate" autocomplete="off">
		          							<label for="mod_codigoAsignatura">Código</label>
										</div>
										<div class="input-field col l6 m6 s12" id="divSelect">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="mod_selectTipoAsignatura">
												<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "select id_tipo_asignatura,nombre from tipos_asignaturas where estado='Activo'";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_tipo_asignatura"].'">'.$row["nombre"].'</option>';
													}
												?>
											</select>
											<label id="labelTipo">Tipo Asignatura</label>
										</div>
										<div class="input-field col l6 m6 s12" id="divSelect">
											<i class="material-icons prefix">assignment_id</i>
											<select name="" id="mod_selectGrado">
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
											<label id="labelGrado">Grado</label>
										</div>
										<div class="col s12 right-align">
											<br>
	  										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Modificar" id="modificar_asignatura">
												<i class="material-icons">edit</i>
											</a>
											<a  id="cancelar_mod" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
												<i class="material-icons">cancel</i>
											</a>
										</div>
									</div>
								</div>
							<div class="formAsignaturaElim">
									<span class="card-title">Desactivar asignatura</span>
									<br>
									<div class="row">
										<h5 id="confirmacion"></h5>
										<div class="col s12 right-align">
											<a  id="eliminar_asginatura" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Desactivar">
												<i class="material-icons">delete</i>
											</a>
											<a  id="cancelar_eliminar" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
												<i class="material-icons">cancel</i>
											</a>
										</div>
									</div>
								</div>
							<div class="formAsignaturaAct">
									<span class="card-title">Activar asignatura</span>
									<br>
									<div class="row">
										<h5 id="confirmacion_activar"></h5>
										<div class="col s12 right-align">
											<a  id="activar_asignatura" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Activar">
												<i class="material-icons">cached</i>
											</a>
											<a  id="cancelar_activar" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
												<i class="material-icons">cancel</i>
											</a>
										</div>
									</div>
								</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<div class="card">
						<div class="card-content">
							<div class="input-field col  s12">									
								<input id="buscar_asignatura" type="text" class="validate">
								<label for="icon_prefix">Buscar</label>
							</div>
							<table class="striped">
								<thead>
									<tr>
										<th>Nombre</th>
										<th>Grado</th>
										<th>Código</th>
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
									<a id="ver_asignaturas_activas" href="#!">Activos</a> |
									<a id="ver_asignaturas_inactivas" href="#!">Inactivos</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("asignaturas"); ?>">
		<?php require_once 'include/scripts.php' ?>
		<script type="text/javascript" src="/privado/js/asignaturas.js"></script>
	</body>
</html>