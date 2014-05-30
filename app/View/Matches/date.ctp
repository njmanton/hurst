<section>
	<h2></h2>
	<p></p>
	
	<?php foreach ($matches as $k=>$days): ?>
		<table class="fixtures">
			<caption><?php echo $k; ?></caption>
			<thead>
				<tr>
					<th>Stage</th>
					<th>Time</th>
					<th>&nbsp;</th>
					<th>Result</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($days as $k=>$m): ?>
				<tr>
					<td><?php echo $m['caption']; ?></td>
					<td><?php echo $this->element('datefield', ['time' => $m['date'], 'tz' => $m['tz'], 'usertz' => $user['utc_offset'], 'timeonly' => true]); ?></td>
					<td class="team"><?php echo $m['teama']; ?></td>
					<td class="score"><?php echo $m['result']; ?></td>
					<td class="team"><?php echo $m['teamb']; ?></td>
					<td><a href="/matches/<?php echo $k; ?>">View</a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endforeach; ?>
	
</section>