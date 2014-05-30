<section>
	<h2>Matches</h2>

	<div class="sticky">
	<nav role="navigation" class="top-bar" data-topbar>
		<ul>
			<li><a href="#0.1">A</a></li>
			<li><a href="#0.2">B</a></li>
			<li><a href="#0.3">C</a></li>
			<li><a href="#0.4">D</a></li>
			<li><a href="#0.5">E</a></li>
			<li><a href="#0.6">F</a></li>
			<li><a href="#0.7">G</a></li>
			<li><a href="#0.8">H</a></li>
			<li><a href="#2">L16</a></li>
			<li><a href="#3">QF</a></li>
			<li><a href="#4">SF</a></li>
			<li><a href="#5">3/4</a></li>
			<li><a href="#6">F</a></li>
		</ul>
	</nav>
</div>
	<?php foreach ($matches as $k=>$m): ?>
	<?php if ($m['order'] != $prevgroup): ?>
	<table class="fixtures">
		<caption id="<?php echo $m['order']; ?>"><?php echo $m['caption']; ?> <a href="#">(top)</a></caption>
		<thead>
			<tr>
				<th>Date</th>
				<th>Venue</th>
				<th>&nbsp;</th>
				<th>Result</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
	<?php endif; ?>
			<tr>
				<td><?php echo $this->element('datefield', ['time' => $m['date'], 'tz' => $m['tz'], 'usertz' => $user['utc_offset']]); ?></td>
				<td><?php echo $m['venue']; ?></td>
				<td class="team"><?php echo $m['teama']; ?></td>
				<td class="score"><?php echo $m['result']; ?></td>
				<td class="team"><?php echo $m['teamb']; ?></td>
				<td><a href="/matches/<?php echo $k; ?>">View</a></td>
			</tr>
		</tbody>
	<?php $prevgroup = $m['order']; ?>
	<?php endforeach; ?>

	</table>

</section>
