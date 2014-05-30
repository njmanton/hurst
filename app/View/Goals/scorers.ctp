<section>
	<h2>Top Scorers</h2>
	<p></p>
	
	<?php if (!empty($scorers)): ?>
	<table class="league">
		<thead>
			<tr>
				<th>Player</th>
				<th>Team</th>
				<th>Goals (Pens)</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($scorers as $s): ?>
			<tr>
				<td><?php echo $s['Goal']['scorer']; ?></td>
				<td><a href="/teams/<?php echo $s['Team']['id']; ?>"><?php echo $s['Team']['name']; ?></a></td>
				<td><?php echo __('%s (%s)', $s[0]['goals'], $s[0]['pens']); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	<p class="orphan-center">No goals scored yet!</p>
	<?php endif; ?>

</section>