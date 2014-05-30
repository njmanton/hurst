<section class="row full">

	<div class="medium-3 columns sidebar">

		<h2><?php echo $league['League']['name']; ?></h2>
		<h4>Organiser: <?php echo $league['User']['username']; ?></h4>
		<p><?php echo $league['League']['description']; ?></p>

		<p>This league is <?php echo ($league['League']['public']) ? 'public' : 'private' ; ?>.
		<?php if (!$member): ?>
		<?php if ($league['League']['public'] == 0) { echo 'Request to '; } ?>
		<a href="/leagues/join/<?php echo $league['League']['id']; ?>">Join</a> this league.
		<?php endif; ?>
		</p>

		<?php if ($pending): ?>
			<h4>Applications to join league</h4>
			<form action="/leagues/<?php echo $league['League']['id']; ?>" method="post" id="LeaguePending">
				<table>
					<thead>
						<tr>
							<th>Name</th>
							<th>Accept</th>
							<th>Reject</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($pending as $p): ?>
						<tr>
							<td>
								<a href="/user/<?php echo $p['User']['id']; ?>"><?php echo $p['User']['username']; ?></a>
								<input type="hidden" name="data[<?php echo $p['User']['id']; ?>][pid]" value="<?php echo $p['LeagueUser']['id']; ?>" />
							</td>
							<td><input type="radio" name="data[<?php echo $p['User']['id']; ?>][decision]" value="a" /></td>
							<td><input type="radio" name="data[<?php echo $p['User']['id']; ?>][decision]" value="r" /></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<input type="submit" class="tiny button" value="Confirm" />
			</form>
		<?php endif; ?>

	</div>

	<div class="medium-9 columns">

		<table class="league">
			<thead>
				<tr>
					<th></th>
					<th>Player</th>
					<th><abbr title="Correct Scores">CS</abbr></th>
					<th><abbr title="Correct Differences">CD</abbr></th>
					<th><abbr title="Correct Results">CR</abbr></th>
					<th>Points</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($table as $k=>$s): ?>
				<tr class="<?php if ($k == $user['username']) { echo ' hiliterow'; } ?>">
					<td><?php echo $s['rank']; ?></td>
					<td><a href="/users/<?php echo $s['id']; ?>"><?php echo $k; ?></a></td>
					<td><?php echo ($s['CS']) ?: 0; ?></td>
					<td><?php echo ($s['CD']) ?: 0; ?></td>
					<td><?php echo ($s['CR']) ?: 0; ?></td>
					<td><?php echo ($s['PTS']) ?: 0; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	</div>

</section>


