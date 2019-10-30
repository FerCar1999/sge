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
							<span class="card-title"><p class="center-align">LLEGADAS TARDE</p></span>
							<div class="col s6 center-align">
								<p>
									<br>
							      	<input name="opciones" type="radio" id="lblautomatic" class="with-gap" checked/>
							      	<label for="lblautomatic" id="automaticRadio">Automático</label>
							      	<br>
							      	<br>
							    </p>
							</div>
							<div class="col s6 center-align">
								<p>
									<br>
							      	<input name="opciones" type="radio" id="lblmanual" class="with-gap" />
							      	<label for="lblmanual" id="manualRadio">Manual</label>
							      	<br>
							      	<br>
							    </p>
							</div>							
							<div class="row">
								<div class="input-field col s12 search-wrapper">
									<i class="material-icons prefix">person</i>
					          		<input placeholder="Carnet del estudiante" id="alumno" type="text" class="autocomplete" autocomplete="off">
					          		<label for="alumno">Estudiante</label>
				        		</div>
				        		<br>
				        		<div class="input-field col s6" id="fechaLlegadaDiv">
			                    	<i class="material-icons prefix">access_time</i>
			                        <label for="fechaLlegada">Fecha</label>
			                        <input id="fechaLlegada" class="datepicker" type="date">
			                    </div>
			                    <br>
				        		<div class="input-field col s6" id="horaLlegadaDiv">
			                    	<i class="material-icons prefix">access_time</i>
			                        <label for="horaLlegada">Hora de llegada a la institución</label>
			                        <input id="horaLlegada" class="timepicker" type="time">
			                    </div>
								<div class="center-align"><div class="col s12" id="noHayCodigos"></div></div>
								<br>
								<br>
								<div id="codigos">
									<div class="col s12 m6 l6">
										<br>
								     	<table>
									        	<thead>
									        		<tr>
									        			<td><b>Tipo</b></td>
									        			<td><b>Código</b></td>
									        			<td><b>Fecha</b></td>
									        			<td><b>Acciones</b></td>
									        		</tr>
									        	</thead>
									        	<tbody class="licodigos">
									        		<tr>
									        			<td></td>
									        			<td></td>
									        		</tr>
									        	</tbody>
									        </table>
									</div>
									<div class="col s12 m6 l6">
										<div class="input-field col s12">
									    	<br>
										    <select multiple id="selectCodigos">
										      	<option value="" disabled selected>Elija los códigos</option>
										      	<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = "select id_codigo,nombre from codigos where estado='Activo'";
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_codigo"].'">'.$row["nombre"].'</option>';
													}
												?>
									    	</select>
									    	<br>
										    <label>Códigos</label>
										  </div>
									</div>
								</div>
								<div class="col s12 right-align">
									<br>
									<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Asignar llegada tarde" id="asginarTardanza">
										<i class="material-icons">alarm_add</i>
									</a>
									<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Asignar códigos" id="asginarCodigo">
										<i class="material-icons">add</i>
									</a>
									<!--a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Asignar códigos" id="mostrarCodigo">
										<i class="material-icons">gavel</i>
									</a-->
									<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Cancelar" id="cancelarCodigo">
										<i class="material-icons">cancel</i>
									</a>
									<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Limpiar" id="cleanText">
										<i class="material-icons">wrap_text</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-content">
							<span class="card-title"><p class="center-align">REGISTROS DE LLEGADAS TARDE</p></span>
							<div class="row">
								<div class="col s12">
									<br>
							     	<table>
								        	<thead>
								        		<tr>
								        			<td><b>Nombre</b></td>
								        			<td><b>Fecha</b></td>
								        			<td><b>Acciones</b></td>
								        		</tr>
								        	</thead>
								        	<tbody class="liimpuntual">
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
		<?php require_once 'include/modalHistorialCodigos.php' ?>
		<?php require_once 'include/scripts.php' ?>
		<script type="text/javascript" src="/privado/js/llegadastarde.js"></script>
	</body>
</html>