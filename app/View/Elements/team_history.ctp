<section class="smalltable">

	<?php if (!empty($history)): ?>
	<table class="league">
		<caption><?php echo (count($history) == 1) ? '1 Appearance' : __('%s Appearances', count($history)); ?></caption>
		<thead>
			<tr>
				<th>Year</th>
				<th>Place</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($history as $h): ?>
			<tr>
				<td><?php echo $h['Tournament']['year']; ?></td>
				<td><?php echo $h['History']['result']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	<p>First World Cup Appearance</p>
	<?php endif; ?>

</section>
