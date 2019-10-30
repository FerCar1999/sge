<?php 
date_default_timezone_set('America/El_Salvador');        
session_start();    
?>
<html>
	<?php require_once 'include/header.php' ?>
	<body>
		<?php require_once 'include/menu.php' ?>
		<?php 
			require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/notificaciones/notificaciones.php"); 
			$data = notificaciones::countNotificaciones($_SESSION['id_personal']);
			$dataLeida = notificaciones::countNotificacionesLeidas($_SESSION['id_personal']);
		?>
		<div class="content">
			<div class="row">
	      		<div class="col s12">
	      			<?php  
	      				if ($data[0] == 0 && $dataLeida[0] == 0) {
	      					echo '<br><h4 class="center-align">SIN NOTIFICACIONES NUEVAS</h4><br>';
	      				}else{
	      					if ($dataLeida[0] == 0) {
      						echo '<br><h4 class="center-align">TIENES NOTIFICACIONES NUEVAS</h4><br>';
	      					}else{
      						echo '<br><h4 class="center-align">SIN NOTIFICACIONES NUEVAS</h4><br>';
	      					}
	      					$notificacionesData = notificaciones::getNotificaciones($_SESSION['id_personal']);
	      					if (count($notificacionesData) > 0) {
	      					 	foreach ($notificacionesData as $notificacion) {
	      							echo $cardNotificaciones = '
										<div class="card-panel grey lighten-3 '.$notificacion[0].'">
	        								<div class="row">
	          									<div class="col s10">
	          										<span class="tituloNotificacion">'.$notificacion[2].'</span>
	          									</div>
	          									<div class="col s2 right-align">
	          										<a onclick="ajaxNotification('.$notificacion[0].')" style="color:#212121!important;">
	          										<i class="material-icons small tooltipped" id="vista" data-position="left" data-delay="50" data-tooltip="Marcar como leída">done</i>
	          										</a>
	          										<a onclick="eliminarNotificacion('.$notificacion[0].')" style="color:#212121!important;">
	          										<i class="material-icons small tooltipped" id="deleteNotification" data-position="left" data-delay="50" data-tooltip="Eliminar notificacion">close</i>
	          										</a>
	          										<p class="hide" id="pk_notificacion">'.$notificacion[0].'</p>
	          									</div>
	          									<div class="col s12">
	          										<p class="textNotificacion">'.$notificacion[3].'</p>
	          									</div>
	          								</div>
	          								<p class="right-align"><b>Fecha: '.$notificacion[4].'</b></p>
	        							</div>';
	      						}	
	      					}
	      					$notificacionesLeidas = notificaciones::getNotificacionesLeidas($_SESSION['id_personal']);
	      					if (count($notificacionesLeidas) > 0) {
	      					 	foreach ($notificacionesLeidas as $notificacionLeida) {
	      					 		echo '<br><h5 class="center-align leidas">NOTIFICACIONES LEÍDAS</h5><br>';
	      							echo $cardNotificaciones = '
	      								<p class="totalNL hide">'.count($notificacionesLeidas).'</p>
										<div class="card-panel grey lighten-3 '.$notificacionLeida[0].'">
	        								<div class="row">
	          									<div class="col s10">
	          										<span class="tituloNotificacion">'.$notificacionLeida[2].'</span>
	          									</div>
	          									<div class="col s2 right-align">
	          										
	          										<i class="material-icons small tooltipped checkNotification" id="vista" data-position="left" data-delay="50" data-tooltip="Marcar como leída">done_all</i>
	          										<a onclick="eliminarNotificacion('.$notificacionLeida[0].')" style="color:#212121!important;">
	          										<i class="material-icons small tooltipped" id="deleteNotification" data-position="left" data-delay="50" data-tooltip="Eliminar notificacion">close</i>
	          										</a>
	          										<p class="hide" id="pk_notificacion">'.$notificacionLeida[0].'</p>
	          									</div>
	          									<div class="col s12">
	          										<p class="textNotificacion">'.$notificacionLeida[3].'</p>
	          									</div>
	          								</div>
	          								<p class="right-align"><b>Fecha: '.$notificacionLeida[4].'</b></p>
	        							</div>';
	      						}	
	      					}
	      				}
	      			?>
	      		</div>
	    	</div>
    	</div>
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("inicio"); ?>">
		<?php require_once 'include/scripts.php' ?>		
		<script type="text/javascript" src="/privado/js/notificaciones.js"></script>
	</body>
</html>