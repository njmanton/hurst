<?php $this->set('title_for_layout', 'Goalmine 2014 League'); ?>
<section class="row">
	<h2>Overall League Table</h2>
	
	<table class="league">
		<thead>
			<tr>
				<th>Rank</th>
				<th>Player</th>
				<th><abbr title="Correct Scores">CS</abbr></th>
				<th><abbr title="Correct Differences">CD</abbr></th>
				<th><abbr title="Correct Results">CR</abbr></th>
				<th><abbr title="Goals away from correct total">&Delta;</abbr></th>
				<th>Points</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($standings as $k=>$s): ?>
			<?php if ($s['paid'] == 1): ?>
			<tr class="<?php if ($k == $user['username']) { echo ' hiliterow'; } ?>">
				<td><?php echo $s['rank']; ?></td>
				<td><a href="/users/<?php echo $s['id']; ?>"><?php echo $k; ?></a></td>
				<td><?php echo ($s['CS']) ?: 0; ?></td>
				<td><?php echo ($s['CD']) ?: 0; ?></td>
				<td><?php echo ($s['CR']) ?: 0; ?></td>
				<td><?php echo ($s['delta']) ?: 0; ?></td>
				<td><?php echo ($s['PTS']) ?: 0; ?></td>
			</tr>
			<?php endif; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
	<p></p>
	
	<p class="orphan-center">See all <a href="/leagues/">user leagues</a></p>

</section>
