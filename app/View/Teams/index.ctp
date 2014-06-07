<?php $this->set('title_for_layout', __('All Teams | %s', APP_NAME)); ?>
<section>
	<h2>Teams</h2>

	<table class="teamindex">
		<thead>
			<tr>
				<th>Group</th>
				<th>Name</th>
				<th>Coach</th>
				<th><span data-tooltip class="his-tip tip-bottom radius" title="World Rankings as of 6th Jun">Ranking</span></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($teams as $t): ?>
			<tr>
				<?php if (($t['Team']['id'] % 4) == 1): ?>
					<td rowspan="4"><?php echo $t['Team']['group']; ?></td>
				<?php endif; ?>
				<td class=""><a href="/teams/<?php echo $t['Team']['id']; ?>"><?php echo $t['Team']['name']; ?></a></td>
				<td><?php echo $t['Team']['coach']; ?></td>
				<td><?php echo $t['Team']['ranking']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

</section>
