<section>
	<h2>Goals</h2>
	<p></p>

	<?php if (!empty($goals)): ?>
	<table class="league">
		<thead>
			<tr>
				<th>Player</th>
				<th>Team</th>
				<th>Opponent</th>
				<th>Time</th>
				<th>Match</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($goals as $g): ?>
				<?php if ($g['type'] != 'O'): ?>
				<?php $tot++; ?>
				<tr>
					<td><?php echo $g['scorer']; ?></td>
					<td><a href="/teams/<?php echo $g['tid']; ?>"><?php echo $g['team']; ?></a></td>
					<td><a href="/teams/<?php echo $g['oid']; ?>"><?php echo $g['oppo']; ?></a></td>
					<td><?php	echo $g['time']; ?></td>
					<td><a href="/matches/<?php echo $g['mid']; ?>">view</a></td>
				</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>Own Goals:</td>
				<td><?php echo count($goals) - $tot; ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>Total:</td>
				<td><?php echo count($goals); ?></td>
			</tr>
		</tfoot>
	</table>
	<?php else: ?>
	<p class="orphan-center">No goals scored yet!</p>
	<?php endif; ?>
</section>
