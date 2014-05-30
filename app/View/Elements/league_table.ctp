<table class="standing">
	<caption><?php echo $caption; ?></caption>
	<thead>
		<tr>
			<th>Team</th>
			<th><abbr title="Games Played">P</abbr></th>
			<th><abbr title="Games Won">W</abbr></th>
			<th><abbr title="Games Drawn">D</abbr></th>
			<th><abbr title="Games Lost">L</abbr></th>
			<th><abbr title="Goals For">GF</abbr></th>
			<th><abbr title="Goals Against">GA</abbr></th>
			<th><abbr title="Goal Difference">GD</abbr></th>
			<th><abbr title="Points">PTS</abbr></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($table as $k=>$t): ?>
		<tr <?php if ($team['Team']['id'] == $k) echo 'class="hiliterow"'; ?>>
			<td><a href="/teams/<?php echo $k; ?>"><?php echo $t['name']; ?></a></td>
			<td><?php echo $t['P']; ?></td>
			<td><?php echo $t['W']; ?></td>
			<td><?php echo $t['D']; ?></td>
			<td><?php echo $t['L']; ?></td>
			<td><?php echo $t['GF']; ?></td>
			<td><?php echo $t['GA']; ?></td>
			<td><?php echo $t['GD']; ?></td>
			<td><?php echo $t['PTS']; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>