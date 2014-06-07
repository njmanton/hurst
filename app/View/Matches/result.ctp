<?php $x = 0; $y = 100; ?>
<?php $this->set('title_for_layout', __('%s v %s | Match %s', $match['TeamA']['name'], $match['TeamB']['name'], $match['Match']['id'])); ?>
<form action="#" method="post" id="EditGoals">
	<section class="row">
		<div class="medium-10 medium-centered columns">
			<table class="matchresult">
				<tbody>
					<tr>
						<td class="teams"><a href=""><?php echo $match['TeamA']['name']; ?></a></td>
						<td class="score">
							<input type="text" data-mid="<?php echo $match['Match']['id']; ?>" value="<?php echo $match['Match']['result']; ?>" id="result" name="data[Match][result]">
							<input type="hidden" value="<?php echo $match['Match']['id']; ?>" name="data[Match][id]">
							<input type="hidden" value="<?php echo $match['TeamA']['id']; ?>" name="data[Match][teama]">
							<input type="hidden" value="<?php echo $match['TeamB']['id']; ?>" name="data[Match][teamb]">	
						</td>
					</tr>
					<tr>
						<td>
							<div id="teama" data-tid="<?php echo $match['TeamA']['id']; ?>">
								<?php foreach ($match['Goal'] as $hg): ?>
								<?php if ($hg['team_id'] == $match['TeamA']['id']): ?>
								<div class="scoreLine">
									<input type="text" required name="data[Goal][<?php echo $x; ?>][scorer]" value="<?php echo $hg['scorer']; ?>">
									<input type="number" class="time normal" name="data[Goal][<?php echo $x; ?>][time]" value="<?php echo $hg['time']; ?>">+
									<input type="number" class="time" disabled name="data[Goal][<?php echo $x; ?>][tao]" value="<?php echo $hg['tao']; ?>">
									<input type="hidden" name="data[Goal][<?php echo $x; ?>][team_id]" value="<?php echo $match['TeamA']['id']; ?>">
									<select name="data[Goal][<?php echo $x++; ?>][type]">
										<option value=""></option>
										<option <?php if ($hg['type'] == 'P') echo 'selected'; ?> value="P">Pen</option>
										<option <?php if ($hg['type'] == 'O') echo 'selected'; ?> value="O">OG</option>
									</select>
								</div>
								<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</td>
					</tr>
					<tr>
						<td class="teams"><a href=""><?php echo $match['TeamB']['name']; ?></a></td>
					</tr>
					<tr>
						<td>
							<div id="teamb" data-tid="<?php echo $match['TeamB']['id']; ?>">
								<?php foreach ($match['Goal'] as $hg): ?>
								<?php if ($hg['team_id'] == $match['TeamB']['id']): ?>
								<div class="scoreLine">
									<input type="text" required name="data[Goal][<?php echo $y; ?>][scorer]" value="<?php echo $hg['scorer']; ?>">
									<input type="number" class="time normal" name="data[Goal][<?php echo $y; ?>][time]" value="<?php echo $hg['time']; ?>">+
									<input type="number" class="time" disabled name="data[Goal][<?php echo $y; ?>][tao]" value="<?php echo $hg['tao']; ?>">
									<input type="hidden" name="data[Goal][<?php echo $y; ?>][team_id]" value="<?php echo $match['TeamB']['id']; ?>">
									<select name="data[Goal][<?php echo $y++; ?>][type]">
										<option value=""></option>
										<option <?php if ($hg['type'] == 'P') echo 'selected'; ?> value="P">Pen</option>
										<option <?php if ($hg['type'] == 'O') echo 'selected'; ?> value="O">OG</option>
									</select>
								</div>
								<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			
			<div id="resolution">
				<label for="shootout">Which team won the penalty shootout?
					<select name="data[Match][shootout]" id="shootout">
						<option value=""></option>
						<option value="<?php echo $match['TeamA']['id']; ?>"><?php echo $match['TeamA']['name']; ?></option>
						<option value="<?php echo $match['TeamB']['id']; ?>"><?php echo $match['TeamB']['name']; ?></option>
					</select>
				</label>
			</div>
		</div>
		<input type="submit" class="tiny button orphan-center">
	</section>
</form>
