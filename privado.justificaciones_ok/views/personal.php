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
						<form id="formPersonal" enctype="multipart/form-data" method="post">
							<div class="agregar_personal">
								<span class="card-title">Personal</span>
								<div class="row">
									<div class="col s6 offset-s3 m4 offset-m4 offset-m3 l3 offset-l4" id="div-img-personal">
										<img class="circle" src="/media/img/user_default.jpg" id="img_personal" alt="Imagen">
										<div class="file-field input-field input-photo col l6 m6 s12 tooltipped" data-position="left" data-delay="50" data-tooltip="Agregar foto">
											<div class="btn amber darken-4">
												<i class="material-icons">photo</i>
												<input type="file" id="fotoPersonal" name="fotoPersonal">
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
										<label for="correo">Correo</label>
									</div>
									<div class="input-field col l6 m6 s12">
										<i class="material-icons prefix">vpn_key</i>
										<input id="clave" type="password" class="validate" autocomplete="off">
										<label for="clave">Clave</label>
									</div>
									<div class="input-field col l6 m6 s12">
										<i class="material-icons prefix">vpn_key</i>
										<input id="claveR" type="password" class="validate" autocomplete="off">
										<label for="claveR">Confirmar clave</label>
									</div>
									<div class="input-field col l6 m6 s12" id="divSelect">
										<i class="material-icons prefix">assignment_id</i>
										<select name="" id="selectPermiso">
											<?php 
											require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
											$sql = "select id_permiso,nombre from permisos where estado='Activo'";
											$params = array("");

											$data = Database::getRows($sql, $params);
											foreach($data as $row)
											{																	
												echo '<option value="'.$row["id_permiso"].'">'.$row["nombre"].'</option>';
											}
											?>
										</select>
										<label>Permiso</label>
									</div>
									<div class="col s12 right-align">
										<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Agregar" id="agregar_personal">
											<i class="material-icons">add</i>
										</a>
									</div>
								</div>
							</div>
						</form>
						<form id="formPersonalMod" enctype="multipart/form-data" method="post">
							<div class="modificar_personal">
								<span class="card-title">Modificar personal</span>
								<div class="row">
									<div class="col s6 offset-s3 m4 offset-m4 offset-m3 l3 offset-l4" id="div-img-personal">
										<img class="circle" src="/media/img/user_default.jpg" id="img_personal_mod" alt="Imagen">
										<div class="file-field input-field input-photo col l6 m6 s12 tooltipped" data-position="left" data-delay="50" data-tooltip="Agregar foto">
											<div class="btn amber darken-4">
												<i class="material-icons">photo</i>
												<input type="file" id="fotoPersonalMod" name="fotoPersonalMod">
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
										<label for="mod_correo">Correo</label>
									</div>
									<div class="input-field col l6 m6 s12" id="divSelect">
										<i class="material-icons prefix">assignment_id</i>
										<select name="" id="selectPermisoMod">
											<?php 
											require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
											$sql = "select id_permiso,nombre from permisos where estado='Activo'";
											$params = array("");

											$data = Database::getRows($sql, $params);
											foreach($data as $row)
											{																	
												echo '<option value="'.$row["id_permiso"].'">'.$row["nombre"].'</option>';
											}
											?>
										</select>
									</div>
									<div class="col s12 right-align">
										<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Modificar" id="modificar_personal">
											<i class="material-icons">edit</i>
										</a>
										<a id="cancelar_mod" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
											<i class="material-icons">cancel</i>
										</a>
									</div>
								</div>
							</div>
						</form>
					</form>
					<div class="eliminar_personal">
						<span class="card-title">Desactivar personal</span>
						<br>
						<div class="row">
							<h5 id="confirmacion"></h5>
							<div class="col s12 right-align">
								<a  id="eliminar_personal" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Desactivar">
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
			<div class="activar_personal">
				<span class="card-title">Activar personal</span>
				<br>
				<div class="row">
					<h5 id="confirmacion_activar"></h5>
					<div class="col s12 right-align">
						<a  id="activar_personal" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Activar">
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
					<input id="buscar_personal" type="text" class="validate">
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
					<tbody class="table" id="tableAcad">        
					</tbody>
				</table>

				<!-- Paginacion de datos-->            

				<ul class="pagination"></ul>
				<div class="footer">
					<div class="stats">
						<a id="ver_personal_activo" href="#!">Activos</a> |
						<a id="ver_personal_inactivo" href="#!">Inactivos</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<div id="modal1" class="modal">
	<div class="modal-content">
		
		<div class="row" id="observacionDiv">
			
			<div class="col s12 ">
				<br>
				<h4 class="text-center">Asistencias diferidas</h4>
				<h6 id="profesor" class="text-center">Asistencias diferidas</h6>
				<div class="input-field col s12">
					<br>
					<i class="material-icons prefix">mode_edit</i>
					<select multiple id="select_materias">
						<option value="" disabled selected>Selecionar Clases</option>						
					</select>
					<br>
					<label>Clases de hoy</label>
				</div>
				<div class="input-field col s12 hidden-field">					
					<i class="material-icons prefix">mode_edit</i>
					<input id="hora" type="number" min='1'>
					<label id="horalbl" for="hora">Horas habilitadas</label>
				</div>	
				<div class="input-field col s12 hidden-field">
					<i class="material-icons prefix">mode_edit</i>
					<div class="switch ">
						<i class="material-icons prefix">mode_edit</i>
						<label >Siempre habilitado
							<input id="check_siempre" type="checkbox">
							<span class="lever"></span>										
						</label>
						<label for="check_siempre"></label>
					</div>
				</div>

			</div>
		</div>
	</div>
	<div class="modal-footer">
	

		<a href="#!" onclick="guardar_asistencia_diferida();" class=" modal-action modal-close waves-effect waves-green btn-flat">Guardar</a>
	</div>
</div>
<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("personal"); ?>">

<?php require_once 'include/scripts.php' ?>
<script type="text/javascript" src="/privado/js/personal.js"></script>
</body>
</html>