<?php $this->set('title_for_layout', 'Reset Password'); ?>
<section class="medium-8 medium-centered columns">
	<h2>Reset your password</h2>
	<p></p>
	<form action="" method="post" id="ResetPassword">
		<label for="resetemail">Email:
			<input type="email" value="" required autocomplete="off" id="resetemail" name="data[Reset][email]" />
		</label>
		<label for="resetpwd">New Password (min 6 characters):
			<input type="password" value="" pattern=".{6,}" required autocomplete="off" id="resetpwd" name="data[Reset][pwd]" />
		</label>
		<label for="rpt">Repeat:
			<input type="password" required pattern=".{6,}" autocomplete="off" id="rpt" name="data[Reset][rpt]" />
		</label>
		<input type="submit" class="tiny button" value="Update" />
	</form>
</section>