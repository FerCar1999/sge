<?php
session_start();
// si tiene una session activa muestra el dashboard
if (!isset($_SESSION['id_estudiante'])) {
	header("location: /inicio");
}
?>
<html>

<head>
	<meta charset="UTF-8">
	<title>Diario Pedagógico</title>
	<!--link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"-->
	<link type="text/css" rel="stylesheet" href="/utils/materialize.min.css" media="screen,projection" />
	<link type="text/css" rel="stylesheet" href="/utils/sweetalert.css" media="screen,projection" />
	<link type="text/css" rel="stylesheet" href="/utils/fonts/material-icons.css" media="screen,projection" />
	<link type="text/css" rel="stylesheet" href="/utils/materializeclockpicker.css" media="screen,projection" />
	<link type="text/css" rel="stylesheet" href="/publico/css/content.css" media="screen,projection" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
	<nav>
		<div class="nav-wrapper black navAlumno">
			<!-- <img src="/media/img/logo.png" class="responsiveLogo"> -->
			<p href="#" class="brand-logo truncate white-text">SGE</p>
			<ul id="nav-mobile" class="right">
				<li class="tooltipped" data-position="left" data-delay="50" data-tooltip="Cerrar sesión"><a href="/publico/php/logout.php"><i class="material-icons white-text">power_settings_new</i></a></li>
			</ul>
		</div>
	</nav>
	<div class="row" style="margin-bottom: 0px;">
		<div class="col s12 m12 l3 xl3 red darken-2 white-text" style="padding-bottom: 7px;">
			<div class="row">
				<div class="col s12 center-align">
					<p></p>
					<img src="<?php echo 'http://sge.ricaldone.edu.sv/media/img/alumnos/' . $_SESSION['codigo'] . '.JPG'; ?>" alt="alumno" class="responsive-img" width="48.5%">
				</div>
				<div class="col s12">
					<p id="nombreAlumno" class="center-align white-text" style="font-size: 18px; font-weight: 600"><?php echo $_SESSION['nombres'] . " " . $_SESSION['apellidos'] ?></p>
					<p class="center-align white-text" style="font-size: 18px; font-weight: 600;"><?php echo $_SESSION['codigo'] ?></p>
					<p class="center-align hide-on-med-and-down" style="font-size: 16px; font-weight: 600;"><?php echo $_SESSION['especialidad'] ?></p>
					<p class="center-align hide-on-med-and-down" style="font-size: 16px; font-weight: 600;">
						<?php
						if ($_SESSION['especialidad'] != "Indefinido") {
							echo $_SESSION['grado'] . " " . $_SESSION['seccion'];
						} else {
							echo $_SESSION['grado'] . " " . substr($_SESSION['seccion'], 0, 1);
						}
						?>
					</p>
					<hr>
					<p class="center-align" style="font-size: 16px; font-weight: 400;"><b style="font-size: 16px; font-weight: 600;">Docente guía: </b><?php echo $_SESSION['docente'] ?></p>
					<p class="center-align" style="font-size: 16px; font-weight: 400;"><b style="font-size: 16px; font-weight: 600;">Correo del Docente: </b><?php echo $_SESSION['correo'] ?></p>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l9 xl9">
			<p></p>
			<div class="row">
				<div class="col s12 m4 l4">
					<a href="conducta" target="_blank">
						<div class="card yellow darken-2 black-text">
							<div class="card-content" style="padding: 10px;">
								<i class="material-icons iconReport black-text">gavel</i>
								<h6 style="padding-top: 10px;" class="center-align bold">REPORTE DE CONDUCTA</h6>
							</div>
						</div>
					</a>
				</div>
				<div class="col s12 m4 l4">
					<a href="horario-academico" target="_blank">
						<div class="card yellow darken-2 black-text">
							<div class="card-content" style="padding: 10px;">
								<i class="material-icons iconReport black-text">book</i>
								<h6 style="padding-top: 10px;" class="center-align bold">HORARIO ACADÉMICO</h6>
							</div>
						</div>
					</a>
				</div>
				<?php
				if ($_SESSION['especialidad'] != "Tercer ciclo") {
					echo ('
						<div class="col s12 m4">
				<a href="horario-tecnico" target="_blank">
					<div class="card yellow darken-2 black-text">
						<div class="card-content" style="padding: 10px;">
							<i class="material-icons iconReport white-text">description</i>
							<h6 style="padding-top: 10px;"s class="center-align bold">HORARIO TÉCNICO</h6>
						</div>
					</div>
				</a>
				</div>
				');
				}
				?>
				<div class="col s12 m4 l4">
					<a onclick="mostrarEstadoCuenta('<?=$_SESSION['nombres']?>','<?=$_SESSION['apellidos'] ?>');">
						<div class="card yellow darken-2 black-text">
							<div class="card-content" style="padding: 10px;">
								<i class="material-icons iconReport black-text">attach_money</i>
								<h6 style="padding-top: 10px;" class="center-align bold">ESTADO DE CUENTA</h6>
							</div>
						</div>
					</a>
				</div>
				<div class="col s12" style="display: none;">
					<h5 class="center-align">Estado de Cuenta:</h5>
					<table class="centered" id="tabla">
						<thead>
							<tr>
								<th>Concepto</th>
								<th>Pago</th>
								<th>Mora</th>
								<th>Total Pagado</th>
								<th>Fecha Pagado</th>
								<th>Debe</th>
								<th>Mora</th>
								<th>Total Debe</th>
								<th>Última Fecha Pago</th>
							</tr>
						</thead>

						<tbody>
							<tr id="0"></tr>
							<tr id="1"></tr>
							<tr id="2"></tr>
							<tr id="3"></tr>
							<tr id="4"></tr>
							<tr id="5"></tr>
							<tr id="6"></tr>
							<tr id="7"></tr>
							<tr id="8"></tr>
							<tr id="9"></tr>
							<tr id="10"></tr>
							<tr id="11"></tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="/utils/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="/utils/materialize.min.js"></script>
	<script type="text/javascript" src="/utils/sweetalert.min.js"></script>
	<script type="text/javascript" src="/privado/js/botones.js"></script>
	<script type="text/javascript" src="/publico/js/estadoC.js"></script>
	<script type="text/javascript" src="/publico/js/jsPDF132/dist/jspdf.min.js"></script>
	<script type="text/javascript" src="/publico/js/jsPDF132/dist/jspdfAutoTable.js"></script>
</body>

</html>