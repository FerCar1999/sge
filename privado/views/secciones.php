
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
							<table class="striped">
								<thead>
									<tr>
										<th>Seccion</th>
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
									<a id="ver_secciones_activas" href="#!">Activas</a> |
									<a id="ver_secciones_inactivas" href="#!">Inactivas</a>
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="col s12 m12 l6">
					<div class="card" id="formAgregarSeccion">
						<div class="card-content">
							<span class="card-title">Secciones</span>
							<div class="row">
								<div class="input-field col s12">
									<i class="material-icons prefix">import_contacts</i>
          							<input id="nombre" type="text">
          							<label for="nombre">Seccion</label>
								</div>
								<div class="col s12 right-align">
									<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Agregar" id="agregar_seccion">
										<i class="material-icons">add</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formModSeccion">
						<div class="card-content">
							<span class="card-title">Modificar seccion</span>
							<div class="row">
								<div class="input-field col s12">
									<i class="material-icons prefix">import_contacts</i>
          							<input id="mod_nombre" type="text">
          							<label for="mod_nombre">Seccion</label>
								</div>
								<div class="col s12 right-align">
									<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Modificar" id="modificar_seccion">
										<i class="material-icons">edit</i>
										<a  id="cancelar_mod" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
										<i class="material-icons">cancel</i>
									</a>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formElimSeccion">
						<div class="card-content">
							<span class="card-title">Eliminar seccion</span>
							<div class="row">
								<h5 id="confirmacion"></h5>
								<div class="col s12 right-align">
									<a  id="eliminar_seccion" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Desactivar">
										<i class="material-icons">delete</i>
									</a>
									<a  id="cancelar_eliminar" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
										<i class="material-icons">cancel</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formActSeccion">
						<div class="card-content">
							<span class="card-title">Activar seccion</span>
							<br>
							<div class="row">
								<h5 id="confirmacion_activar"></h5>
								<div class="col s12 right-align">
									<a  id="activar_seccion" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Activar seccion">
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
		<?php require_once 'include/scripts.php' ?>
		<script type="text/javascript" src="/privado/js/secciones.js"></script>
	</body>
</html>