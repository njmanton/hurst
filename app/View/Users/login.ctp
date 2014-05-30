<?php //$this->layout = 'login'; ?>
<section>
	<h2>Please enter your username and password</h2>
	<div class="userform">
	<?php echo $this->Session->flash(); ?>
	<?php echo $this->Form->create('User');?>
		<fieldset>
		 <?php
				echo $this->Form->input('username');
				echo $this->Form->input('password');
		?>
		</fieldset>
		<input type="submit" class="tiny button" value="Login" />
	<?php echo $this->Form->end();?>
	<a href="/users/forgot">Forgot password?</a>
	</div>
</section>
