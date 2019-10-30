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
								<input id="buscar_nivel" type="text" class="validate">
								<label for="icon_prefix">Buscar nivel</label>
							</div>
							<table class="striped">
								<thead>
									<tr>
										<th>Nombre</th>										
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
									<a id="ver_especialidades_activas" href="#!">Activos</a> |
									<a id="ver_especialidades_inactivas" href="#!">Inactivos</a>
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="col s12 m12 l6">
					<div class="card" id="formAgregarNivel">
						<div class="card-content">
							<span class="card-title">Niveles</span>
							<div class="row">
								<div class="input-field col s12">
									<i class="material-icons prefix">build</i>
          							<input id="nombre" type="text">
          							<label for="nombre">Nombre</label>
								</div>
								<div class="input-field col s12">
									<i class="material-icons prefix">format_list_numbered</i>
          							<input id="cantNivel" type="text">
          							<label for="cantNivel">Cantidad de secciones</label>
								</div>
								<div class="input-field col s12">
									<i class="material-icons prefix">subject</i>
          							<textarea id="descripcion" class="materialize-textarea"></textarea>
          							<label for="descripcion">Descripcion</label>
								</div>
								<div class="col s12 right-align">
									<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Agregar" id="agregar_nivel">
										<i class="material-icons">add</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formModNivel">
						<div class="card-content">
							<span class="card-title">Modificar nivel</span>
							<div class="row">
								<div class="input-field col s12">
									<i class="material-icons prefix">group</i>
          							<input id="mod_nombre" type="text">
          							<label for="mod_nombre">Nombre</label>
								</div>
								<div class="input-field col s12">
									<i class="material-icons prefix">format_list_numbered</i>
          							<input id="mod_cantNivel" type="text">
          							<label for="mod_cantNivel">Cantidad de secciones</label>
								</div>
								<div class="input-field col s12">
									<i class="material-icons prefix">subject</i>
          							<textarea id="mod_descripcion" class="materialize-textarea"></textarea>
          							<label for="descripcion">Descripcion</label>
								</div>
								<div class="col s12 right-align">
									<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Modificar" id="modificar_nivel">
										<i class="material-icons">edit</i>
										<a  id="cancelar_mod" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
										<i class="material-icons">cancel</i>
									</a>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formElimNivel">
						<div class="card-content">
							<span class="card-title">Eliminar nivel</span>
							<div class="row">
								<h5 id="confirmacion"></h5>
								<div class="col s12 right-align">
									<a  id="eliminar_nivel" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Desactivar">
										<i class="material-icons">delete</i>
									</a>
									<a  id="cancelar_eliminar" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
										<i class="material-icons">cancel</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formActNivel">
						<div class="card-content">
							<span class="card-title">Activar nivel</span>
							<br>
							<div class="row">
								<h5 id="confirmacion_activar"></h5>
								<div class="col s12 right-align">
									<a  id="activar_nivel" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Activar nivel">
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
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("niveles"); ?>">

		<?php require_once 'include/scripts.php' ?>
		<script type="text/javascript" src="/privado/js/niveles.js"></script>
	</body>
</html>