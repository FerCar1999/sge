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
								<input id="buscar_tiempo" type="text" class="validate">
								<label for="icon_prefix">Buscar</label>
							</div>
							<table class="striped">
								<thead>
									<tr>
										<th id="nombreTipo">Hora inicial</th>
										<th id="nombreTipo">Hora final</th>
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
									<a id="ver_tiempos_activos" href="#!">Activos</a> |
									<a id="ver_tiempos_inactivos" href="#!">Inactivos</a>
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="col s12 m12 l6">
					<div class="card" id="formAgregarTiempo">
						<div class="card-content">
							<span class="card-title">Tiempo</span>
							<div class="row">
								<div class="input-field col s12">
			                    	<i class="material-icons prefix">access_time</i>
			                        <label for="horaInicio">Hora inicial</label>
			                        <input id="horaInicio" class="timepicker" type="time">
			                    </div>
								<div class="input-field col s12">
			                    	<i class="material-icons prefix">access_time</i>
			                        <label for="horaFin">Hora final</label>
			                        <input id="horaFin" class="timepicker" type="time">
			                    </div>
								<div class="col s12 right-align">
									<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Agregar" id="agregar_tiempo">
										<i class="material-icons">add</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formModTiempo">
						<div class="card-content">
							<span class="card-title">Modificar tiempo</span>
							<div class="row">
								<div class="input-field col s12">
			                    	<i class="material-icons prefix">access_time</i>
			                        <label for="mod_horaInicio">Hora inicial</label>
			                        <input id="mod_horaInicio" class="timepicker" type="time">
			                    </div>
								<div class="input-field col s12">
			                    	<i class="material-icons prefix">access_time</i>
			                        <label for="mod_horaFin">Hora final</label>
			                        <input id="mod_horaFin" class="timepicker" type="time">
			                    </div>
								<div class="col s12 right-align">
									<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Modificar" id="modificar_tiempo">
										<i class="material-icons">edit</i>
										<a  id="cancelar_mod" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
										<i class="material-icons">cancel</i>
									</a>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formElimTiempo">
						<div class="card-content">
							<span class="card-title">Desactivar tiempo</span>
							<div class="row">
								<h5 id="confirmacion"></h5>
								<div class="col s12 right-align">
									<a  id="eliminar_tiempo" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Desactivar">
										<i class="material-icons">delete</i>
									</a>
									<a  id="cancelar_eliminar" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
										<i class="material-icons">cancel</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formActTiempo">
						<div class="card-content">
							<span class="card-title">Activar tiempo</span>
							<br>
							<div class="row">
								<h5 id="confirmacion_activar"></h5>
								<div class="col s12 right-align">
									<a  id="activar_tiempo" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Activar">
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
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("permisos"); ?>">

		<?php require_once 'include/scripts.php' ?>
		<script type="text/javascript" src="/privado/js/tiempo.js"></script>
	</body>
</html>