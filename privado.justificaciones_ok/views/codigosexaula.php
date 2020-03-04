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
								<form action="/privado/php/reportes/conducta/conducta.php" class="hide" method="post" target="_blank" id="reporteAlumnoForm">
									<input id="alumnoCod" type="text" name="codigo">
									<input id="etapaCod" type="text" name="etapa">
								</form>
								<form action="">
									<div class="input-field col s12 search-wrapper">
										<i class="material-icons prefix">person</i>
						          		<input id="alumno" type="text" class="autocomplete">
						          		<label for="alumno">Carnet o nombre del estudiante</label>
					        		</div>
					        			<?php 
					        				$colspan = 0;
					        				if ($_SESSION["permiso"] == "Administrador") {
					        				 	echo '
					        				 	<div class="col m4 s12">
					        						<br>
					        				 		<a class="btn amber darken-4 right col s12" id="verCodigosAlumno">
					        							Historial
      													<i class="right material-icons">list</i>
    												</a>
    											</div>
    											<div class="col m4 s12">
								        			<br>
								        			<a class="btn amber darken-4 left col s12" id="loadCodigos">
								        				Código
			      										<i class="right material-icons">gavel</i>
			    									</a>
								        		</div>
								        		<div class="col m4 s12">
								        			<br>
								        			<a class="btn amber darken-4 right col s12" id="reporteAlumno" target="_blank">
					        							Reporte
      													<i class="right material-icons">chrome_reader_mode</i>
    												</a>
    											</div>';
    											$colspan++;
				        				 	}
				        				 	if ($_SESSION["permiso"] == "Docente guía") {
				        				 		echo '
					        				 	<div class="col m4 s12">
					        						<br>
					        				 		<a class="btn amber darken-4 right col s12" href="/privado/php/reportes/conducta/grupal.php" target="_blank">
					        							Reporte grupal
      													<i class="right material-icons">chrome_reader_mode</i>
    												</a>
    											</div>
    											<div class="col m4 s12">
								        			<br>
								        			<a class="btn amber darken-4 left col s12" id="loadCodigos">
								        				Código
			      										<i class="right material-icons">gavel</i>
			    									</a>
								        		</div>
								        		<div class="col m4 s12">
								        			<br>
								        			<a class="btn amber darken-4 right col s12" id="reporteAlumno" target="_blank">
					        							Reporte
      													<i class="right material-icons">chrome_reader_mode</i>
    												</a>
    											</div>';
				        				 	}
				        				 	if ($_SESSION["permiso"] == "Docente Colaborador") {
				        				 		echo '
    											<div class="col m6 s12">
								        			<br>
								        			<a class="btn amber darken-4 left col s12" id="loadCodigos">
								        				Código
			      										<i class="right material-icons">gavel</i>
			    									</a>
								        		</div>
								        		<div class="col m6 s12">
								        			<br>
								        			<a class="btn amber darken-4 right col s12" id="reporteAlumno" target="_blank">
					        							Reporte
      													<i class="right material-icons">chrome_reader_mode</i>
    												</a>
    											</div>';
				        				 	}
				        				 	if ($_SESSION["permiso"] == "Coordinador") {
				        				 		echo '
    											<div class="col m6 s12">
								        			<br>
								        			<a class="btn amber darken-4 left col s12" id="loadCodigos">
								        				Código
			      										<i class="right material-icons">gavel</i>
			    									</a>
								        		</div>
								        		<div class="col m6 s12">
								        			<br>
								        			<a class="btn amber darken-4 right col s12" id="reporteAlumno" target="_blank">
					        							Reporte
      													<i class="right material-icons">chrome_reader_mode</i>
    												</a>
    											</div>';
				        				 	}
				        				 	/*else{
				        				 		echo '
					        				 		;
				        				 	}*/
				        				 	if ($_SESSION["permiso"] == "Docente") {
				        				 		echo '
								        		<div class="col m4 s12 offset-m8">
								        			<br>
								        			<a class="btn amber darken-4 right col s12" id="reporteAlumno" target="_blank">
					        							Reporte
      													<i class="right material-icons">chrome_reader_mode</i>
    												</a>
    											</div>';
				        				 	}
					        			?>
								</form>
								<div class="center-align"><div class="col s12" id="noHayCodigos"></div></div>
								<br>
								<br>
								<div id="infoCodigos">
									<div class="col s8 m4 l3 center-align offset-s2">
										<br>
										<br>
										<img src="" alt="alumno" id="fotoExaula" class="responsive-img circle">	
										<h5 id="nombreExaula"></h5>
										<p id="carnetExaula"></p>
									</div>
									<div class="col s12 m8 l8 offset-l1">
										<br>
										<br>
										<ul class="collection with-header"></ul>
								     	<table>
									        	<thead>
									        		<tr>
									        			<td><b>Tipo</b></td>
									        			<td><b>Código</b></td>
									        			<td><b>Fecha</b></td>
									        			<td><b>Docente</b></td>
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
									        		</tr>
									        	</tbody>
									        </table>
									</div>
									<div class="col s12 right-align">
										<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped cancelarButton" data-position="left" data-delay="50" data-tooltip="Cerrar">
											<i class="material-icons">close</i>
										</a>
									</div>
								</div>
								<div id="asignarExaulaDiv">
									<div class="row">
										<div class="col s8 m3 l3 center-align offset-s2">
											<br>
											<br>
											<img src="" alt="alumno" id="foto" class="responsive-img circle">	
											<h5 id="nombre"></h5>
											<p id="codigo"></p>
										</div>
										<div class="col s12 m7 l7 offset-m1 offset-l1">
											<br>
											<br>
											<div class="input-field col s12">
												<i class="material-icons prefix">assignment_id</i>
												<select name="" id="selectTipoCodigo">
													<option value="" disabled selected>Selecciona un tipo de código</option>
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
										</div>
										<div class="col s12 m7 l7 offset-m1 offset-l1">
											<div class="input-field col s12">
												<i class="material-icons prefix">assignment_id</i>
												<select name="" id="selectCodigo">
													<option value="" disabled selected>Selecciona un código</option>						
												</select>
											</div>
										</div>
										<!--div class="col s12 m7 l7 offset-m1 offset-l1">
											<div class="input-field col s12">
												<i class="material-icons prefix">mode_edit</i>
									          	<textarea id="observacion_codigo" class="materialize-textarea" row="100"></textarea>
									          	<label for="observacion">Observación</label>
									        </div>
										</div-->
										<div class="col s12 right-align">
											<br>
	  										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Asignar código" id="asignarCodigo">
												<i class="material-icons">add</i>
											</a>
											<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped cancelarButton" data-position="left" data-delay="50" data-tooltip="Cancelar">
												<i class="material-icons">close</i>
											</a>
										</div>
									</div>
								</div>
								<!--div id="asignarObservacionDiv">
									<div class="row">
										<div class="col s8 m3 l3 center-align offset-s2">
											<br>
											<br>
											<img src="" alt="alumno" id="fotoOb" class="responsive-img circle">	
											<h5 id="nombreOb"></h5>
											<p id="codigoOb"></p>
										</div>
										<div class="col s12 m7 l7">
											<br>
											<br>
											<h5 class="text-center">Asigne la observación al estudiante</h5>
											<br>
											<div class="input-field col s12">
												<i class="material-icons prefix">mode_edit</i>
									          	<textarea id="observacion_text" class="materialize-textarea" row="100"></textarea>
									          	<label for="observacion_text">Observación</label>
									        </div>
										</div>
										<div class="col s12 right-align">
											<br>
	  										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Asignar observación" id="asignarObservacion">
												<i class="material-icons">add</i>
											</a>
											<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped cancelarButton" data-position="left" data-delay="50" data-tooltip="Cancelar">
												<i class="material-icons">close</i>
											</a>
										</div>
									</div>
								</div-->
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="modalEtapas" class="modal">
			    <div class="modal-content">
			      	<h4>ETAPAS</h4>
			      	<p>Ver una etapa: </p>
			      	<select name="" id="selectEtapa">
						<option value="0" disabled selected>Selecciona una etapa</option>
						<?php 
							require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
							$sql = "select id_etapa,nombre from etapas where estado='Activo'";
							$params = array("");
							$data = Database::getRows($sql, $params);
							foreach($data as $row){
								echo '<option value="'.$row["id_etapa"].'">'.$row["nombre"].'</option>';
							}
						?>
					</select>
					<br>
					<br>
					<br>
					<br>
		    	</div>
			    <div class="modal-footer">
			      <a id="viewReport" class="modal-action waves-effect waves-green btn-flat">Aceptar</a>
			      <a id="allReport" class="modal-action waves-effect waves-green btn-flat">Ver reporte completo</a>
			    </div>
		  	</div>
		</div>
		
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("codigosexaula"); ?>">

		<?php require_once 'include/modalHistorialCodigos.php' ?>
		<?php require_once 'include/scripts.php' ?>
		
		<script type="text/javascript" src="/privado/js/disciplina.js"></script>
	</body>
</html>