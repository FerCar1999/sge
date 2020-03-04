
<?php 
date_default_timezone_set('America/El_Salvador');        
session_start();    
?>
<html>
<?php $nonavfixed=true; ?>
<?php require_once 'include/header.php' ?>
<body>
	<?php require_once 'include/menu.php' ?>
	<div class="content-full">
		<div class="row">
			<div class="col s12">
				<div class="card">						
					<div class="card-content">
						<div class="row">
							<div class="col s12 text-center">
								<span class="card-title">Configuración secciones técnicas</span>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12 m3">
								<i class="material-icons prefix">build</i>
								<select name="" id="select_grupos">
									<?php 
									require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
									$sql = "select id_grupo_tecnico,nombre from grupos_tecnicos where estado='Activo'";
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
							<div class="input-field col s12 m3">
								<i class="material-icons prefix">person</i>
								<select name="" id="">
									<option  id="" value="">Issela Mejia</option>
								</select>
								<label>Guía proximo año</label>
							</div>
							<div class="input-field col s12 m3">
								<i class="material-icons prefix">extension</i>
								<select name="" id="select_especialidad">
									<?php 
									require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
									$sql = "select id_especialidad,nombre from especialidades where estado='Activo'";
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
							<div class="input-field col s12 m3">
								<i class="material-icons prefix">extension</i>
								<select name="" id="select_grado">
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
								<label>Año</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12 m6">
				<div class="card">
					<div class="card-content">							
						<div class="input-field col  s12">									
							<input id="buscar_estudiante" type="text" class="validate">
							<label for="icon_prefix">Buscar Alumnos sin asignar</label>
						</div>
						<!-- Tabla de actividades-->
						<table class="striped">
							<thead>
								<tr>									
									<th >Nombres</th>
									<th >Apellidos</th>
									<th >Codigo</th>							      							 
									<th >Accion</th>     
								</tr>
							</thead>

							<tbody id="table-estudiantes">        
							</tbody>
							</table>

									</div>
								</div>
							</div>
							<div class="col s12 m6">
								<div class="card">
									<div class="card-content">
										<ul class="tabs">

										<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/matriculados/imprimir_secciones.php"); ?>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="fixed-action-btn" id="floatMatriculado">
						<a onclick="aceptar_draft();" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped right" data-position="left" data-delay="50" data-tooltip="Agregar" id="agregar_personal">
							<i class="material-icons">add</i>
						</a>
					</div>
					<?php require_once 'include/scripts.php' ?>
					<script type="text/javascript" src="/privado/js/matriculados.js"></script>
				</body>
				</html>		