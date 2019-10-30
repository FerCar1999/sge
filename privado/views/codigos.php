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
								<input id="buscar_codigo" type="text" class="validate">
								<label for="icon_prefix">Buscar</label>
							</div>
							<table class="striped">
								<thead>
									<tr>
										<th>Nombre</th>
										<th>Tipo</th>
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
									<a id="ver_codigos_activos" href="#!">Activos</a> |
									<a id="ver_codigos_inactivos" href="#!">Inactivos</a>
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="col s12 m12 l6">
					<div class="card" id="formAgregarCodigos">
						<div class="card-content">
							<span class="card-title">Código</span>
							<div class="row">
								<div class="input-field col s12">
									<i class="material-icons prefix">build</i>
          							<input id="nombre" type="text">
          							<label for="nombre">Nombre</label>
								</div>
								<div class="input-field col s12">
									<i class="material-icons prefix">assignment_id</i>
									<select name="" id="selectTipoCodigo">
										<?php 
											require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
											$sql = "select id_tipo_codigo,nombre from tipos_codigos where estado='Activo'";
											$params = array("");

											$data = Database::getRows($sql, $params);
											foreach($data as $row)
											{																	
												echo '<option value="'.$row["id_tipo_codigo"].'">'.$row["nombre"].'</option>';
											}
										?>
									</select>
									<label>Tipo</label>
								</div>
								<div class="input-field col s12">
									<i class="material-icons prefix">subject</i>
          							<textarea id="descripcion" class="materialize-textarea"></textarea>
          							<label for="descripcion">Descripción</label>
								</div>
								<div class="col s12 right-align">
									<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Agregar" id="agregar_codigo">
										<i class="material-icons">add</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formModCodigos">
						<div class="card-content">
							<span class="card-title">Modificar código</span>
							<div class="row">
								<div class="input-field col s12">
									<i class="material-icons prefix">group</i>
          							<input id="mod_nombre" type="text">
          							<label for="mod_nombre">Nombre</label>
								</div>
								<div class="input-field col s12">
									<i class="material-icons prefix">assignment_id</i>
									<select name="" id="selectTipoCodigoMod">
										<?php 
											require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
											$sql = "select id_tipo_codigo,nombre from tipos_codigos where estado='Activo'";
											$params = array("");

											$data = Database::getRows($sql, $params);
											foreach($data as $row)
											{																	
												echo '<option value="'.$row["id_tipo_codigo"].'">'.$row["nombre"].'</option>';
											}
										?>
									</select>
								</div>
								<div class="input-field col s12">
									<i class="material-icons prefix">subject</i>
          							<textarea id="mod_descripcion" class="materialize-textarea"></textarea>
          							<label for="descripcion">Descripción</label>
								</div>
								<div class="col s12 right-align">
									<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Modificar" id="modificar_codigo">
										<i class="material-icons">edit</i>
										<a  id="cancelar_mod" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
										<i class="material-icons">cancel</i>
									</a>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formElimCodigos">
						<div class="card-content">
							<span class="card-title">Desactivar código</span>
							<div class="row">
								<h5 id="confirmacion"></h5>
								<div class="col s12 right-align">
									<a  id="eliminar_codigo" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Desactivar">
										<i class="material-icons">delete</i>
									</a>
									<a  id="cancelar_eliminar" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
										<i class="material-icons">cancel</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formActCodigos">
						<div class="card-content">
							<span class="card-title">Activar código</span>
							<br>
							<div class="row">
								<h5 id="confirmacion_activar"></h5>
								<div class="col s12 right-align">
									<a  id="activar_codigo" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Activar">
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
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("codigos"); ?>">
		<?php require_once 'include/scripts.php' ?>
		<script type="text/javascript" src="/privado/js/codigos.js"></script>
	</body>
</html>