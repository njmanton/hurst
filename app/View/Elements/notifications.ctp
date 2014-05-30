<section id="notifications" class="">

	<?php $r = $this->requestAction('/matches/remaining/' . $user['id']); ?>
	<?php $pending = $this->requestAction('/leagues/pending'); ?>

	<?php if ($user['paid'] == 0): ?>
	<div data-alert class="alert-box radius warning">
		You have not yet paid your entry to Goalmine!
		<a href="#" class="close">&times;</a>
	</div>
	<?php endif; ?>

	<?php if ($r > 0): ?>
	<div data-alert class="alert-box radius warning">
		You have <?php echo $r; ?> <a href="/predictions/">prediction<?php echo ($r == 1) ? '' : 's' ; ?></a> still to make
		<a href="#" class="close">&times;</a>
	</div>
	<?php endif; ?>
	
	<?php if (!empty($pending['leagues'])): ?>
		<?php foreach ($pending['leagues'] as $l): ?>
			<div data-alert class="alert-box radius info">
				[ADMIN] User league <strong><?php echo $l['League']['name']; ?></strong> is awaiting <a href="/leagues/">confirmation</a>.
				<a href="#" class="close">&times;</a>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php foreach ($pending['users'] as $p): ?>
		<div data-alert class="alert-box radius info">
			<?php $plural = ($p[0]['cnt'] == 1) ? '' : 's' ; ?>
			<?php echo __('You have %s request%s pending for league <a href="/leagues/%s"><strong>%s</strong></a>', $p[0]['cnt'], $plural, $p['L']['id'], $p['L']['name']); ?>
			<a href="#" class="close">&times;</a>
		</div>
	<?php endforeach; ?>

</section>