<?php $this->set('title_for_layout', __('My Predictions')); ?>
<section>
	<h2>Edit Predictions</h2>

	<div class="sticky">
		<nav role="navigation" class="top-bar" data-topbar>
			<ul class="menu">
				<?php foreach ($preds as $k=>$p): ?>
				
				<li>
					<a href="#<?php echo ++$x; ?>"><?php echo str_replace('Group', '', $k); ?></a>
				</li>
								
				<?php endforeach; ?>
			</ul>
		</nav>
	</div>

	<?php echo $this->Form->create('pred'); ?>

	<?php foreach ($preds as $key=>$group): ?>
	<table class="fixtures">
		<caption id="<?php echo ++$y; ?>"><?php echo $key; ?></caption>
		<thead>
			<tr>
				<th>Date</th>
				<th>Venue</th>
				<th>&nbsp;</th>
				<th>Pred</th>
				<th>Result</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($group as $k=>$p): ?>
		<?php if ($p['exp'] && $p['joker'] == 1) $disable[$key] = true; ?>
		<?php $status = ($p['joker'] || ($k == 64)) ? 'checked' : ''; ?>
		<?php if ($p['exp'] || $disable[$key]) $status .= ' disabled'; ?>
			<tr>
				<td><?php echo $this->element('datefield', ['time' => $p['date'], 'tz' => $p['tz'], 'usertz' => $user['utc_offset']]); ?></td>
				<td><?php echo $p['venue']; ?></td>
				<td class="team"><?php echo $p['teama']; ?></td>
				<td class="score <?php if ($p['exp']) { echo 'expired'; } ?>">
					<?php if ($p['exp']): ?>
						<span class="result"><?php echo $p['prediction']; ?></span>
					<?php else: ?>
					<input type="text" data-pid="<?php echo $p['pid']; ?>" data-mid="<?php echo $k; ?>" class="<?php echo $class; ?>" value="<?php echo $p['prediction'] ?>" pattern="\d{1,2}-\d{1,2}" maxlength="5" placeholder="X-X" />
					<?php endif; ?>
					<?php if ($k != 63): ?>
						<input type="radio" name="data[Joker][<?php echo $key; ?>]" value="<?php echo $k; ?>" <?php echo $status; ?> />
					<?php endif; ?>
				</td>
				<td class="score"><?php echo ($p['result']) ?: '-'; ?></td>
				<td class="team"><?php echo $p['teamb']; ?></td>
				<td><a href="/matches/<?php echo $k; ?>">View</a></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php endforeach; ?>

<?php echo $this->Form->end(); ?>

</section>

