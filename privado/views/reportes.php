<?php require_once 'include/header.php' ?>
<?php 
	date_default_timezone_set('America/El_Salvador'); 
	session_start();
?>
<html>
<body>
	<?php require_once 'include/menu.php' ?>
	<div class="content">
		<div class="row">
			<div class="col s12 l4">
				<div class="card">
					<div class="card-content text-center">
						<i  class="material-icons icon-stat">watch_later</i>
						<p class="card-stats-title">Inasistencias</p>
						<hr>
						<form action="inasistencias_reporte" method="post" target="_blank">
							<div class="row">
								<br>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_inicio" type="text" class="datepicker" name="inicio">
									<label for="date_inicio">Fecha de inicio (AAAA-MM-DD)</label>
								</div>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_fin" type="text" class="datepicker" name="fin">
									<label for="date_fin">Fecha fin (AAAA-MM-DD)</label>
								</div>
								<button class="waves-effect waves-light btn amber darken-4">VER REPORTE</button>
							</div>
						</form>
					</div>									
				</div>
			</div>
			<div class="col s12 l4">
				<div class="card">
					<div class="card-content text-center">
						<i  class="material-icons icon-stat">alarm_off</i>
						<p class="card-stats-title">Llegadas tarde a la institución</p>
						<hr>
						<form action="llegadas_tarde_ins_reporte" method="post" target="_blank">
							<div class="row">
								<br>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_inicio" type="text" class="datepicker" name="inicio">
									<label for="date_inicio">Fecha de inicio</label>
								</div>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_fin" type="text" class="datepicker" name="fin">
									<label for="date_fin">Fecha fin</label>
								</div>
								<button class="waves-effect waves-light btn amber darken-4">VER REPORTE</button>
							</div>
						</form>		
					</div>									
				</div>
			</div>
			<div class="col s12 l4">
				<div class="card">
					<div class="card-content text-center">
						<i  class="material-icons icon-stat">gavel</i>
						<p class="card-stats-title">Llegadas tarde a clases</p>
						<hr>
						<form action="llegadas_tarde_clase_reporte" method="post" target="_blank">
							<div class="row">
								<br>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_inicio" type="text" class="datepicker" name="inicio">
									<label for="date_inicio">Fecha de inicio (AAAA-MM-DD)</label>
								</div>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_fin" type="text" class="datepicker" name="fin">
									<label for="date_fin">Fecha fin (AAAA-MM-DD)</label>
								</div>
								<button class="waves-effect waves-light btn amber darken-4">VER REPORTE</button>
							</div>
						</form>
					</div>									
				</div>
			</div>
			<div class="col s12 l4">
				<div class="card">
					<div class="card-content text-center">
						<i  class="material-icons icon-stat">list</i>
						<p class="card-stats-title">Listas académicas</p>
						<hr>
						<form action="lista_academica" method="post" target="_blank">
							<div class="row">
								<br>
								<div class="input-field col s12">
									<select name="id_gradoAc" id="selectGradoAc">
										<?php 
										require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
										$sql = "select id_grado,nombre from grados where estado='Activo'";
										$params = array("");

										$data = Database::getRows($sql, $params);
										foreach($data as $row)
										{																	
											echo '<option value="'.$row["id_grado"].'">'.$row["nombre"].'</option>';
										}
										?>
									</select>
									<label>Grado</label>
								</div>
								<br>
								<div class="input-field col s12">
									<select name="id_seccionAc" id="selectSeccionAc">
										<?php 
										require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
										$sql = "select id_seccion,nombre from secciones where estado='Activo'";
										$params = array("");

										$data = Database::getRows($sql, $params);
										foreach($data as $row)
										{																	
											echo '<option value="'.$row["id_seccion"].'">'.$row["nombre"].'</option>';
										}
										?>
									</select>
									<label>Sección</label>
								</div>
								<br>
								<div class="input-field col s12">
									<select name="id_grupo_academicoAc" id="selectGrupoAc">
										<?php 
										require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
										$sql = "select id_grupo_academico,nombre from grupos_academicos where estado='Activo'";
										$params = array("");

										$data = Database::getRows($sql, $params);
										foreach($data as $row)
										{																	
											echo '<option value="'.$row["id_grupo_academico"].'">'.$row["nombre"].'</option>';
										}
										?>
									</select>
									<label>Grupo académico (Solo bachillerato)</label>
								</div>
								<button class="waves-effect waves-light btn amber darken-4">VER LISTA</button>
							</div>
						</form>
					</div>									
				</div>
			</div>
			<div class="col s12 l4">
				<div class="card">
					<div class="card-content text-center">
						<i  class="material-icons icon-stat">list</i>
						<p class="card-stats-title">Listas de especialidades de bachillerato</p>
						<hr>
						<form action="lista_tecnica" method="post" target="_blank">
							<div class="row">
								<br>
								<div class="input-field col s12">
									<select name="id_gradoTc" id="selectGradoTc">
										<?php 
										require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
										$sql = "select id_grado,nombre from grados where estado='Activo' AND id_nivel=2";
										$params = array("");

										$data = Database::getRows($sql, $params);
										foreach($data as $row)
										{																	
											echo '<option value="'.$row["id_grado"].'">'.$row["nombre"].'</option>';
										}
										?>
									</select>
									<label>Grado</label>
								</div>
								<br>
								<div class="input-field col s12">
									<select name="id_especialidadTc" id="selectEspecialidad">
										<?php 
										require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
										$sql = "select id_especialidad,nombre from especialidades where estado='Activo' AND nombre != 'Ninguna'";
										$params = array("");

										$data = Database::getRows($sql, $params);
										foreach($data as $row)
										{																	
											echo '<option value="'.$row["id_especialidad"].'">'.$row["nombre"].'</option>';
										}
										?>
									</select>
									<label>Especialidad</label>
								</div>
								<br>
								<div class="input-field col s12">
									<select name="id_grupo_tecnico" id="selectGrupoTc">
										<?php 
										require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
										$sql = "select id_grupo_tecnico,nombre from grupos_tecnicos where estado='Activo' AND nombre != 'Indefinido'";
										$params = array("");

										$data = Database::getRows($sql, $params);
										foreach($data as $row)
										{																	
											echo '<option value="'.$row["id_grupo_tecnico"].'">'.$row["nombre"].'</option>';
										}
										?>
									</select>
									<label>Grupo técnico</label>
								</div>
								<button class="waves-effect waves-light btn amber darken-4">VER LISTA</button>
							</div>
						</form>
					</div>									
				</div>
			</div>
			<div class="col s12 l4">
				<div class="card">
					<div class="card-content text-center">
						<i  class="material-icons icon-stat">gavel</i>
						<p class="card-stats-title">Inasistencias justificadas</p>
						<hr>
						<form action="reporte_inasistencias_justificadas" method="post" target="_blank">
							<div class="row">
								<br>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_inicio_just_total" type="text" class="datepicker" name="inicio">
									<label for="date_inicio">Fecha de inicio (AAAA-MM-DD)</label>
								</div>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_fin_just_total" type="text" class="datepicker" name="fin">
									<label for="date_fin">Fecha fin (AAAA-MM-DD)</label>
								</div>
								<div class="input-field col s12">
									<select name="id_nivel" id="selectNivel_just_total">
										<?php 
										require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
										$sql = "select id_nivel,nombre from niveles where estado='Activo'";
										$params = array("");

										$data = Database::getRows($sql, $params);
										foreach($data as $row)
										{																	
											echo '<option value="'.$row["id_nivel"].'">'.$row["nombre"].'</option>';
										}
										?>
									</select>
									<label>Nivel</label>
								</div>
								<button class="waves-effect waves-light btn amber darken-4">VER REPORTE</button>
							</div>
						</form>
						<button onclick = "descargarExelClases3()" class="waves-effect waves-light btn amber darken-4">EXCEL</button>
					</div>									
				</div>
			</div>
			<!--div class="col s12 l4">
				<div class="card">
					<div class="card-content text-center">
						<i  class="material-icons icon-stat">gavel</i>
						<p class="card-stats-title">Inasistencias injustificadas</p>
						<hr>
						<form action="reporte_inasistencias_injustificadas" method="post" target="_blank">
							<div class="row">
								<br>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_inicio_injust_total" type="text" class="datepicker" name="inicio">
									<label for="date_inicio">Fecha de inicio (AAAA-MM-DD)</label>
								</div>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_fin_injust_total" type="text" class="datepicker" name="fin">
									<label for="date_fin">Fecha fin (AAAA-MM-DD)</label>
								</div>
								<div class="input-field col s12">
									<select name="id_nivel" id="selectNivel_injust_total">
										<?php 
										require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
										$sql = "select id_nivel,nombre from niveles where estado='Activo'";
										$params = array("");

										$data = Database::getRows($sql, $params);
										foreach($data as $row)
										{																	
											echo '<option value="'.$row["id_nivel"].'">'.$row["nombre"].'</option>';
										}
										?>
									</select>
									<label>Nivel</label>
								</div>
								<button class="waves-effect waves-light btn amber darken-4">VER REPORTE</button>
							</div>
						</form>
						<button onclick = "descargarExelClases4()" class="waves-effect waves-light btn amber darken-4">EXCEL</button>
					</div>									
				</div>
			</div-->
			<div class="col s12 l4">
				<div class="card">
					<div class="card-content text-center">
						<i  class="material-icons icon-stat">gavel</i>
						<p class="card-stats-title">Inasistencias injustificadas</p>
						<hr>
						<form action="reporte_inasistencias_injustificadas" method="post" target="_blank">
							<div class="row">
								<br>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_inicio_just_clase" type="text" class="datepicker" name="inicio">
									<label for="date_inicio">Fecha de inicio (AAAA-MM-DD)</label>
								</div>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_fin_just_clase" type="text" class="datepicker" name="fin">
									<label for="date_fin">Fecha fin (AAAA-MM-DD)</label>
								</div>
								<div class="input-field col s12">
									<select name="id_nivel" id="selectNivel_just_clase">
										<?php 
										require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
										$sql = "select id_nivel,nombre from niveles where estado='Activo'";
										$params = array("");

										$data = Database::getRows($sql, $params);
										foreach($data as $row)
										{																	
											echo '<option value="'.$row["id_nivel"].'">'.$row["nombre"].'</option>';
										}
										?>
									</select>
									<label>Nivel</label>
								</div>																
								<button class="waves-effect waves-light btn amber darken-4">VER REPORTE</button>																																																		
							</div>
						</form>
						
								<button onclick = "descargarExelClases()" class="waves-effect waves-light btn amber darken-4">EXCEL</button>
						
					</div>									
				</div>
			</div>
			<!--div class="col s12 l4">
				<div class="card">
					<div class="card-content text-center">
						<i  class="material-icons icon-stat">gavel</i>
						<p class="card-stats-title">Inasistencias injustificadas a clases</p>
						<hr>
						<form action="reporte_inasistencias_injustificadas_clases" method="post" target="_blank">
							<div class="row">
								<br>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_inicio_injust_clase" type="text" class="datepicker" name="inicio">
									<label for="date_inicio">Fecha de inicio (AAAA-MM-DD)</label>
								</div>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_fin_injust_clase" type="text" class="datepicker" name="fin">
									<label for="date_fin">Fecha fin (AAAA-MM-DD)</label>
								</div>
								<div class="input-field col s12">
									<select name="id_nivel" id="selectNivel_injust_clase">
										<?php 
										require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
										$sql = "select id_nivel,nombre from niveles where estado='Activo'";
										$params = array("");

										$data = Database::getRows($sql, $params);
										foreach($data as $row)
										{																	
											echo '<option value="'.$row["id_nivel"].'">'.$row["nombre"].'</option>';
										}
										?>
									</select>
									<label>Nivel</label>
								</div>
								<button class="waves-effect waves-light btn amber darken-4">VER REPORTE</button>
							</div>
						</form>
						<button onclick = "descargarExelClases2()" class="waves-effect waves-light btn amber darken-4">EXCEL</button>
					</div>									
				</div>
			</div-->
			<div class="col s12 l4">
				<div class="card">
					<div class="card-content text-center">
						<i  class="material-icons icon-stat">gavel</i>
						<p class="card-stats-title">Inasistencias por bloque</p>
						<hr>
						<form action="control_bloques" method="post" target="_blank">
							<div class="row">
								<br>
								<div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_inicio" type="text" class="datepicker" name="inicio">
									<label for="date_inicio">Fecha (AAAA-MM-DD)</label>
								</div>
								<button class="waves-effect waves-light btn amber darken-4">VER REPORTE</button>
							</div>
						</form>
					</div>									
				</div>
			</div>
         <!--DESDE ACA SE PEGARA A LA PARTE PRINCIPAL-->
            <div class="col s12 l4">
				<div class="card">
					<div class="card-content text-center center">
                    <i class="material-icons medium">assignment_turned_in</i>
						<p class="card-stats-title">Asistencias por seccion y tiempo</p>
						<hr>
						<form action="/privado/views/excel.php" method="post" target="_blank">
							<div class="row">
								<br>
                                <div class="input-field col s12">
                                    <select id="idComboGrados" name ="Anio">
                                    
                                    </select>
                                    <label>Seleccion de año</label>
                                </div>
                                <div class="input-field col s12">
                                    <select id="idComboSeccion" name ="Seccion">
                                    
                                    </select>
                                    <label>Seleccion de seccion</label>
                                </div>
                                <div class="input-field col s12">
                                    <select id="idComboGrAc" name ="Grupoacademico">
                                    
                                    </select>
                                    <label>Seleccion de grupo academico (bachillerato)</label>
                                </div>
                                <div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_inicio_acade" type="text" class="datepicker" name="Fechainicio">
									<label for="date_inicio_acade">Fecha Inicio (AAAA-MM-DD)</label>
								</div>
                                <div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_final_ok" type="text" class="datepicker" name="Fechafin">
									<label for="date_final_ok">Fecha Final (AAAA-MM-DD)</label>
								</div>
								<p class="center">
                                <button class="waves-effect waves-light btn amber darken-4" name="reporte">VER REPORTE</button>
                                </p>
							</div>
						</form>
					</div>									
				</div>
			</div>
           <!-- HASTA ACA SE PEGARA A LA PARTE PRINCIPAL-->

            <!--EMPIEZA ACA CARD ESPECIALIDAD-->
            <div class="col s12 l4">
				<div class="card">
					<div class="card-content text-center center">
                    <i class="material-icons medium">assignment_turned_in</i>
						<p class="card-stats-title">Asistencias por especialidad y tiempo</p>
						<hr>
						<form action="/privado/views/excel.php" method="post" target="_blank">
							<div class="row">
								<br>
                                <div class="input-field col s12">
                                    <select id="idComboGradosEsp" name ="Anio">
                                    
                                    </select>
                                    <label>Seleccion de año</label>
                                </div>
                                <div class="input-field col s12">
                                    <select id="idComboEspecialidad" name ="Especialidad">
                                    
                                    </select>
                                    <label>Seleccion de especialidad</label>
                                </div>
                                <div class="input-field col s12">
                                    <select id="idComboGrTec" name ="Grupotecnico">
                                    
                                    </select>
                                    <label>Seleccion de grupo tecnico</label>
                                </div>
                                <div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_inicio_grupotec" type="text" class="datepicker" name="Fechainicio">
									<label for="date_inicio_grupotec">Fecha Inicio (AAAA-MM-DD)</label>
								</div>
                                <div class="input-field col s12">
									<input value="<?php echo date('Y-m-d')?>" id="date_fin_grupotec" type="text" class="datepicker" name="Fechafin">
									<label for="date_fin_grupotec">Fecha Final (AAAA-MM-DD)</label>
								</div>
								<p class="center">
                                <button class="waves-effect waves-light btn amber darken-4" name="reporte">VER REPORTE</button>
                                </p>
							</div>
						</form>
					</div>									
				</div>
			</div>
<!--TERMINA ACA CARD ESPECIALIDAD-->
		</div>
	</div>

<form  id = "descargarExcelClases" action="/privado/php/reportes/conducta/exportarExcel.php" class="hidden" target="_blank" method="POST">
  <input type="hidden" id= "clases_accion" value="10" name="accion">
	<input type="hidden" id= "clases_inicio" value="10" name="fecha_inicio">
	<input type="hidden" id= "clases_fin" value="10" name="fecha_final">
	<input type="hidden" id= "clases_nivel" value="10" name="nivel">
  <input type="submit" class="">
</form>

<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("inicio"); ?>">
<?php require_once 'include/scripts.php' ?>
<script type="text/javascript" src="/privado/js/reportes.js"></script>
<script type="text/javascript" src="/privado/js/reporte.js"></script>
</body>
</html>
