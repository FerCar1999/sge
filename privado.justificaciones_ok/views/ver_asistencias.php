<?php 
date_default_timezone_set('America/El_Salvador');        
session_start();    
?>
<html>
<?php
date_default_timezone_set('UTC');
require_once 'include/header.php'; ?>
<body>
	<?php require_once 'include/menu.php' ?>
	<div class="content">
		<div class="row">
			<div class="col s12">
				<div class="card">
					<div class="card-content">
						<br>
						
						<div class="row">							
							<h5 id="id_profesor" class="text-center">VER ASISTENCIAS</h5>
							<div class="input-field col s12">
									<i class="material-icons prefix">mode_edit</i>
									<input value="<?php echo date('Y-m-d')?>" id="date_inicio" type="date" class="datepicker" name="inicio">
									<label for="date_inicio">Fecha</label>
							
							</div>
							<div class="input-field col s12">
								<br>

								<i class="material-icons prefix">mode_edit</i>
								<select multiple id="select_materias">
									<option value="" disabled selected>Selecionar Clases</option>						
								</select>
								<br>
								<label>Clases de hoy</label>
							</div>
							<div class="col s12 right-align">
								<br>
								<a onclick="cargar_asistencia();" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Ver Asistencia">
									<i class="material-icons">add</i>
								</a>
								<a onclick="mostrar();" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Buscar">
									<i class="material-icons">search</i>
								</a>
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
		<a href="#!" onclick="ver_asistencia_maestro();" class=" modal-action modal-close waves-effect waves-green btn-flat">Ver Asistencia</a>

		<a href="#!" onclick="guardar_asistencia_diferida();" class=" modal-action modal-close waves-effect waves-green btn-flat">Guardar</a>
	</div>
</div>
<?php require_once 'include/scripts.php' ?>
<script type="text/javascript" src="/privado/js/ver_asistencias.js"></script>
</body>
</html>