<section class="row">
	<h2>Correctly predicted qualifiers</h2>
	<p></p>
	<div id="hc_pbm"></div>
</section>

<script>
	$(function() {

		$.ajax({
			type: 'GET',
			url: '/users/qualifiers',
			success: function(res) {
				console.log(res);
				data = JSON.parse(res);
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
						categories: data.labels,
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
						name: 'correct qualifiers',
						data: data.values,
						pointWidth: 15,
						//pointPadding: 0.3,
					}]
				});

			}
		})

	})
</script>