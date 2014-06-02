<?php $this->set('title_for_layout', __('%s v %s | Match %s', $match['teama'], $match['teamb'], $match['mid'])); ?>
<?php $hw = $aw = $d = 0; ?>
<?php list($hs, $as) = explode('-', $match['result']); ?>
<section class="row">
	<div class="medium-10 medium-centered columns">
		<table class="matchresult">
			<caption>
				<?php echo $this->element('datefield', ['time' => $match['date'], 'tz' => $match['tz'], 'usertz' => $user['utc_offset']]); ?> at <?php echo $match['venue']; ?>
				<?php if (($user['admin'] == 1) && $match['editable']): ?>
				 (<a href="/results/<?php echo $match['mid']; ?>">Edit Result</a>)
				<?php endif; ?>
			</caption>
			<tbody>
				<tr>
					<td class="teams">
						<?php echo (is_numeric($match['teama_id'])) ? __('<a href="/teams/%s">%s</a>', $match['teama_id'], $match['teama']) : $match['teama_id']; ?>
					</td>
					<td class="score">
						<?php if ($match['winner'] == $match['aid']): ?>
						<span class="winm"><?php echo $match['winmethod']; ?></span>
						<?php endif; ?>
						<span><?php echo $hs; ?></span>
					</td>
				</tr>
				<tr class="scorers">
					<td><?php echo $match['Goals'][$match['aid']]; ?></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="teams">
						<?php echo (is_numeric($match['teamb_id'])) ? __('<a href="/teams/%s">%s</a>', $match['teamb_id'], $match['teamb']) : $match['teamb_id']; ?>
					</td>
					<td class="score">
						<?php if ($match['winner'] == $match['bid']): ?>
						<span class="winm"><?php echo $match['winmethod']; ?></span>
						<?php endif; ?>
						<span><?php echo $as; ?></span>
					</td>
				</tr>
				<tr class="scorers">
					<td><?php echo $match['Goals'][$match['bid']]; ?></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

		<section>
			<?php if (!empty($preds)): ?>
			<div class="small-6 columns">
				<table class="league pgrid">
					<caption>Predictions</caption>
					<tbody>
						<?php foreach ($preds as $p): ?>
						<tr>
							<td><a href="/users/<?php echo $p['uid'] ?>"><?php echo $p['user']; ?></a></td>
							<td class="<?php echo 'pts' . $p['points']; if ($p['joker']) echo ' joker'; ?>"><?php echo $p['prediction']; ?></td>
						</tr>
					<?php list($a, $b) = explode('-', $p['prediction']); if ($a > $b) { $hw++; } elseif ($a < $b) { $aw++; } else { $d++; } ?>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="small-6 columns" id="hchart">

			</div>
			<?php endif; ?>
		</section>

	</div>

</section>

<script>

$(function() {

	$('#hchart').highcharts({
		colors: [
			'#00a859',
			'#ffcc29',
			'#3e4095'
		],
		chart: {
			backgroundColor: '#4c4a59'
		},
		title: {
			text: null
		},
		legend: {
			layout: 'vertical',
			backgroundColor: '#eee',
			borderRadius: 0
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				borderColor: '#4c4a59',
				cursor: 'pointer',
				dataLabels: {
					enabled: false
				},
				showInLegend: true
			}
		},
		series: [{
			type: 'pie',
			name: 'Predictions',
			data: [
				[<?php echo __('\'%s Win\', %s', $match['teama'], $hw); ?>],
				[<?php echo __('\'%s Win\', %s', $match['teamb'], $aw); ?>],
				[<?php echo __('\'Draw\', %s', $d); ?>]
			]
		}]

	});

})

</script>

