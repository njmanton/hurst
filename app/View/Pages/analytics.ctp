<section class="row">
	<h2>Analytics</h2>
	<p>This page shows visualisations of a number of different World Cup and Goalmine statistics. Click on an icon to see the chart and data.</p>
	<ul class="small-block-grid-6" id="chartlist">
		<li>
			<a href="/content/goal_time">
				<img src="/img/bar-chart.svg" alt="Chart Icon">
				<p>Chart of each goal, by time scored</p>
			</a>
		</li>
		<li>
			<a href="/content/confed">
				<img src="/img/bar-chart.svg" alt="Chart Icon">
				<p>Number of points per game, for each confederation</p>
			</a>
		</li>
		<li>
			<a href="/content/points_match">
				<img src="/img/bar-chart.svg" alt="Chart Icon">
				<p>Chart showing the points scored for each game</p>
			</a>
		</li>
		<li>
			<a href="/content/bubble">
				<img src="/img/bar-chart.svg" alt="Chart Icon">
				<p>Bubble chart showing predicted goals vs actual results</p>
			</a>
		</li>
		<li>
			<a href="/content/cumulative">
				<img src="/img/bar-chart.svg" alt="Chart Icon">
				<p>Cumulative goals (2006 v 2010 v 2014)</p>
			</a>
		</li>
		<li>
			<a href="/content/qualifiers">
				<img src="/img/bar-chart.svg" alt="Chart Icon">
				<p>Number of correctly predicted qualifying teams</p>
			</a>
		</li>
	</ul>
</section>
<script>
	$(function() {
		if (!Modernizr.svg) { // fallback to png if no svg support
			$('#chartlist img').attr('src', 'img/bar-chart.png');
		}
	})
</script>