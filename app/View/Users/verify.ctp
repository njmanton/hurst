<?php $this->set('title_for_layout', 'Verify new user'); ?>
<section class="row">
	<h2>Verify your account</h2>
	<div class="medium-centered medium-8 columns">

		<p>Hi, you've been invited by <a href="/users/<?php echo $tempuser['Referer']['id']; ?>"><?php echo $tempuser['Referer']['username']; ?></a> to create an account to play <?php echo APP_NAME; ?>, a prediction competition for the 2014 World Cup. To finish creating your account, just fill in the fields below. Your email address will only be used to send game updates. It will never be passed on to anyone else, and is not visible to any other user.</p>

		<form action="/users/verify" method="post" accept-charset="utf-8" id="Verify">
			<fieldset>
				<label for="">Choose a username: *</label>
				<input type="text" name="data[User][username]" required id="verifyuid" />
				<div role="alert" id="uid_err"></div>
				<label for="">Email address: *</label>
				<input type="email" name="data[User][email]" id="email" required value="<?php echo $tempuser['User']['email']; ?>" />
				<label for="">Choose a password: *</label>
				<input type="password" name="data[User][password]" required id="verifypwd" />
				<div role="alert" id="pwd_err"></div>
				<label for="">Repeat password: *</label>
				<input type="password" name="data[User][repeat]" required id="verifyrpt" />
				<div role="alert" id="rpt_err"></div>
			</fieldset>
			<input type="hidden" name="data[User][id]" value="<?php echo $tempuser['User']['id']; ?>" />
			<input type="submit" class="tiny button" value="Confirm" />
		</form>
	</div>

</section>

