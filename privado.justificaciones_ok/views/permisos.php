<?php 
date_default_timezone_set('America/El_Salvador');        
session_start();    
?>
<html>
<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php");
require_once 'include/header.php'
 ?>

<body>
	<?php require_once 'include/menu.php' ?>
	<div class="content">
		<div class="row">
			<div class="col s12 l6">
				<div class="card">
					<div class="card-content">
						<span class="card-title">Permisos</span>
						<br>
						<div class="input-field col  s12">									
							<input id="buscar_permiso" type="text" class="validate">
							<label for="icon_prefix">Buscar</label>
						</div>
						<!-- Tabla de actividades-->
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
								<a id="ver_permisos_activos" href="#!">Activos</a>|
								<a id="ver_permisos_inactivos" href="#!">Inactivos</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col s12 l6 ">
				
				<div class="card">
					<div class="card-content">
						<div class="agregar_permiso">
							<span class="card-title">Agregar</span>
							<br>

							<div class="row">
								<div class="input-field col  s12">
									<i class="material-icons prefix">account_circle</i>
									<input id="nombre" type="text" class="validate">
									<label for="icon_prefix">Nombre</label>
								</div>
								<div class="input-field col  s12">
									<button  id="agregar_permiso"  class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Agregar">
										<i class="material-icons">add</i>
									</button>
									
								</div>
							</div>
						</div>
						<div class="modificar_permiso">
							<span class="card-title">Modificar</span>
							<br>

							<div class="row">
								<div class="input-field col  s12">
									<i class="material-icons prefix">account_circle</i>
									<input id="modificar_nombre" type="text" class="validate">
									<label for="icon_prefix">Nombre</label>
								</div>
								<div class="input-field col  s12">

									<button id="modificar_permiso"  class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Modificar">
										<i class="material-icons">cached</i>
									</button>
									
								</div>
							</div>
						</div>
						<div class="eliminar_permiso">
							<span class="card-title">Desactivar permiso</span>
							<br>

							<div class="row">
								<h5 id="confirmacion" ></h5>
								<button  id="eliminar_permiso"  class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Desactivar">
									<i class="material-icons">delete</i>
								</button>
							</div>
						</div>
						<div class="activar_permiso">
							<span class="card-title">Activar permiso</span>
							<br>

							<div class="row">
								<h5 id="confirmacion2" ></h5>
								<button  id="activar_permiso"  class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Activar">
									<i class="material-icons">add</i>
								</button>
							</div>
						</div>
						<div class="permiso_modulos">

							<span  id="titulo_acceso" class="card-title  valign center-block">Activar</span>
							<br>

							<div class="row">
								<div class="acceso">
									<div class="">										
											<div class="row">

												<div id="modulos">
												</div>   
											</div>
										</div>
									<button id="guardar_acceso"  class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Modificar">
										<i class="material-icons">cached</i>
									</button>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

		</div>
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("permisos"); ?>">

		<?php require_once 'include/scripts.php' ?>
		<script type="text/javascript" src="/privado/js/permisos.js"></script>
	</body>
	</html>