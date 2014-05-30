<?php $this->set('title_for_layout', 'Manage payments'); ?>
<section id="payments">
	<h2>Manage payments</h2>

	<table class="league">
		<thead>
			<tr>
				<th>Player</th>
				<th>Paid</th>
			</tr>
		</thead>
		<?php foreach ($players as $p): ?>
		<tbody>
			<tr>
				<td><?php echo $p['User']['username']; ?></td>
				<td>
					<?php if ($p['User']['paid']): ?>
						&#x2714;
					<?php else: ?>
						<span>
							<button class="tiny button" data-uid="<?php echo $p['User']['id']; ?>">Mark as paid</button>
						</span>
					<?php endif; ?>
				</td>
			</tr>
		</tbody>
		<?php endforeach; ?>
	</table>

</section>

