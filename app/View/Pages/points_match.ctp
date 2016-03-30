<section class="row">
	<h2>Points scored per game</h2>
	<p></p>
	<div id="hc_pbm"></div>
</section>

<script>
	$(function() {

		$.ajax({
			type: 'GET',
			url: '/matches/analytics/pointsbymatch',
			success: function(res) {
				res = JSON.parse(res);
				$('#hc_pbm').highcharts({
					colors: ['#00db74', '#280661'],
					chart: {
						type: 'bar',
						height: 1200
					},
					title: {
						text: null
					},
					xAxis: {
						categories: res.labels,
						step: 1
					},
					yAxis: {
						title: {
							text: 'points'
						}
					},
					plotOptions: {
						bar: {
							animation: false,
							grouping: false,
							dataLabels: {
								enabled: true
							}
						}
					},
					series: [{
						name: 'points',
						data: res.points,
						pointWidth: 15,
						//pointPadding: 0.3,
						point: {
							events: {
								click: function() {
									window.location.href = '/matches/' + this.id;
								}
							}
						}
					}, {
						name: 'jokers',
						data: res.jokers,
						pointWidth: 6,
						borderWidth: 0
					}]
				});

			}
		})

	})
</script>