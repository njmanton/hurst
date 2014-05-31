<section class="medium-3 columns sidebar">
	<h2><?php echo $selected['username']; ?></h2>
	<div>
		<h4>Standings</h4>
		<?php echo $this->element('small_table', array('uid' => $user['id'])); ?>
		<p><a href="/league">See all</a></p>
	</div>
	
	<div>
		<h4>Actions</h4>
		<ul>
			<li><a href="/users/invite">Invite a friend</a></li>
			<li><a href="/leagues/add">Create a new league</a></li>
			<li><a href="/users/options">Account options</a></li>
			<?php if ($user['admin'] == 1): ?>
			<li><a href="/users/payment">Manage Payments</a> <em>(Admin)</em></li>
			<?php endif; ?>
		</ul>
	</div>
	
	<div>
		<h4>My Leagues</h4>
		<?php if (empty($leagues)): ?>
			<p class="orphan-center">You have not joined any leagues yet</p>
		<?php else: ?>
			<ul>
				<?php foreach($leagues as $l): ?>
				<li>
					<a href="/leagues/<?php echo $l['L']['id']; ?>"><?php echo $l['L']['name']; ?></a>
					<?php if ($user['id'] == $l['L']['organiser']) { echo ' *'; } ?>
				</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	
</section>