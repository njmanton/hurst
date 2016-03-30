<?php $this->set('title_for_layout', APP_NAME . ' | Points per confederation'); ?>
<section class="row">
	<p></p>
	<div id="hc_confed">
		
	</div>
</section>
<script>
	$(function() {

		$.ajax({
			type: 'GET',
			url: '/matches/analytics/confed',
			success: function(res) {
				var data = JSON.parse(res);
				$('#hc_confed').highcharts({

					colors: ['#00db74'],
					chart: {

						type: 'column',
						backgroundColor: '#4c4a59'
					},
					title: {
						text: 'Points per game by confederation',
						style: {
							color: '#eee'
						}
					},
					xAxis: {
						categories: data.labels,
						labels: {
							style: {
								color: '#eee'
							}
						}
					},
					yAxis: {
						title: {
							text: 'points per game',
							style: {
								color: '#eee'
							}
						},
						labels: {
							formatter: function() {
								return Highcharts.numberFormat(this.value, 1);
							},
							style: {
								color: '#eee'
							}
						}
					},
					legend: {
						enabled: false
					},
					series: [{
						name: 'points per game',
						data: data.pts,
						borderWidth: 0
					}]

				});

			}
		});

	});
</script>