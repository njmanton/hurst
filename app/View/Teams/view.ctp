<?php $this->set('title_for_layout', __('%s | %s', $team['Team']['name'], APP_NAME)); ?>
<section class="row full" data-equalizer>

	<div class="medium-9 medium-push-3 columns" data-equalizer-watch>
		<?php foreach ($fixtures as $k=>$match): ?>
		<table class="fixtures">
			<caption><?php echo $k; ?></caption>
			<thead>
				<tr>
					<th>Date</th>
					<th>Venue</th>
					<th>Opponent</th>
					<th>Result</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($match as $k=>$m): ?>
				<tr>
					<td><?php echo $this->element('datefield', ['time' => $m['date'], 'tz' => $m['tz'], 'usertz' => $user['utc_offset']]); ?></td>
					<td><a href="/venues/<?php echo $m['venue_id']; ?>"><?php echo $m['venue']; ?></a></td>
					<td class="team">
						<?php if (is_numeric($m['oppo_id'])): ?>
						<a href="/teams/<?php echo $m['oppo_id']; ?>"><?php echo $m['oppo']; ?></a>
						<?php else: ?>
						<?php echo $m['oppo_id']; ?>
						<?php endif; ?>
					</td>
					<td class="score"><?php echo $m['result']; ?></td>
					<td><a href="/matches/<?php echo $k; ?>">view</a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php endforeach; ?>

		<?php echo $this->element('league_table', array('table' => $table, 'caption' => 'Table')); ?>

	</div>
	
	<div class="medium-3 medium-pull-9 columns sidebar" data-equalizer-watch>
		<?php $ext = (file_exists(__('img/Badges/%s.svg', $team['Team']['sname']))) ? 'svg' : 'png'; ?>
		<img class="badge" src="/img/Badges/<?php echo __('%s.%s', $team['Team']['sname'], $ext); ?>" alt="Team badge">

		<?php echo $this->element('team_history', array('history' => $history)); ?>
	</div>

</section>
