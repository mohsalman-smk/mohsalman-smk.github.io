<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="<?= base_url('views/themes/academics/js/jquery-2.2.4.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/js/Chart.js'); ?>"></script>
<div class="col-xs-12 col-md-9">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?= strtoupper($title) ?></h3>
		</div>
		<div class="panel-body">
			<canvas id="buildChart"></canvas>
			<script>
				var ctx = document.getElementById("buildChart");
				var buildChart = new Chart(ctx, {
					type: 'bar',
					data: {
						labels: <?= $labels; ?>,
						datasets: [{
							label: '',
							data: <?= $data; ?>,
							borderWidth: 2,
							backgroundColor: 'rgba(75, 192, 192, 0.2)',
							borderColor: 'rgba(75, 192, 192, 1)'
						}]
					},
					options: {
						title: {
							display: true,
							text: '<?= $question; ?>'
						},
						responsive: true,
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero: true
								}
							}]
						}
					}
				});
			</script>
		</div>
	</div>
</div>
<?php $this->load->view('themes/academics/sidebar') ?>