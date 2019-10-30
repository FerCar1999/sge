	<div class="row">
		<div class="col s12 m3">
			<div class="card">
				<div class="card-content text-center">
					<i  class="material-icons icon-stat">alarm</i>
					<p class="card-stats-title">Clases Pendientes</p>
					<hr>
					<h4 class="card-stats-number"><?php echo stat_clases_pendientes(); ?></h4>									
				</div>									
			</div>
		</div>
		<div class="col s12 m3">
			<div class="card">
				<div class="card-content text-center">
					<i  class="material-icons icon-stat">watch_later</i>
					<p class="card-stats-title">Clases sin Asistencia</p>
					<hr>
					<h4 class="card-stats-number"><?php echo stat_clases_sin_pasar_lista(); ?></h4>									
				</div>									
			</div>
		</div>
		<div class="col s12 m3">
			<div class="card">
				<div class="card-content text-center">
					<i  class="material-icons icon-stat">alarm_off</i>
					<p class="card-stats-title">Observaciones</p>
					<hr>
					<h4 class="card-stats-number"><?php echo stat_observaciones_asignadas_maestro(); ?></h4>									
				</div>									
			</div>
		</div>
		<div class="col s12 m3">
			<div class="card">
				<div class="card-content text-center">
					<i  class="material-icons icon-stat">gavel</i>
					<p class="card-stats-title">Códigos Asignados</p>
					<hr>
					<h4 class="card-stats-number"><?php echo stat_codigos_asiganados_maestro(); ?></h4>									
				</div>									
			</div>
		</div>

	</div>