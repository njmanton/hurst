<?php $this->set('title_for_layout', __('Request a New League')); ?>
<section class="medium-8 medium-centered columns">
	<h2>Add a new League</h2>
	<p>Complete the form below to request a new league. The request must be approved by an administrator before it will be visible. Once the league has been created, you will be set as the organiser.</p>
	<p>If you set the league to be public, anyone can join the league. The default setting is private, which means the administrator will need to accept or reject any requests to join the league.</p>

	<form action="/leagues/add" method="post" id="NewLeague">
		<fieldset>
			<label for="name">Name of league: 
				<input type="text" required name="data[League][name]" id="name" placeholder="name of league" />
			</label>
			<label for="desc">Description: <span id="descchar"></span>
				<textarea name="data[League][description]" id="desc" cols="40" rows="10" placeholder="enter a description here"></textarea>
			</label>
			<label for="public">Public:
				<input type="checkbox" id="public" name="data[League][public]" value="1" />
			</label>
		</fieldset>
		<input type="submit" class="tiny button" value="Submit" />
	</form>

</section>
