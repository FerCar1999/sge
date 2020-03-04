<?php 
date_default_timezone_set('America/El_Salvador');        
session_start();    
?>
<html>
	<?php require_once 'include/header.php' ?>
<body>
	<?php require_once 'include/menu.php' ?>
	<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/estadisticas/card-stats.php") ?>

	<div class="content">
		<br>
		
		<?php 
		// verifica si admin para ver que dashboard mostrar
		if(isset($_SESSION["isAdmin"])){
			
			if($_SESSION["isAdmin"]) include_once('dashboard-admin.php');
		}else include_once('dashboard-maestro.php'); 
		
		?>
	
</div>

<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("inicio"); ?>">
<?php require_once 'include/scripts.php' ?>
<script src="/utils/highcharts.js" ></script>	
<script src="/utils/broken-axis.js" ></script>	
<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/estadisticas/tipos_codigos_grafico.php") ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/estadisticas/especialidades_grafica_mensual.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/estadisticas/inasistencias_bachillerato.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/estadisticas/inasistencias_tercer_ciclo.php") ?>

<script src="/privado/js/estadisticas.js" ></script>	
</body>
</html>