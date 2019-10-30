<?php 
	session_start();
  	// si tiene una session activa muestra el dashboard
  	if(!isset($_SESSION['id_estudiante'])){
		header("location: /inicio");
  	}
	include($_SERVER['DOCUMENT_ROOT']."/publico/php/reportes/estadisticas_alumno.php");
	$estadistica = new Estadistica();
	$nivel = $estadistica->getNivel($_SESSION['codigo']);
	$etapa = $estadistica->getPeriodo($nivel);
?>
<html>
<head>
	<meta charset="UTF-8">
	<title>Diario Pedagógico</title>
  	<!--link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"-->
  	<link type="text/css" rel="stylesheet" href="/utils/materialize.min.css"  media="screen,projection"/>
  	<link type="text/css" rel="stylesheet" href="/utils/sweetalert.css"  media="screen,projection"/>
  	<link type="text/css" rel="stylesheet" href="/utils/fonts/material-icons.css"  media="screen,projection"/>
  	<link type="text/css" rel="stylesheet" href="/utils/materializeclockpicker.css"  media="screen,projection"/>
  	<link type="text/css" rel="stylesheet" href="/publico/css/content.css"  media="screen,projection"/>
  	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
	<nav>
		<div class="nav-wrapper grey lighten-4 navAlumno">
			<!-- <img src="/media/img/logo.png" class="responsiveLogo"> -->
			<p href="#" class="brand-logo truncate">SISTEMA DE GESTIÓN ESTUDIANTIL</p>
			<ul id="nav-mobile" class="right">
				<li class="tooltipped" data-position="left" data-delay="50" data-tooltip="Cerrar sesión"><a href="/publico/php/logout.php"><i class="material-icons">power_settings_new</i></a></li>
			</ul>
		</div>
	</nav>
	<div class="row">
		<div class="col s12 m4 l3">
			<div class="col s10 offset-s2">
				<img src="<?php echo 'http://sge.ricaldone.edu.sv/media/img/alumnos/'.$_SESSION['codigo'].'.JPG';?>" alt="Alumno" class="responsiveAlumno">
				<br>
				<br>
			</div>
			<p class="center-align nombre"><?php echo $_SESSION['nombres'] . " " . $_SESSION['apellidos']?></p>
			<p class="center-align alumnoText bold"><?php echo $_SESSION['codigo']?></p>
			<p class="center-align alumnoText"><?php echo $_SESSION['especialidad']?></p>
			<p class="center-align alumnoText">
			<?php 
				if ($_SESSION['especialidad'] != "Indefinido"){
					echo $_SESSION['grado'] . " ".$_SESSION['seccion'];
				}else{
					echo $_SESSION['grado'] . " ".substr($_SESSION['seccion'],0,1);
				}
			?>
			</p>
			<hr>
			<p class="center-align alumnoText"><b>Docente guía: </b><?php echo $_SESSION['docente']?></p>
			<p class="center-align alumnoText"><b>Correo: </b><?php echo $_SESSION['correo']?></p>
		</div>
		<div class="col s12 m8 l9 card-parent">
			<div class="col s12 m4">
				<a href="conducta" target="_blank">
					<div class="card">
						<div class="card-content">
							<i class="material-icons iconReport">gavel</i>
						</div>
						<div class="card-action">
							<h6 class="center-align">REPORTE DE CONDUCTA</h6>
						</div>
					</div>
				</a>
			</div>
			<div class="col s12 m4">
				<a href="horario-academico" target="_blank">
					<div class="card">
						<div class="card-content">
							<i class="material-icons iconReport">book</i>
						</div>
						<div class="card-action">
							<h6 class="center-align">HORARIO ACADÉMICO</h6>
						</div>
					</div>
				</a>
			</div>
	<?php  
		if ($_SESSION['especialidad'] != "Tercer ciclo") {
			echo('
			<div class="col s12 m4">
				<a href="horario-tecnico" target="_blank">
					<div class="card">
						<div class="card-content">
							<i class="material-icons iconReport">description</i>
						</div>
						<div class="card-action">
							<h6 class="center-align">HORARIO TÉCNICO</h6>
						</div>
					</div>
				</a>
			</div>
			');
		}
	?>
				<div class="col s12 m4">
					<div class="card">
						<div class="card-content">
							<p id="estadisticaNumero"><?php $estadistica->getCodigosNegativos($_SESSION['codigo'], $etapa['inicio'], $etapa['fin']); ?></p>
						</div>
						<div class="card-action">
							<h6 class="center-align">CÓDIGOS NEGATIVOS DURANTE EL PERÍODO</h6>
						</div>
					</div>
				</div>
				<div class="col s12 m4">
					<div class="card">
						<div class="card-content">
							<p id="estadisticaNumero"><?php $estadistica->getCodigosPositivos($_SESSION['codigo'], $etapa['inicio'], $etapa['fin']); ?></p>
						</div>
						<div class="card-action">
							<h6 class="center-align">CÓDIGOS POSITIVOS DURANTE EL PERÍODO</h6>
						</div>
					</div>
				</div>
				<div class="col s12 m4">
					<div class="card">
						<div class="card-content">
							<p id="estadisticaNumero"><?php $estadistica->getInasistenciaClase($_SESSION['codigo'], $etapa['inicio'], $etapa['fin']); ?></p>
						</div>
						<div class="card-action">
							<h6 class="center-align">INASISTENCIAS A CLASE DURANTE EL PERÍODO</h6>
						</div>
					</div>
				</div>
				<div class="col s12 m4">
					<div class="card">
						<div class="card-content">
							<p id="estadisticaNumero"><?php $estadistica->getInasistenciaTotal($_SESSION['codigo'], $etapa['inicio'], $etapa['fin']); ?></p>
						</div>
						<div class="card-action">
							<h6 class="center-align">INASISTENCIAS A LA INSTITUCIÓN DURANTE EL PERÍODO</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="/utils/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="/utils/materialize.min.js"></script>
	<script type="text/javascript" src="/utils/sweetalert.min.js"></script>
	<script type="text/javascript" src="/privado/js/botones.js"></script>
</body>
</html>
