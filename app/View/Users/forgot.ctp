<?php $this->set('title_for_layout', 'Forgotten Password'); ?>
<section class="row">
	<div class="medium-8 medium-centered columns">
		<h2>Forgotten Password</h2>
		<p>Fill out your username and the email you used to register, and you will receive an email with instructions for resetting your password. If you still have problems, send an email to <a href="mailto:admin@worldcup.goalmine.eu">admin@worldcup.goalmine.eu</a>.</p>
		<form action="/users/forgot" method="post" id="forgotPassword" data-abide>
			<label for="uid">Username:
				<input type="text" required autocomplete="off" id="forgotuid" placeholder="username" name="data[User][username]" />
			</label>
			<div id="erroruid" class=""></div>
			<label for="forgotemail">Email address:
				<input type="email" required autocomplete="off" id="forgotemail" placeholder="bob@example.com" name="data[User][email]" />
			</label>
			<input type="Submit" class="tiny button" id="forgotsubmit" disabled value="Find Me" />
		</form>
	</div>
</section>
