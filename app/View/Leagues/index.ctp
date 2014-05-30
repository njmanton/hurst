<?php $this->set('title_for_layout', __('List of User Leagues')); ?>
<section>
	<h2>User leagues</h2>
	<p></p>

	<?php if (!empty($leagues)): ?>
	<table class="league">
		<thead>
			<tr>
				<th>League</th>
				<th>Organiser</th>
				<th>Members</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($leagues as $l): ?>
			<tr>
				<td><a href="/leagues/<?php echo $l['L']['id']; ?>"><?php echo $l['L']['name']; ?> <?php if (!$l['L']['public']) { echo '*'; } ?></a></td>
				<td><a href="/users/<?php echo $l['U']['id']; ?>"><?php echo $l['U']['organiser']; ?></a></td>
				<td><?php echo $l[0]['members']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<p class="orphan-center">Leagues marked with a * are private.</p>
	<?php else: ?>
	<p class="orphan-center">No user leagues created yet</p>
	<?php endif; ?>


</section>

<?php if ($pending): ?>

<section class="row">
	<div class="medium-10 medium-centered columns">
		<h4>Pending user league applications</h4>

		<form action="/leagues/" method="post" id="manage">
			<table>
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Creator</th>
						<th>Accept</th>
						<th>Reject</th>
					</tr>
				</thead>
				<?php foreach ($pending as $p): ?>
				<tbody>
					<tr>
						<td><?php echo $p['League']['name']; ?></td>
						<td><?php echo $p['League']['description']; ?></td>
						<td><?php echo $p['User']['username']; ?></td>
						<td><input type="radio" name="data[<?php echo $p['League']['id']; ?>]" value="a" /></td>
						<td><input type="radio" name="data[<?php echo $p['League']['id']; ?>]" value="r" /></td>
					</tr>
				</tbody>
				<?php endforeach; ?>
			</table>
			<input class="tiny button" type="submit" value="Save" />
		</form>
	</div>
</section>

<?php endif; ?>
