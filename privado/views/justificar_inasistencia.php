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
							<span class="card-title"><p class="center-align">JUSTIFICAR INASISTENCIA</p></span>
							<div class="row">
								<form action="">
									<div class="input-field col s12 search-wrapper">
										<i class="material-icons prefix">person</i>
						          		<input id="alumno" type="text" class="autocomplete">
						          		<label for="alumno">Carnet o nombre del estudiante</label>
					        		</div>
					        		<div class="input-field col s6">
										<i class="material-icons prefix">timer</i>
										<input id="date_inicio" type="date" value="<?php echo date('Y-m-d')?>" class="datepicker">
										<label for="date_inicio">Inicio</label>
									</div>
									<div class="input-field col s6">
										<i class="material-icons prefix">timer</i>
										<input id="date_fin" type="date" value="<?php echo date('Y-m-d')?>"class="datepicker">
										<label for="date_fin">Fin</label>
									</div>
								</form>
								<div class="center-align"><div class="col s12" id="noHayCodigos"></div></div>							
								<div>
									<div class="col s12">
								     	<table>
									        	<thead>
									        		<tr>
									        			<td><b>Carnet</b></td>
									        			<td><b>Nombre</b></td>
									        			<td><b>Especialidad</b></td>
									        			<td><b>Grado</b></td>
									        			<td><b>Fecha</b></td>
									        			<td><b>Hora</b></td>
									        			<td><b>Clase</b></td>
									        			<td><b>Acciones</b></td>
									        		</tr>
									        	</thead>
									        	<tbody class="table">
									        		<tr>
									        			<td></td>
									        			<td></td>
									        			<td></td>
									        			<td></td>
									        			<td></td>
									        			<td></td>
									        		</tr>
									        	</tbody>
									        </table>
									</div>
									<div class="col s12 right-align">
										<br>
										<a onclick="mostrar();" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Buscar">
											<i class="material-icons">search</i>
										</a>
									</div>
									<div class="col s12 right-align">
										<br>
										<a onclick="justificarTodas();" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Justificar TODAS">
											<i class="material-icons">done</i>
										</a>
									</div>
									<div class="col s12 right-align">
										<br>
										<a onclick="justificarTodasITR();" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Justificar TODAS ITR">
											<i class="material-icons">done_alll</i>
										</a>
									</div>
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
				<h5 class="text-center">ASIGNE LA OBSERVACIÓN</h5>	
				<div class="input-field col s12">
					<i class="material-icons prefix">mode_edit</i>
					<textarea id="observacion_text" class="materialize-textarea" row="100"></textarea>
					<label for="observacion_text">Observación</label>
				</div>

			</div>
		</div>
</div>
<div class="modal-footer">
<a href="#!" onclick="justificar_inasistencia();" class=" modal-action modal-close waves-effect waves-green btn-flat">Sin Observacion</a>
	<a href="#!" onclick="justificar_inasistencia();" class=" modal-action modal-close waves-effect waves-green btn-flat">OK</a>
</div>
</div>		
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("justificaciones"); ?>">

		<?php require_once 'include/scripts.php' ?>
		<script src="/privado/js/justificar_inasistencias.js"></script>
	</body>
</html>