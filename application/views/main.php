<script src="<?=base_url()?>assets/js/chartDraw.js"></script>

<div class="chartDrawContainer" data-base-url=<?=base_url()?>>
	<div class="chartDrawTemplate row">
		<div class="col-xs-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					{chartTitle}
				</div>
				<div class="panel-body">
					<canvas class="chartDrawChart {chartName}" height="400"></canvas>
				</div>
			</div>
		</div>
	</div>
	<div class="chartDrawGraphsContainer">
	</div>
</div>
