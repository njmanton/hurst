<?php $this->set('title_for_layout', __('%s | %s', $selected['username'], APP_NAME)); ?>
<section class="row full clearfix">

	<?php if ($self) { echo $this->element('notifications'); } ?>
	
	<?php 
		$side = false;
		if ($self) {
			echo $this->element('self', ['leagues' => $leagues]);
			$side = true;
		} else {
			echo __('<h2>%s</h2>', $selected['username']);
		}
	?>

	<section class="<?php echo ($side) ? 'medium-9' : ''; ?> columns" id="userpreds">

		<div class="sticky">
			<nav role="navigation" data-topbar class="top-bar">
				<ul class="menu">
					<?php foreach ($preds as $k=>$g): ?>
					<li>
						<a href="#<?php echo ++$x; ?>"><?php echo str_replace('Group', '', $k); ?></a>
					</li>
					<?php endforeach; ?>
					<li><a href="#tables">Tables</a></li>
				</ul>
			</nav>
		</div>

		<?php foreach ($preds as $k=>$group): ?>
		<table class="predictions">
			<caption id="<?php echo ++$y; ?>"><?php echo $k; ?> <a href="#">(top)</a></caption>
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th>Score</th>
					<th>Pred</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($group as $k=>$m): ?>
				<tr>
					<td><?php echo $this->element('datefield', ['time' => $m['date'], 'tz' => $m['tz'], 'usertz' => $user['utc_offset']]); ?></td>
					<td class="team"><?php echo $m['teama']; ?></td>
					<td class="score"><?php echo $m['result']; ?></td>
					<td class="<?php echo 'score pts' . $m['pts']; echo ($m['joker'] == 1) ? ' joker' : ''; ?>"><?php echo $m['pred']; ?></td>
					<td class="team"><?php echo $m['teamb']; ?></td>
					<td><a href="/matches/<?php echo $k; ?>">View</a></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php endforeach; ?>

		<h4 id="tables">Predicted Tables</h4>
		<p>Tables are based on the predictions above, and results (when known)</p>
		<?php
			foreach ($predleagues as $k=>$t) {
				echo $this->element('league_table', array('table' => $t, 'caption' => __('Group %s', $k)));
			}
		?>

</section>

