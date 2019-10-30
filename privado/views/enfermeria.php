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
							<div class="row">
								<br>
								<br>
								<form action="/privado/php/reportes/conducta/conducta.php" method="post" id="reporteAlumnoForm" target="_blank">
								<div class="input-field col s12 search-wrapper">
									<i class="material-icons prefix">person</i>
					          		<input id="alumno" type="text" class="autocomplete">
					          		<input id="alumCod" name="codigo" class="hide">
					          		<label for="alumno">Carnet o nombre del estudiante</label>
				        		</div>
				        		</form>
							</div>
							<div class="observacionEnfermeria">
								<div class="col s8 m3 l3 center-align offset-s2">
									<br>
									<img src="" alt="alumno" id="fotoEnf" class="responsive-img circle">	
								</div>
								<div class="col s12 m9 l9">
									<br>
									<h5 class="text-center">Asigne la observación al estudiante</h5>
									<br>
									<b><p id="nombreEnf" class="center-align" ></p></b>
									<p id="carnetEnf" class="center-align" ></p>
									<br>
									<div class="input-field col s12">
										<i class="material-icons prefix">favorite</i>
							          	<textarea id="observacion_text" class="materialize-textarea" row="100"></textarea>
							          	<label for="observacion_text">Observación</label>
							        </div>
								</div>
							</div>
							<div class="row">
								<div class="col s12 right-align">
									<br>
									<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped left-align" data-position="left" data-delay="50" data-tooltip="Ver reporte" id="verReporte" target="_blank">
										<i class="material-icons">chrome_reader_mode</i>
									</a>
									<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Observación" id="selecAlumno">
										<i class="material-icons">check_circle</i>
									</a>
									<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Observación" id="asignarObservacion">
										<i class="material-icons">local_hospital</i>
									</a>
									<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Modificar observación" id="modObservacion">
										<i class="material-icons">local_hospital</i>
									</a>
									<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Cerrar" id="cancelarButton">
										<i class="material-icons">cancel</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-content">
							<span class="card-title"><p class="center-align">REGISTROS DE VISITAS A ENFERMERÍA</p></span>
							<div class="row">
								<div class="col s12">
									<br>
							     	<table>
								        	<thead>
								        		<tr>
								        			<td><b>Nombre</b></td>
								        			<td><b>Especialidad</b></td>
								        			<td><b>Fecha</b></td>
								        			<td><b>Acciones</b></td>
								        		</tr>
								        	</thead>
								        	<tbody class="lienfermeria">
								        		<tr>
								        			<td></td>
								        			<td></td>
								        		</tr>
								        	</tbody>
								        </table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("enfermeria"); ?>">
		<?php require_once 'include/scripts.php' ?>
		
		<script type="text/javascript" src="/privado/js/enfermeria.js"></script>
	</body>
</html>