<div class="matchlists">
	<ul class="small-block-grid-4 large-block-grid-8">
	<?php $matches = $this->requestAction('/matches/head/'); ?>

	<?php foreach ($matches as $m): ?>
	<?php if (++$x % 6 == 1): ?>
	<li>
		<ul>
	<?php endif; ?>
			<li><a href="/matches/<?php echo $m['Match']['id']; ?>"><?php echo __('%s v %s %s', $m['TeamA']['tname'], $m['TeamB']['tname'], $m['Match']['result']); ?></a></li>

	<?php if (!($x % 6)): ?>
		</ul>
	</li>
	<?php endif; ?>
	<?php endforeach; ?>
	</ul>
</div>
