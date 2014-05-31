<?php $r = $this->requestAction('/matches/remaining/' . $user['id']); ?>

<nav id="mainnav" role="navigation">
	<?php if ($user): ?>
	<ul class="menu">
		<li>
			<a href="/users/<?php echo $user['id']; ?>"><?php echo strtoupper($user['username']); ?> 
				<span title="Predictions left to make" class="alert label <?php if ($r == 0) echo 'success'; ?>"><?php echo $r; ?></span>
			</a>
		</li>
		<li><a href="/predictions/">PREDICTIONS</a></li>
		<li><a href="/league/">LEAGUE</a></li>
		<li><a id="showml" href="#">MATCHES</a></li>
		<li><a id="showtl" href="#">TEAMS</a></li>
		<li><a href="/users/logout">LOGOUT</a></li>
	</ul>
	<?php else: ?>
	<ul class="menu">
		<li>
			<a href="/users/login">LOGIN</a>
		</li>
	</ul>
	<?php endif; ?>
	
	<?php echo $this->element('matchlists'); ?>
	
	<div id="teamlists">

		<ul class="small-block-grid-4 large-block-grid-8">
		<?php $x = 0; $teams = $this->requestAction('/teams/head'); ?>

		<?php foreach ($teams as $t): ?>
		<?php if (++$x % 4 == 1): ?>
		<li>
			<ul>
		<?php endif; ?>
			<li class="flag <?php echo $t['Team']['sname']; ?>">
				<a href="/teams/<?php echo $t['Team']['id'] ?>"><?php echo $t['Team']['name']; ?></a>
			</li>
		<?php if (!($x % 4)): ?>
			</ul>
		</li>
		<?php endif; ?>
		<?php endforeach; ?>
		</ul>

	</div>

</nav>
