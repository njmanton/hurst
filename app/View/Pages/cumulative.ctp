<?php $this->set('title_for_layout', APP_NAME . ' | Cumulative goal total'); ?>
<section class="row">
	<p></p>
	<div id="hc_cumul">
		
	</div>

</section>
<script>
	$(function() {

		$.ajax({
			type: 'GET',
			url: '/matches/analytics/cumulative',
			success: function(res) {
				console.log (res);

				$('#hc_cumul').highcharts({
					chart: {
						type: 'line'
					},
					title: {
						text: 'Cumulative goals scored'
					},
					yAxis: {
						min: 0,
						title: {
							text: 'Goals'
						}
					},
					series: [{
						name: '2006',
						data: [6,8,9,9,12,13,17,18,22,25,27,30,30,31,35,39,40,43,45,46,52,55,55,57,59,61,61,63,65,67,71,75,78,81,83,87,90,92,97,97,99,102,107,111,112,113,115,117,119,122,123,124,125,125,128,132,134,137,137,138,140,141,145,147]
					}, {
						name: '2010',
						data: [2,2,4,5,7,8,9,13,15,16,18,20,20,23,24,25,28,33,36,38,39,43,43,44,46,49,51,53,57,64,65,67,68,71,75,77,78,79,80,83,88,88,92,95,95,98,101,101,104,107,112,116,119,122,122,123,126,128,132,133,138,139,144,145]
					}, {
						name: '2014',
						data: JSON.parse(res)
					}]
				})

			}
		})

	})
</script>