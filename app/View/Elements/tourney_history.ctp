<?php
	// get a random World Cup result
	$tourney = $this->requestAction('/tournaments/rnd');
	// expand into list of golden shoe winners
	$gs = explode(',', $tourney['golden_shoe']);
?>
<section>
	<h4>Previous Tournaments - <?php echo $tourney['year']; ?></h4>
	<img src="/img/posters/<?php echo $tourney['year'] ?>s.png" alt="Tournament Poster">
	<div>
		<p>
			<strong>Hosts: </strong>
			<?php echo $tourney['host']; ?>
		</p>
		<p>
			<strong>Champions: </strong>
			<?php echo __('<a href="/teams/%s">%s</a>', $tourney['champion_id'], $tourney['champions']); ?>
		</p>
		<p>
			<strong>Golden Shoe [<?php echo $tourney['gs_count']; ?>]</strong><br>
			<?php foreach ($gs as $g): ?>
				<?php echo $g; ?><br>
			<?php endforeach; ?>
		</p>
	</div>
	
</section>