<?php $this->set('title_for_layout', 'Invite a friend'); ?>

<section class="row">
	<h2>Send email to users</h2>
	<form action="/users/send" method="post" id="SendUsers">
		<label for="SendUsersBody">Subject
			<input type="text" id="SendUsersBody" name="data[subject]" />
		</label>
		<label for="SendUsersBody">Message
			<textarea name="data[body]" id="SendUsersBody" rows="10"></textarea>
		</label>
		<label>
			<input type="checkbox" value="1" name="data[onlypaid]" /> Only paid
		</label>
		<br />
		<input type="submit" value="send" class="tiny button" />
	</form>

</section>