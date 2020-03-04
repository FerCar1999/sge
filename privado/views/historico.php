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
						<h5 class="text-center">HISTORIAL ALUMNO</h5>
						<div class="row">
							<div class="input-field col s12">
								<i class="material-icons prefix">gavel</i>
								<select id="select_anio">
									<option value="2020">2020</option>
									<option value="2019">2019</option>
									<option value="2018">2018</option>
									<option value="2017">2017</option>
								</select>
								<label>Año</label>
							</div>
							<form action="/privado/php/reportes/conducta/conducta.php" class="hide" method="post" target="_blank" id="reporteAlumnoForm">
								<input id="alumnoCod" type="text" name="codigo">
								<input id="y" type="text" name="y">
								<input id="etapaCod" type="text" name="etapa" value="0">
							</form>
							<form action="">
								<div class="input-field col s12 search-wrapper">
									<i class="material-icons prefix">person</i>
									<input id="alumno" type="text" class="autocomplete" autocomplete="off" onkeyup="buscarAlumnos();">
									<label for="alumno">Carnet o nombre del estudiante</label>
								</div>
								<div class="col m3 s12 right">
									<br>
									<button class="btn amber darken-4 right col s12" id="buscarAlumno" target="_blank" disabled>
										Cambiar año
										<i class="right material-icons">autorenew</i>
									</button>
									<br>
									<br>
								</div>
								<?php
								$colspan = 0;
								if ($_SESSION["permiso"] == "Administrador") {
									echo '					        				 	
								        		<div class="col m3 s12 right">
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
								?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/privado/php/functions/relog.php");
											comprobar_permiso("codigosexaula"); ?>">


	<?php require_once 'include/scripts.php' ?>
	<script type="text/javascript" src="/privado/js/historico.js"></script>
</body>

</html>
