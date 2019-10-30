	<div class="row">
		<div class="col s12 m3">
			<div class="card">
				<div class="card-content text-center">
					<i  class="material-icons icon-stat">alarm</i>
					<p class="card-stats-title">Impuntualidad Institución</p>
					<hr>
					<h4 class="card-stats-number"><?php echo stat_llegadas_tarde(); ?></h4>									
				</div>									
			</div>
		</div>
		<div class="col s12 m3">
			<div class="card">
				<div class="card-content text-center">
					<i  class="material-icons icon-stat">watch_later</i>
					<p class="card-stats-title">Impuntualidad Salón</p>
					<hr>
					<h4 class="card-stats-number"><?php echo stat_llegadas_tarde_aclase(); ?></h4>									
				</div>									
			</div>
		</div>
		<div class="col s12 m3">
			<div class="card">
				<div class="card-content text-center">
					<i  class="material-icons icon-stat">alarm_off</i>
					<p class="card-stats-title">Inasistencias</p>
					<hr>
					<h4 class="card-stats-number"><?php echo stat_inasistencias(); ?></h4>									
				</div>									
			</div>
		</div>
		<div class="col s12 m3">
			<div class="card">
				<div class="card-content text-center">
					<i  class="material-icons icon-stat">gavel</i>
					<p class="card-stats-title">Códigos Asignados</p>
					<hr>
					<h4 class="card-stats-number"><?php echo stat_codigos(); ?></h4>									
				</div>									
			</div>
		</div>


	</div>
	<div class="row">
	<div class="col s12 m12 l6">
			<div class="card">
				<div class="card-content text-center">
					<div id="chart5"></div>					
				</div>									
			</div>
		</div>
		<div class="col s12 m12 l6">
			<div class="card">
				<div class="card-content text-center">
					<div id="chart6"></div>					
				</div>									
			</div>
		</div>
		<div class="col s12 m12 l6">
			<div class="card">
				<div class="card-content text-center">
					<div id="chart1"></div>					
				</div>									
			</div>
		</div>
		<div class="col s12 m12 l6">
			<div class="card">
				<div class="card-content text-center">
					<div id="chart2"></div>					
				</div>									
			</div>
		</div>
		
		<div class="col s12">
			<div class="card">
				<div class="card-content text-center">
					<div id="chart3"></div>					
				</div>									
			</div>
		</div>
		<div class="col s12">
			<div class="card">
				<div class="card-content text-center">
					<div id="chart4"></div>					
				</div>									
			</div>
		</div>
	</div>