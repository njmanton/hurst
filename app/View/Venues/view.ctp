<?php $this->set('title_for_layout', __('%s | %s', $venue['Venue']['stadium'], APP_NAME)); ?>
<section class="rows clearfix">
	
	<div class="medium-3 columns sidebar">
		
		<h2><?php echo $venue['Venue']['city']; ?></h2>
		<img style="width: 100%;" src="/img/stadia/arena<?php echo $venue['Venue']['id']; ?>.jpg" alt="Stadium picture">
		<h3><?php echo $venue['Venue']['stadium']; ?></h3>
		<h4>Capacity: <?php echo number_format($venue['Venue']['capacity']); ?></h4>
		
	</div>
	
	<div class="medium-9 columns">
		
		<table class="fixtures">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th>Score</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($venue['Match'] as $k=>$v): ?>
				<tr>
					<td><?php echo $this->element('datefield', ['time' => $v['date'], 'tz' => $v['tz'], 'usertz' => $user['utc_offset']]); ?></td>
					<td><?php echo $v['caption']; ?></td>
					<td class="team"><?php echo $v['teama']; ?></td>
					<td class="score"><?php echo str_replace('-', ' &#8208; ', $v['result']); ?></td>
					<td class="team"><?php echo $v['teamb']; ?></td>
					<td><a href="/matches/<?php echo $k; ?>">view</a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
	</div>
	
</section>
