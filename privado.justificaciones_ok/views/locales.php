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
				<div class="col s12 m12 l6">
					<div class="card">
						<div class="card-content">
							<div class="input-field col  s12">									
								<input id="buscar_local" type="text" class="validate">
								<label for="icon_prefix">Buscar</label>
							</div>
							<table class="striped">
								<thead>
									<tr>
										<th>Nombre</th>
										<th>Capacidad</th>
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
									<a id="ver_locales_activos" href="#!">Activos</a> |
									<a id="ver_locales_inactivos" href="#!">Inactivos</a>
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="col s12 m12 l6">
					<div class="card" id="formAgregarLocal">
						<div class="card-content">
							<span class="card-title">Local</span>
							<div class="row">
								<div class="input-field col s12">
									<i class="material-icons prefix">build</i>
          							<input id="nombre" type="text">
          							<label for="nombre">Nombre</label>
								</div>
								<div class="input-field col s12">
									<i class="material-icons prefix">build</i>
          							<input id="capacidad" type="number" min="1">
          							<label for="capacidad">Capacidad</label>
								</div>
								<div class="input-field col s12">
									<i class="material-icons prefix">assignment_id</i>
									<select name="" id="selectTipoLocal">
										<?php 
											require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
											$sql = "select id_tipo_local,nombre from tipos_locales where estado='Activo'";
											$params = array("");

											$data = Database::getRows($sql, $params);
											foreach($data as $row)
											{																	
												echo '<option value="'.$row["id_tipo_local"].'">'.$row["nombre"].'</option>';
											}
										?>
									</select>
									<label>Tipo</label>
								</div>
								<div class="col s12 right-align">
									<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Agregar" id="agregar_local">
										<i class="material-icons">add</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formModLocal">
						<div class="card-content">
							<span class="card-title">Modificar local</span>
							<div class="row">
								<div class="input-field col s12">
									<i class="material-icons prefix">group</i>
          							<input id="mod_nombre" type="text">
          							<label for="mod_nombre">Nombre</label>
								</div>
								<div class="input-field col s12">
									<i class="material-icons prefix">group</i>
          							<input id="mod_capacidad" type="number" min="1">
          							<label for="mod_capacidad">Capacidad</label>
								</div>
								<div class="input-field col s12">
									<i class="material-icons prefix">assignment_id</i>
									<select name="" id="selectTipoLocalMod">
										<?php 
											require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
											$sql = "select id_tipo_local,nombre from tipos_locales where estado='Activo'";
											$params = array("");

											$data = Database::getRows($sql, $params);
											foreach($data as $row)
											{																	
												echo '<option value="'.$row["id_tipo_local"].'">'.$row["nombre"].'</option>';
											}
										?>
									</select>
								</div>
								<div class="col s12 right-align">
									<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Modificar" id="modificar_local">
										<i class="material-icons">edit</i>
										<a  id="cancelar_mod" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
										<i class="material-icons">cancel</i>
									</a>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formElimLocal">
						<div class="card-content">
							<span class="card-title">Desactivar local</span>
							<div class="row">
								<h5 id="confirmacion"></h5>
								<div class="col s12 right-align">
									<a  id="eliminar_local" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Desactivar">
										<i class="material-icons">delete</i>
									</a>
									<a  id="cancelar_eliminar" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
										<i class="material-icons">cancel</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formActLocal">
						<div class="card-content">
							<span class="card-title">Activar local</span>
							<br>
							<div class="row">
								<h5 id="confirmacion_activar"></h5>
								<div class="col s12 right-align">
									<a  id="activar_local" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Activar">
										<i class="material-icons">autorenew</i>
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
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("locales"); ?>">

		<?php require_once 'include/scripts.php' ?>
		<script type="text/javascript" src="/privado/js/locales.js"></script>
	</body>
</html>