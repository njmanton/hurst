<section class="row">
	<h2></h2>
	<div id="hc_bubble">

	</p>

</section>

<script>
	$(function() {

		var ajaxData = [], point;
		$.ajax({
			type: 'GET',
			url: '/predictions/bubble',
			success: function(res) {
				data = JSON.parse(res);
				$('#hc_bubble').highcharts({
					chart: {
						type: 'bubble'
					},
					xAxis: {			
						title: {
							text: 'Home'
						},
						max: 7,
						min: -0.5,
						tickInterval: 1,
						gridLineWidth: 1
					},
					yAxis: {
						title: {
							text: 'Away'
						},
						max: 7,
						min: -0.5,
						tickInterval: 1
					},
					title: {
						text: 'Plot of predicted scores vs results'
					},
					series: [{
						name: 'Predictions',
						data: data.Pred
					}, {
						name: 'Results',
						data: data.Result
					}]
				})

			}
		})

	})
</script>