<?php $this->set('title_for_layout', APP_NAME . ' | Goals by time scored'); ?>
<section class="row">
	<h2>Goals by time scored</h2>
	<p></p>
	<div id="hc">
		
	</div>
</section>

<script>
	$(function() {

		var goals = [];

		$.ajax({
			type: 'GET',
			url: '/matches/analytics/scorebytime',
			dataType: 'JSON',
			success: function(res) {
				$('#hc').highcharts({
					chart: {
          	type: 'scatter',
          	zoomType: 'x',
            plotBackgroundColor: 'rgba(128, 128, 255, 0.4)'
          },
          title: {
              text: null
          },
          legend: {
          	enabled: false
          },
          subtitle: {
          	text: document.ontouchstart === undefined ?
          		'Click and drag to zoom in' :
          		'Pinch the chart to zoom in'
          },
          tooltip: {
          	formatter: function() {
          		var str;
          		str = this.point.scorer + ' (' + this.point.x + '\'';
							if (this.point.tao) {
								str += '+' + this.point.tao + '\'';
							}
							if (this.point.type == 'O') {
								str += ' og';
          		} else if (this.point.type == 'P') {
								str += ' pen';
          		}
							str += ')<br>';
							str += '<strong>' + this.point.team + '</strong> v ' + this.point.oppo;
							str += '<br>Click to select match';
          		return str;
          	}
          },
          xAxis: {
            title: {
              enabled: true,
              text: 'Time (m)'
            },
            min: 0,
            startOnTick: true,
            tickInterval: 5,
            alternateGridColor: 'rgba(72, 72, 255, 0.4)'
          },
          yAxis: {
          	gridLineWidth: 0,
          	labels: {
          		enabled: false
          	},
          	title: {
          		enabled: false
          	},
            max: 2,
          },
          plotOptions: {
          	column: {
          		pointPadding: 0,
          		borderWidth: 0,
          		groupPadding: 0,
          		shadow: false,
          		enableMouseTracking: false,
          		animation: false
          	},
          	scatter: {
          		cursor: 'pointer',
          		point: {
          			events: {
          				click: function() {
          					window.location.href = '/matches/' + this.match;
          				}
          			}
          		}
          	}
          },
          series: [{
          	type: 'column',
          	name: null,
          	data: [{x: 92.5, y: 2, color: 'rgba(255, 128, 128, 0.8)'}, {x: 97.5, y: 2, color: 'rgba(255, 72, 72, .8)'}]
          }, {
          	type: 'scatter',
            name: 'Goals',
            color: 'rgba(223, 83, 83, .5)',
            data: res
          }]
      });

			}

		})

	})
</script>