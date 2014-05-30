<?php $table = $this->requestAction('/users/'); ?>
<section class="smalltable">
	<table class="league">
		<thead>
			<tr>
				<th>Pos</th>
				<th>Name</th>
				<th>Pts</th>
			</tr>
		</thead>
		<tbody>
		<?php if (!is_null($table)): ?>
			<?php foreach ($table as $k=>$t): ?>
			<?php if ($t['show'] || ($t['id'] == $uid)): ?>
				<tr class="<?php if ($t['id'] == $selected['id']) { echo 'hiliterow'; }?> <?php if ($t['class']) { echo $t['class']; } elseif ($t['id'] == $uid && !$t['show']) { echo 'player'; } ?>">
					<td><?php echo $t['rank']; ?></td>
					<td><a href="/users/<?php echo $t['id']; ?>"><?php echo $k; ?></a></td>
					<td><?php echo ($t['PTS']) ?: 0; ?></td>
				</tr>
			<?php endif; ?>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="3">No standings data yet</td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>
</section>

