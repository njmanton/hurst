<?php $this->set('title_for_layout', 'Set KO'); ?>
<section class="row">
	<h2>Set L16 teams</h2>
		<form action="#" id="SetKO" method="post">
			
			<label for="gpA">Group <?php echo $gp; ?> winner
				<select name="data[winner]" id="">
					<option value=""></option>
					<?php foreach ($teams as $k=>$t): ?>
						<option value="<?php echo $k; ?>"><?php echo $t; ?></option>
					<?php endforeach; ?>
				</select>
			</label>
			<label for="gpA">Group <?php echo $gp; ?> runner-up
				<select name="data[ru]" id="">
					<option value=""></option>
					<?php foreach ($teams as $k=>$t): ?>
						<option value="<?php echo $k; ?>"><?php echo $t; ?></option>
					<?php endforeach; ?>
				</select>
			</label>

			<input type="submit" value="Submit" />
		</form>
	
</section>